<?php

namespace Secure\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Secure\Controller\Plugin\EmailPlugin;
use Secure\Controller\Plugin\CommonClass;
use Secure\View\Helper\CommonHelper;
use Checkondispatch\Custom\AbstractApplicationController;
use Zend\Http\Client as HttpClient;
use Secure\Model\ReportTable;
use Secure\Model\UserTable;
use Secure\Form\SignUpForm;
use Secure\Form\LoginForm;
use Secure\Model\LoginInputFilter;

class IndexController extends AbstractApplicationController {

    protected $acceptCriteria = [
        'Zend\View\Model\JsonModel' => ['application/json'],
        'Zend\View\Model\ViewModel' => ['text/html'],
    ];
    private $db;

    public function __construct() {
        $this->container = new Container('auth');
    }

    //initialize UserTable object for CRUD 
    function initModelObject() {
        $dbadapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    }

    /**
     * @desc login user
     */
    public function indexAction() {
        $this->CommonClass()->setCustomLayout($this, 'loginLayout');
        $viewModel = $this->acceptableviewmodelselector($this->acceptCriteria);
        $env = strtolower(getenv('APP_ENV'));

        $usrData = !empty($this->container->authDetail) ? $this->container->authDetail : array();
        $signUpForm = new SignUpForm($this->getRequest()->getBaseUrl() . '/upload/captcha/', $this->returnBasePath());
        $loginForm = new LoginForm();
        $viewModel->setVariables([
            'form' => $signUpForm,
            'loginFrm' => $loginForm,
            'baspath' => $this->returnBasePath(),
            'usrData' => $usrData
        ]);

        return $viewModel;
    }

