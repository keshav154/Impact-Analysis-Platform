<?php

namespace Checkondispatch\Custom;

use Zend\Authentication\Storage\Session;
use Zend\Session\Container;
use Aws\Sdk;
use Checkondispatch\Custom\IP2Location;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

trait CommonFunctionTrait {

    /**
     * @desc used of this fuction for set meta data (title, keywords and description)
     */
    public function setMetaData($contAndActName, $config) {
        if (isset($config[$contAndActName])) {
            $param = [
                'title' => $config[$contAndActName]['title'],
                'keywords' => $config[$contAndActName]['keywords'],
                'description' => $config[$contAndActName]['description'],
                'canonical' => (empty($config[$contAndActName]['canonical'])) ? '' : $config[$contAndActName]['canonical']
            ];
        } else {
            $param = [
                'title' => $config['defaultTitle'],
                'keywords' => $config['defaultKeywords'],
                'description' => $config['defaultDescription']
            ];
        }
        $this->CommonClass()->setMetaData($this, $param);
    }

    /**
     * @desc used of this fuction for retrieve db connection
     */
    public function getAdapter() {
        return $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    }

    /**
     * @desc used of this fuction for initialize for table object
     * @param string $table
     */
    public function initTable($table, $adapter = "Zend\Db\Adapter\Adapter") {
        return new $table($this->getAdapter());
    }

    /**
     * @desc used of this fuction for get helper in the controller
     */
    public function getHelperManager() {
        return $this->getServiceLocator()->get('ViewHelperManager');
    }

    /**
     * @desc used of this function for return baser path
     */
    public function returnBasePath() {
        return $this->getRequest()->getUri()->getScheme() . "://" . $this->getRequest()->getUri()->getHost() . $this->getRequest()->getBasePath();
    }

    /**
     * @desc used of this function for filter ckeditor data
     */
    public function replaceCkeditorData($qus) {
        $content    = preg_replace('/<img[^>]+\>/i', '[IMG]', $qus);
        return $escapedVal = strip_tags($content);
    }

    /**
     * @desc used of this for print variable
     * @param string|array $var
     * @param boolean $isEcho
     * @response mixed
     */
    public function debug($var, $label = null, $isEcho = false) {
        return \Zend\Debug\Debug::dump($var, $label, $isEcho);
    }

    /**
     * @desc used of this function for redirect on dashboard as per logged user
     */
    public function checkUserAuthentication() {        
        $ut_id                = (!empty($this->container->authDetail['UT_ID'])) ? $this->container->authDetail['UT_ID'] : null;
        switch ($ut_id) {
            case 4:
                return $this->redirect()->toRoute('agency');
                break;
            case 3:
                return $this->redirect()->toRoute('citymanager');
                break;
            case 1:
                return $this->redirect()->toRoute('administrator', array('action' => 'dashboard'));
                break;
            default:
                return false;
        }
    }

    /*
     * @desc   Use of this function to get current country code from IP
     * @return country code as string
     */

    public function get_country_code($ipaddress) {
        $db      = new IP2Location('data/iptocountrydata/IP-COUNTRY.BIN', \Checkondispatch\Custom\IP2Location::FILE_IO);
        $records = $db->lookup($ipaddress, \Checkondispatch\Custom\IP2Location::ALL);
        if (!empty($records['countryCode'])) {
            return $records['countryCode'];
        } else {
            $config = $this->serviceLocator->get('config');
            $lang   = $config['locale']['available'][$config['locale']['default']];
            return $lang;
        }
    }

