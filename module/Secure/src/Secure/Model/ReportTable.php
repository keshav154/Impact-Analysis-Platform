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
 * Description of ReportTable
 *
 * @author Anant.Sharma
 */
class ReportTable {

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
     * @desc : this function used to get activity
     * @author Anant Sharma<anant.sharma@veative.com>
     * @return array Description
     */
    public function getActivityTableColumn() {
        try {
            $sql       = "DESCRIBE t_unicef_usractivity";
            $statement = $this->adapter->createStatement($sql);
            $statement->prepare();
            $result    = $statement->execute();
            return $this->returnPrepareResult($result);
        } catch (\Exception $ex) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
        }
    }

    /**
     * @desc : this function used to get activity
     * @author Anant Sharma<anant.sharma@veative.com>
     * @return array Description
     */
    public function getUserActivity($pagingData = array()) {
        try {
            $length      = !empty($pagingData['LENGTH']) ? intval($pagingData['LENGTH']) : 10;
            $start       = !empty($pagingData['START']) ? intval($pagingData['START']) : 0;
            $order       = !empty($pagingData['ORDER']) ? $pagingData['ORDER'] : 'DESC';
            $orderBy     = !empty($pagingData['COLUMN']) ? $pagingData['COLUMN'] : 'ACTIVITY_ID';
            $searchKey   = $pagingData['SEARCH'];
            $this->table = $this->initTableObject('t_unicef_usractivity');
            $select      = $this->table->getSql()->select();
            $select->columns(array('*'));
            if (!empty($searchKey)) {
                $select->where->nest
                                ->like('t_unicef_usractivity.USR_NAME', '%' . $searchKey . '%')
                        ->unnest;
            }
            $select->order("$orderBy $order");
            $select->limit($length);
            $select->offset($start);

            $resultSet = $this->table->selectWith($select);
             
            return $resultSet->toarray();
        } catch (\Exception $ex) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
        }
    }

    public function getUserActivityCount($pagingData = array()) {
        try {
            $order       = !empty($pagingData['ORDER']) ? $pagingData['ORDER'] : 'DESC';
            $orderBy     = !empty($pagingData['COLUMN']) ? $pagingData['COLUMN'] : 'ACTIVITY_ID';
            $searchKey   = $pagingData['SEARCH'];
            $this->table = $this->initTableObject('t_unicef_usractivity');
            $select      = $this->table->getSql()->select();
            $select->columns(array('*'));
            if (!empty($searchKey)) {
                $select->where->nest
                                ->like('t_unicef_usractivity.USR_NAME', '%' . $searchKey . '%')
                        ->unnest;
            }
            $select->order("$orderBy $order");
            $resultSet = $this->table->selectWith($select);
            return $resultSet->count();
        } catch (\Exception $ex) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
        }
    }
    
    public function getAllModule( ) {
        try {
             
            $this->table = $this->initTableObject('t_unicef_usractivity');
            $select      = $this->table->getSql()->select();
            $select->columns(array('GL_MODULE_NAME', 'SCORE' => new Expression('round((sum(TR_USER_SCORE)/sum(LL_MAX_SCORE)*100),2)')));
            $select->group('GL_MODULE_ID');
            $resultSet = $this->table->selectWith($select);
             
            return $resultSet->toArray();
        } catch (\Exception $ex) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
        }
    }
    public function getAllModuleAttemt() {
        try {             
            $this->table = $this->initTableObject('module_attempt');
            $select      = $this->table->getSql()->select();
            $select->columns(array('GL_MODULE_NAME', 'COUNT' => new Expression('count(GL_MODULE_NAME)')));
            $select->group('GL_MODULE_NAME');
            $resultSet = $this->table->selectWith($select);
            return $resultSet->toArray();
        } catch (\Exception $ex) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
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
