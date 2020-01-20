<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Secure\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of SignUpInputFilter
 *
 * @author Anant.Sharma
 */
class SignUpInputFilter implements InputFilterAwareInterface {

    public $FULL_NAME, $EMAIL_ID, $USER_NAME, $USER_PASSWORD, $USER_AGE, $USER_GENDER;
    public $captcha;
    protected $inputFilter;
    protected $dbAdapter;

    public function __construct($dbAdapter, $data) {
        $this->dbAdapter = $dbAdapter;
        $this->userId = (isset($data['USER_NAME'])) ? $data['USER_NAME'] : null;
        $this->EMAIL_ID = (isset($data['EMAIL_ID'])) ? $data['EMAIL_ID'] : null;
    }

    public function exchangeArray($data) {
        $this->FULL_NAME = (isset($data['FULL_NAME'])) ? $data['FULL_NAME'] : null;
        $this->EMAIL_ID = (isset($data['EMAIL_ID'])) ? $data['EMAIL_ID'] : null;
        $this->USER_NAME = (isset($data['USER_NAME'])) ? $data['USER_NAME'] : null;
        $this->USER_PASSWORD = (isset($data['USER_PASSWORD'])) ? $data['USER_PASSWORD'] : null;
        $this->USER_AGE = (isset($data['USER_AGE'])) ? $data['USER_AGE'] : null;
        $this->USER_GENDER = (isset($data['USER_GENDER'])) ? $data['USER_GENDER'] : null;
        $this->captcha = (isset($data['captcha'])) ? $data['captcha'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $inputFilter->add($factory->createInput([
                        'name' => 'FULL_NAME',
                        'required' => true,
                        'filters' => [
                                ['name' => 'StripTags'],
                                ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                                [
                                'name' => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 20,
                                ],
                            ],
                        ],
            ]));
            $inputFilter->add($factory->createInput([
                        'name' => 'USER_NAME',
                        'required' => true,
                        'validators' => [
                                [
                                    [
                                    'name' => 'NotEmpty',
                                    'break_chain_on_failure' => true,
                                    'options' => [
                                        'messages' => [
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter your Username'
                                        ],
                                    ],
                                ],
                                'name' => 'Db\NoRecordExists',
                                'options' => [
                                    'table' => 't_unicef_user',
                                    'field' => 'USERNAME',
                                    'adapter' => $this->dbAdapter,
                                    'exclude' => [
                                        'field' => 'USERNAME',
                                        'value' => $this->userId,
                                    ],
                                    'messages' => [
                                        \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Username already exists in database. Try with another one'
                                    ],
                                ],
                            ],
                                [
                                'name' => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min' => 4,
                                    'max' => 20,
                                ],
                            ],
                            array(
                                'name' => 'Regex',
                                'break_chain_on_failure' => true,
                                'options' => array(
                                    'pattern' => '/^[a-zA-Z0-9]*$/',
                                    'messages' => array(
                                        'regexNotMatch' => 'Please enter alphanumeric & without any space only.'
                                    ),
                                ),
                            ),
                        ],
            ]));
            $inputFilter->add($factory->createInput([
                        'name' => 'EMAIL_ID',
                        'required' => false,
                        'validators' => array(
                            array(
                                'name' => 'EmailAddress',
                                'options' => array(
                                    'domain' => 'true',
                                    'hostname' => 'true',
                                    'mx' => 'true',
                                    'deep' => 'true',
                                    'message' => 'Please Enter Valid Email',
                                ),
                            ),
                            array(
                                'name' => 'Db\NoRecordExists',
                                'options' => [
                                    'table' => 't_unicef_user',
                                    'field' => 'EMAIL_ID',
                                    'adapter' => $this->dbAdapter,
                                    'exclude' => [
                                        'field' => 'EMAIL_ID',
                                        'value' => $this->EMAIL_ID,
                                    ],
                                    'messages' => [
                                        \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Email id already exists in database. Try with another one'
                                    ],
                                ],
                            ),
                        ),
            ]));
            $inputFilter->add($factory->createInput([
                        'name' => 'USER_PASSWORD',
                        'required' => true,
                        'filters' => [
                                ['name' => 'StripTags'],
                                ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                                [
                                'name' => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min' => 4,
                                    'max' => 20,
                                ],
                            ],
                        ],
            ]));

            $inputFilter->add($factory->createInput([
                        'name' => 'USER_AGE',
                        'required' => true,
                        'validators' => [
                                [
                                'name' => 'StringLength',
                                'options' => [
                                    'min' => 1,
                                    'max' => 2,
                                    'inclusive' => false
                                ],
                            ],
                        ],
            ]));

            $inputFilter->add($factory->createInput([
                        'name' => 'USER_GENDER',
                        'required' => true,
            ]));
            $inputFilter->add($factory->createInput([
                        'name' => 'captcha',
                        'required' => true,
            ]));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
