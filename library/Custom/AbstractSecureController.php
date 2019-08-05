<?php

/**
 * @desc use of this controller to set navigation for secure module and check authentication
 * @created on 16-06-2016
 * @author Deepak Gupta
 */

namespace Checkondispatch\Custom;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Session\Container;
use Checkondispatch\Custom\Acl;
use Aws\Sdk;
use Checkondispatch\Custom\CommonFunctionTrait;


class AbstractSecureController extends AbstractActionController {

    /**
     * @desc initialize session container
     */
    public function __construct() {
        $this->container = new Container('auth');
    }

    /**
     * @desc set view property
     */
    protected $acceptCriteria = [
        'Zend\View\Model\JsonModel' => ['application/json'],
        'Zend\View\Model\ViewModel' => ['text/html'],
    ];

    /**
     * @desc used of this fuction for execute before any action 
     * and set title, left navigation and check authentication
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $event) {
        $controller = $event->getRouteMatch()->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');
        if (empty($this->container->authDetail)) {
            return $this->redirect()->toRoute('secure', array('action' => 'login'));
        } else {
            $config = $this->serviceLocator->get('config');
            if (empty($this->container->language)) {
                $this->container->language = $config['locale']['default'];
            }
            $userType = $this->checkUserType();
            $controllerName = explode('\\', $controller);
            $contAndActName = strtolower($controllerName[2]) . '-' . strtolower($action);
            $activeClass = (isset($config[$userType . '-A'][$contAndActName])) ? $config[$userType . '-A'][$contAndActName] : $config[$userType . '-A']['default'];

            $this->layout()->setVariables(array('Side_Bar_Navigation' => $config[$userType], 'Active_Class' => $activeClass));
            $this->setMetaData($contAndActName, $config);

            $acl = new Acl();
            $acl->initAcl();
            $status = $acl->isAccessAllowed($userType, $controller, $action);
            if (!$status) {
                $route = $config['user_default_redirect'][$userType]['route'];
                $action = $config['user_default_redirect'][$userType]['action'];
                $this->flashMessenger()->addMessage(array('permission_denied' => 'Permission denied! You can not access this page.'));
                return $this->redirect()->toRoute($route, array('action' => $action));
            }
        }

        return parent::onDispatch($event);
    }

    use CommonFunctionTrait;
}
