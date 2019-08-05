<?php

namespace Secure\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager as ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\I18n\Translator\Translator;

class Config extends AbstractHelper {

    protected $serviceManager;

    public function __construct(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    public function getConfig() {
        $translator = $this->serviceManager->getServiceLocator()->get('translator');
        $current_lang = $translator->getLocale();
        $config = $this->serviceManager->getServiceLocator()->get('Config');
        return $config;
    }

}

?>