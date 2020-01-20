<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Secure\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Adapter;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate;
use Secure\Controller\Plugin\CommonClass;
use Checkondispatch\Custom\AbstractModelClass;

/**
 * Description of UserTable
 *
 * @author Anant.Sharma
 */
class UserTable {

    protected $tableGateway;
    protected $adapter;
    protected $table;

    /* Constructor: To define every table in table gateway and 
      use them as required to run sql queries */

    public function __construct($db) {
        $this->adapter = $db;
    }

    //initialize table object for each table
    public function initTableObject($tableName) {
        $tableGatewayObj = new TableGateway($tableName, $this->adapter);
        return $tableGatewayObj;
    }

    /**
     * @deac this function used to get user data by login
     * @author Anant Sharma<anant.sharma@veative.com>
     * @param type $data
     * @return type
     */
    public function userLogin($data) {
        try {

            $this->table = $this->initTableObject('t_unicef_user');
            $select = $this->table->getSql()->select();
            $select->columns(array('*'));

            $select->where->nest
                            ->equalTo('EMAIL_ID', $data['username'])
                            ->or
                            ->equalTo('USERNAME', $data['username'])
                    ->unnest;
            $select->where(array('PASSWORD' => sha1($data['password'])));

            $resultSet = $this->table->selectWith($select);
            return $resultSet->current();
        } catch (\Exception $e) {
            //$this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
        }
    }

    //get user Role by UserType ID
    public function checkUserExist($data) {
        $this->table = $this->initTableObject('t_unicef_user');
        $select = $this->table->getSql()->select();
        $select->columns(array('USER_OID'));
        $select->where(array('PASSWORD' => sha1($data['password'])));
        $select->where->nest
                        ->equalTo('EMAIL_ID',$data['username'])
                        ->or
                        ->equalTo('USERNAME',$data['username'])
                ->unnest;

        $resultSet = $this->table->selectWith($select);
        return $resultSet->current();
    }

    //get user Role by UserType ID
    public function checkEmailExist($emailId) {
        $this->table = $this->initTableObject('t_unicef_user');
        $select = $this->table->getSql()->select();
        $select->columns(array('USER_OID'));
        $select->where(array('EMAIL_ID' => $emailId));

        $resultSet = $this->table->selectWith($select);
        return $resultSet->count();
    }

    public function checkUsernameExist($userName, $password = "") {
        $this->table = $this->initTableObject('t_unicef_user');
        $select = $this->table->getSql()->select();
        $select->columns(array('USER_OID'));
        $select->where(array('USERNAME' => $userName));
        if (!empty($password)) {
            $select->where(array('PASSWORD' => sha1($password)));
        }
        $resultSet = $this->table->selectWith($select);
        return $resultSet->count();
    }

    //get user Role by UserType ID
    public function getUserData($column, $value) {
        $this->table = $this->initTableObject('t_unicef_user');
        $select = $this->table->getSql()->select();
        $select->columns(array('*'));
        $select->where(array($column => $value));
        $resultSet = $this->table->selectWith($select);
        return $resultSet->current();
    }

    public function saveUserData($data, $id = 0) {
        try {
            $array = array();
            $this->table = $this->initTableObject('t_unicef_user');
            if ($id) {
                $this->table->update($data, array('USER_OID' => $id));
                $array['unicef_user_id'] = $id;
                $array['unicef_fname'] = $data['USERNAME'];
                $array['unicef_age'] = $userDataToSave['USER_AGE'];
                $array['unicef_gender'] = $userDataToSave['GENDER_ID'];
            } else {
                $dataIns = $this->table->insert($data);
                $lastInsert = $this->table->lastInsertValue;
                $array['unicef_user_id'] = $lastInsert;
                $array['unicef_fname'] = $data['USERNAME'];
                $array['unicef_age'] = $userDataToSave['USER_AGE'];
                $array['unicef_gender'] = $userDataToSave['GENDER_ID'];
            }
            return $array;
        } catch (\Exception $e) {
            //$this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
        }
    }

    /**
     * @desc used of this function for convert obj to array
     */
    public function returnPrepareResult($result) {
        $rows = array();
        if ($result->count()) {
            $rows = new ResultSet();
            return $rows->initialize($result)->toArray();
        }
    }

}
