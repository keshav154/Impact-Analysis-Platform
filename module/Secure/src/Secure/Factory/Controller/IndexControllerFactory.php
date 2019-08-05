<?php

namespace Secure\Factory\Controller;

use Secure\Controller\IndexController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class IndexControllerFactory implements FactoryInterface {

    /**
     * @{inheritDoc}
     */
    public function createService(ServiceLocatorInterface $sl) {
        $controller = new IndexController(
                $sl->getServiceLocator()->get('FormElementManager')->get('Secure\Form\LoginForm')
        );
        return $controller;
    }

}
