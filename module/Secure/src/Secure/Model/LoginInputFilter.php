<?php

namespace Secure\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator;

class LoginInputFilter implements InputFilterAwareInterface {

    public $username;
    public $password;
    protected $inputFilter;

    public function __construct($data) {
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
    }

    public function exchangeArray($data) {
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $inputFilter->add($factory->createInput([
                        'name' => 'username',
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
                        ]
            ]));
            $inputFilter->add($factory->createInput([
                        'name' => 'password',
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

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