    //function to login user
    /**
     * @desc login user
     */
    public function loginAction() {
        $referer = $this->getRequest()->getHeader('Referer');
        $translator = $this->getServiceLocator()->get('translator');
        $loginForm = new LoginForm($translator);
        $refererURL = '';

        $this->checkUserAuthentication();
        $this->CommonClass()->setCustomLayout($this, 'loginLayout');
        $result = ['result' => false, 'message' => ''];
        $viewModel = $this->acceptableviewmodelselector($this->acceptCriteria);
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $postData = $request->getPost()->toArray();

            $loginData['csrf'] = $postData['csrf'];
            $loginData['username'] = $postData['username'];
            $loginData['password'] = $postData['password'];
            unset($postData);
            $login = new LoginInputFilter($loginData);
            $loginForm->setInputFilter($login->getInputFilter());
            $loginForm->setData($request->getPost());
            $dbadapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $secureTable = new UserTable($dbadapter);
            $isUserExist = $secureTable->checkUserExist($loginData);

            $bErrorFlag = true;
            $aError = array();


            if ($loginForm->isValid() && $bErrorFlag) {
                $resArray = $secureTable->userLogin($loginData);
                if (!empty($resArray)) {
                    $response['message'] = 'Success';
                    $response['data']['msg'] = 'Success';
                    if (!empty($response) && $response['message'] == 'Success') {
                        $this->setUserSession($resArray);
                        $this->setCookie($resArray);
                        $result = ['result' => true, 'data' => $resArray['USER_OID'], 'current_path_redirect' => 'Y'];
                    } else {
                        $aError["username"] = array("isEmpty" => $response['error']['message']);
                        $aDataError = array_merge($aError, $loginForm->getMessages());
                        $result = ['result' => false, 'message' => $aDataError];
                    }
                } else {
                    $result = ['result' => false, 'message' => 'Invalid Username/Password !'];
                }
            } else {
                $aDataError = array_merge($aError, $loginForm->getMessages());
                $result = ['result' => false, 'message' => $aDataError];
            }
        } else {
            $result = ['result' => false, 'message' => 'Request is not valid'];
        }
        if (!$viewModel instanceof JsonModel && $request->isXmlHttpRequest()) {
            $viewModel = new JsonModel();
        }
        $viewModel->setVariables(['form' => $loginForm, 'data' => $result]);
        return $viewModel;
    }

    /**
     * @desc set cookie for remember me
     */
    function setCookie($data) {
        $cookie1 = new \Zend\Http\Header\SetCookie('unicef_user_id', $data['USER_OID'], time() + (((30 * 24) * 60) * 60), '/');
        $this->getResponse()->getHeaders()->addHeader($cookie1);
    }

    /**
     * @desc set user session after authentication
     */
    function setUserSession($data) {
        $this->initModelObject();
        $userSessionData['USER_OID'] = $data['USER_OID'];
        $userSessionData['USERNAME'] = $data['USERNAME'];
        $userSessionData['EMAIL_ID'] = $data['EMAIL_ID'];
        $this->container->authDetail = $userSessionData;
    }

    public function signUpAction() {
        $this->CommonClass()->setCustomLayout($this, 'loginLayout');
        $viewModel = $this->acceptableviewmodelselector($this->acceptCriteria);
        $translator = $this->getServiceLocator()->get('translator');
        $signUpForm = new SignUpForm($this->getRequest()->getBaseUrl() . '/upload/captcha/', $this->returnBasePath());
        $request = $this->getRequest();
        $table = new UserTable($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
        if ($request->isXmlHttpRequest()) {
            $postData = $request->getPost()->toArray();

            $signUpInputFilter = new \Secure\Model\SignUpInputFilter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'), $postData);
            $signUpForm->setInputFilter($signUpInputFilter->getInputFilter());
            $signUpForm->setData($postData);
            $noError = false;
            $aError = array();
            if (!empty($postData["EMAIL_ID"])) {
                $isEmailExist = $table->checkEmailExist($postData["EMAIL_ID"]);
                if ($isEmailExist) {
                    $noError = true;
                    $aError["EMAIL_ID"] = array("isEmpty" => $translator->translate("Email id already exist. Please try with another email id."));
                }
            }

            if (!empty($postData["USER_NAME"])) {
                $isUsernameExist = $table->checkUsernameExist($postData["USER_NAME"]);
                if ($isUsernameExist) {
                    $noError = true;
                    $aError["USER_NAME"] = array("isEmpty" => $translator->translate("Username already exist. Please try with another username."));
                }
            }

            if ($signUpForm->isValid() && !$noError) {
                $saveData = array();
                $nameArr = explode(' ', trim($postData['USER_NAME']));
                if (!empty($nameArr)) {
                    $firstName = ucfirst($nameArr[0]);
                    unset($nameArr[0]);
                    $lastName = implode(" ", ucwords($nameArr));
                } else {
                    $firstName = $postData['USER_NAME'];
                    $lastName = "";
                }
                $saveData['USERNAME'] = $postData['USER_NAME'];
                if (!empty($postData['EMAIL_ID'])) {
                    $saveData['EMAIL_ID'] = $postData['EMAIL_ID'];
                }
                $saveData['PASSWORD'] = sha1($postData['USER_PASSWORD']);
                $saveData['FIRST_NAME'] = $firstName;
                $saveData['LAST_NAME'] = $lastName;
                $saveData['USER_AGE'] = $postData['USER_AGE'];
                $saveData['GENDER_ID'] = $postData['USER_GENDER'];
                $saveData['IP_ADDRESS'] = $this->get_client_ip();
                $saveData['CREATED_ON'] = date('Y-m-d h:i:s');
                $response = $table->saveUserData($saveData);
                $result = ['result' => true, 'data' => $response, 'message' => "Data successfully saved.", 'flag' => true];
            } else {
                $aDataError = ($noError) ? array_merge($signUpForm->getMessages(), $aError) : $signUpForm->getMessages();
                $captchaError = '';
                if (!empty($aDataError['captcha'])) {
                    $captchaError = $aDataError['captcha']['badCaptcha'];
                }
                $result = ['result' => false, 'captchaError' => $captchaError, 'message' => $aDataError];
            }
            if (!$viewModel instanceof JsonModel && $request->isXmlHttpRequest()) {
                $viewModel = new JsonModel();
            }
            $viewModel->setVariables(['data' => $result, 'form' => $signUpForm,]);
        } else {
            $viewModel->setVariables([
                'form' => $signUpForm,
            ]);
        }
        return $viewModel;
    }

    public function refreshCaptchaAction() {

        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $ConfigHelper = $viewHelperManager->get('Config');
        $signUpForm = new SignUpForm($this->getRequest()->getBaseUrl() . '/upload/captcha/', $this->returnBasePath());
        $captcha = $signUpForm->get('captcha')->getCaptcha();
        $data = array();
        $data['id'] = $captcha->generate();
        $data['src'] = $captcha->getImgUrl() . $captcha->getId() . $captcha->getSuffix();
        return new JsonModel($data);
    }

    /**
     * @desc logout User
     */
    public function logoutAction() {
        $auth = new AuthenticationService();

        if (!empty($this->container->authDetail)) {
            $auth->clearIdentity();
            setcookie("unicef_user_id", "", time() - 3600);
            $cookie = new \Zend\Http\Header\SetCookie('unicef_user_id', '', strtotime('-1 Year', time()), '/');
            $this->getResponse()->getHeaders()->addHeader($cookie);
            $this->container->getManager()->getStorage()->clear('auth');
            $this->container->getManager()->getStorage()->clear('returnurl');
            return $this->redirect()->toRoute('home');
        }
        return $this->redirect()->toRoute('home');
    }

}
