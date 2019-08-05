<?php

namespace Secure\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        
    }

}
