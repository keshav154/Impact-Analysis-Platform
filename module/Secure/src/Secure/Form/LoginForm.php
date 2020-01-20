<?php

namespace Secure\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('loginForm');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 1400
                )
            )
        ));
         
        $this->add([
            'name' => 'username',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'unicef_username',
                 'placeholder' => 'Username',
            ),
            'options' => [
                'label' => 'Username',
            ],
        ]);
        $this->add([
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Password',
                'id' => 'unicef_password',
                 
            ],
        ]);
        
     
        $this->add(array(
            'name' => 'login',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Sign in',
                'id' => 'login-btn',
                'class' => 'btn btn-dark btn-block',
                'onclick' => "loginSubmitForm('#loginForm',event);",
            ),
        ));
    }

}