    //get IP address as per location
    public function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        $expIp     = explode(",", $ipaddress);
        if (count($expIp) > 1) {
            $ipaddress = $expIp[0];
        }
        return $ipaddress;
    }

    public function checkRedirection() {
        $session                      = new Container('language');
        $config                       = $this->serviceLocator->get('config');
        $getLocalDefault              = $config['locale']['default'];
        $ipaddress                    = ($config['liveurl']) ? $this->get_client_ip() : '195.229.41.195';
        $countryCode                  = $this->get_country_code($ipaddress);
        $this->container->countryCode = $countryCode;
        $languageCode                 = $this->params('clang');
        if (isset($session->language)) {
            $config = $this->serviceLocator->get('config');
            if (isset($config['locale']['languages'][$session->language]['ISO_3166'])) {
                $countryCode = $config['locale']['languages'][$session->language]['ISO_3166'];
            }
            return false;
        }
        $newCountryCode = "";
        if ($countryCode) {
            $currentCountryCode = array_search($countryCode, $config['locale']['available']);
            if ($currentCountryCode) {
                $newCountryCode = str_replace($countryCode . "_", "", $currentCountryCode);
            }
            if ($newCountryCode != $getLocalDefault && $languageCode == '') {
                if (!empty($config['locale']['available'][$currentCountryCode])) {
                    return $this->redirect()->toUrl($this->getRequest()->getUriString() . strtolower($countryCode));
                }
            } else if ($languageCode == '') {
                if (isset($config['locale']['available'][$currentCountryCode])) {
                    return $this->redirect()->toUrl($this->getRequest()->getUriString() . strtolower($countryCode));
                }
            }
        }
    }

    public function isUserLoggedIn() {
        if (!empty($this->container->authDetail)) {
            return true;
        } else {
            return false;
        }
    }

    public function crypto_rand_secure($min, $max) {
        $range  = $max - $min;
        if ($range < 1)
            return $min; // not so random...
        $log    = ceil(log($range, 2));
        $bytes  = (int) ($log / 8) + 1; // length in bytes
        $bits   = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public function getToken($length) {
        $token        = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max          = strlen($codeAlphabet); // edited
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max - 1)];
        }
        return $token;
    }

    /**
     * @desc used of this fuction for check user type b2b and b2c
     */
    private function checkUserType() {
        $utId     = $this->container->authDetail['UT_ID'];
        $orgId    = isset($this->container->authDetail['ORG_ID']) ? $this->container->authDetail['ORG_ID'] : '';
        $userType = $this->container->authDetail['USER_TYPE'];

        switch ($utId) {
            case ($utId == 1 && empty($orgId)):
                return $userType;
                break;
            case ($utId == 2 && !empty($orgId)):
                return $userType . "-B";
                break;
            default:
                return $userType;
        }
    }

    /**
     * 
     *
     * @desc      Used for generating Log
     *
     * @package    ITC
     * @author     Aditya Kumar
     * @param      int|string $errno 
     * @param      int|string $errstr
     * @param      int|string $errline
     * @param      int|string $errfile
     * @return 
     */
    public function log($errno = "", $errstr = "Unknown Message", $errline = "Unknown Line", $errfile = "Unknown File", $email = false, $sendEmail = "") {
        /* First create an instance of Logger class */
        $logger = new Logger;
        /* Create a writer object */
        $writer = new Stream("data/log/php-" . date('Y-m-d') . '-error.log');    /* give the path of the log file */
        $logger->addWriter($writer);
        $log    .= "<b>ERROR Message= $errno $errstr\n";
        $log    .= "  Error on line $errline in file $errfile";
        $log    .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")\n";

        $logger->log(Logger::INFO, $log); /* log() function is used to log data in the storage specified */

        if ($email == true) {
            $sendEmail      = empty($sendEmail) ? "deepak.gupta@veative.com" : $sendEmail;
            $emailPluginObj = new \Secure\Controller\Plugin\EmailPlugin();
            $body           = $log;
            $emailPluginObj->sendMail($sendEmail, $body);
        }
    }

    /**
     * @desc      Used for validate json
     * @package    ITC
     * @author     Rahbar Raza Zaidi
     * @param      string 
     * @return 
     */
    function validate_json($str = NULL) {
        if (is_string($str)) {
            @json_decode($str);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

    /**
     * @desc used of this function for check date is expired or not
     * @return true/false
     */
    private function checkUserExpiry($account_expiry) {
        date_default_timezone_set('Asia/Kolkata');
        $today  = date("Y-m-d H:i:s");
        $result = false;
        if (strtotime($account_expiry) > strtotime($today)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    /**
     * @desc used of this function to return array of nested object
     * @package ITC
     * @author  Aditya Kumar
     * @return array()
     */
    function objToArray($obj, &$arr) {

        if (!is_object($obj) && !is_array($obj)) {
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value) {
            if (!empty($value)) {
                $arr[$key] = array();
                $this->objToArray($value, $arr[$key]);
            } else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    /**
     * @desc This function use to generate Numeric OTP
     * @author Anant Sharma<anant.sharma@veative.com>
     * @param $length
     * @return number Description
     */
    public function generateOtp($length = 6) {
        $token      = "";
        $codeNumber = "0123456789";
        $max        = strlen($codeNumber); // edited
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeNumber[$this->crypto_rand_secure(0, $max - 1)];
        }
        return $token;
    }
    

}
