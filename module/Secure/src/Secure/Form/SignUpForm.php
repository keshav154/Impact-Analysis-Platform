<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Secure\Form;

use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;

/**
 * Description of SignUpForm
 *
 * @author Anant.Sharma
 */
class SignUpForm extends Form {

    public function __construct($captchaUrl, $baseUrl) {
        parent::__construct('userSignUpFrm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->add([
            'name' => 'FULL_NAME',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Full Name',
                'id' => 'FULL_NAME',
                'maxlength' => 20
            ],
            'options' => [
                'label' => 'Full Name',
            ],
        ]);

        $this->add([
            'name' => 'EMAIL_ID',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Email Id(Optional)',
                'id' => 'EMAIL_ID',
            ],
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add([
            'name' => 'USER_NAME',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Username',
                'id' => 'USER_NAME',
                'maxlength' => 20
            ],
            'options' => [
                'label' => 'Username',
            ],
        ]);

        $this->add([
            'name' => 'USER_PASSWORD',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Password',
                'id' => 'USER_PASSWORD',
            ],
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'name' => 'USER_AGE',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Age',
                'id' => 'USER_AGE',
                'maxlength' => 2,
                'onkeypress' => 'return isNumberKey(event);',
            ],
            'options' => [
                'label' => 'Age',
            ],
        ]);

        $this->add([
            'name' => 'USER_GENDER',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'USER_GENDER',
            ),
            'options' => array(
                'value_options' => array('' => 'Select Gender', 'M' => 'Male', 'F' => 'Female'),
            ),
        ]);

        $dirdata = './public/upload';
        $captchaImage = new CaptchaImage(array(
            'font' => $dirdata . '/fonts/arial.ttf',
            'width' => 360,
            'height' => 85,
            'wordLen' => 5,
            'dotNoiseLevel' => 10,
            'lineNoiseLevel' => 1)
        );
        $captchaImage->setImgDir($dirdata . '/captcha');
        $captchaImage->setImgUrl($captchaUrl);

        //add captcha element...
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'captcha' => $captchaImage,
            ),
            'attributes' => array(
                'class' => 'form-control styled',
                'id' => 'captcha',
                'placeholder' => 'Captcha',
            ),
        ));

        $this->add(array(
            'name' => 'register',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Sign Up',
                'id' => 'userSignUpFrm-btn',
                'class' => 'btn btn-primary btn-sm',
                'onclick' => "submitForm('#userSignUpFrm',event);",
            ),
        ));
    }

}
