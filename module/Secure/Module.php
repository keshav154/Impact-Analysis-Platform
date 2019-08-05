<?php

namespace Secure;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\I18n\Translator\Translator;
use Zend\Session\Container;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module {

    public function onBootstrap(MvcEvent $e) {

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $session = new Container('language');
        if (isset($session->language)) {
            $e->getApplication()->getServiceManager()->get('translator')->setLocale($session->language);
            $this->initTranslator($e, $session->language);
        }
    }

    /*
     * @desc   translate error message
     */

    protected function initTranslator($e) {

        $config = $e->getApplication()->getServiceManager()->get('config');
        $list = $config['locale']['languages'];
        $session = new Container('language');

        $translator = $e->getApplication()->getServiceManager()->get('translator');

        $translator->addTranslationFile(
                'phpArray', 'config/autoload/language/validations/' . $list[$session->language]['err'] . '/Zend_Validate.php'
        );

        \Zend\Validator\AbstractValidator::setDefaultTranslator($translator);
    }

    public function getConfig() {
        $config = array();
        $configFiles = array(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/module.navigation.config.php',
        );
        foreach ($configFiles as $file) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, $file);
        }
        return $config;
    }

    public function getAutoloaderConfig() {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Checkondispatch' => __DIR__ . '/../../library',
                ],
            ],
        ];
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'config' => function($serviceManager) {
                    $helper = new \Secure\View\Helper\Config($serviceManager);
                    return $helper;
                },
            )
        );
    }

}
