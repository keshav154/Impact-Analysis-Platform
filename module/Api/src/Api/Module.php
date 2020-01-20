<?php

namespace Api;

use Zend\Mvc\ModuleRouteListener;
use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements ApigilityProviderInterface {

    public function getConfig() {

        $config      = array();
        $configFiles = array(
            include __DIR__ . '/../../config/module.config.php',
            include __DIR__ . '/../../config/apicustom.config.php',
        );

        foreach ($configFiles as $file) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, $file);
        }

        return $config;
    }

    public function onBootstrap(MvcEvent $mvcEvent) {

        $eventManager        = $mvcEvent->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sm = $mvcEvent->getApplication()->getServiceManager();

        $router       = $sm->get('router');
        $request      = $sm->get('request');
        $matchedRoute = $router->match($request);

        $request = $mvcEvent->getRequest();
        $headers = $request->getHeaders();

        if ($headers->has('Accept')) {
            // Accept header present, nothing to do
            return;
        }

        $headers->addHeaderLine('Accept', 'application/hal+json');
    }

    public function getAutoloaderConfig() {
        return array(
            'ZF\Apigility\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(                
                'Api\V1\Rest\AppActivity\AppActivityMapper' => function ($sm) {
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new \Api\V1\Rest\AppActivity\AppActivityMapper($adapter);
                },
                 
            ),
        );
    }

}
