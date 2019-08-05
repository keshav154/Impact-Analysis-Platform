<?php

/**
 * @desc use of this controller for use as a abstract controller
 * @created on 16-06-2016
 * @author Deepak Gupta
 */

namespace Checkondispatch\Custom;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Session\Container;

use Checkondispatch\Custom\CommonFunctionTrait;

class AbstractApplicationController extends AbstractActionController {

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

    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        
        $config = $this->serviceLocator->get('config');
        $language = strtoupper($this->params('clang'));
        $session = new Container('language');
        
        if (empty($this->container->language)) {
            $this->container->language = $config['locale']['default'];
        }

        $controllerName = explode('\\', $this->params('controller'));

        $options = $this->params('options');

        $actionName = $this->params('action');
       
        $controllerName = (isset($controllerName[2])) ? $controllerName[2] : '';
        
        if (!empty($options)) {
            $actionName = $options['action'];
            $controllerName = $options['controller'];
        }

        $contAndActName = strtolower($controllerName) . '-' . strtolower($actionName);
        $this->setMetaData($contAndActName, $config);

        return parent::onDispatch($e);
    }
	
	use CommonFunctionTrait;
}
