<?php
//Author- Anant sharma
namespace Secure\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController {    

    public function __construct() {
    }

    //initialize SecureTable object for CRUD 
    function initModelObject() {
        $dbadapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
    }

    /**
     * @desc login user
     */
    public function indexAction() {
        
    }

}
