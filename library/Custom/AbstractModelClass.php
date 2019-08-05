<?php

/**
 * @desc make a model class for used with table class
 * @created on 01-08-2017
 * @author Khushboo Tanwar
 */

namespace Checkondispatch\Custom;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Adapter;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate;
use Checkondispatch\Custom\CommonFunctionTrait;

abstract class AbstractModelClass {

    protected $adapter;

    /* Constructor: To define every table in table gateway and 
      use them as required to run sql queries */

    public function __construct($db) {
        $this->adapter = $db;
    }

    //careate TableGateway object for tables
    public function initTableObject($tableName) {
        $initTableObj = new TableGateway($tableName, $this->adapter);
        return $initTableObj;
    }

    /**
     * @desc used of this function for insert data
     * @param stirng $table
     * @param array $data
     * 
     * @return int lastInsertValue
     */
    function insertData($table, $data) {
        try{
            $table = $this->initTableObject($table);
            $table->insert($data);
            return $table->getLastInsertValue();
        } catch (\Exception $e) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(),true);
            return array();
        } 
    }

    /**
     * @desc used of this function for fetch data from any table on condition base
     * @param stirng $table
     * @param array $data
     * @param array $condition
     * @param boolean $resultType
     * 
     * @return array 
     */
    public function fetchData($table, $conditions = array(), $columns = array(), $orderBy = '', $resultType = true, $length = '', $start = '') {
        try{
            $this->table = $this->initTableObject($table);
            $select = $this->table->getSql()->select();
            if (!empty($columns)) {
                $select->columns($columns);
            }
            if (!empty($conditions)) {
                $select->where($conditions);
            }
            if (!empty($orderBy)) {
                $select->order($orderBy);
            }
            if($table=='t_org_licence'){
                $select->order(new \Zend\Db\Sql\Expression("DATE(t_org_licence.CREATED_ON) desc")); 
            }
            if (!empty($length)) {
                $select->limit((int) $length);
            }
            if (!empty($start)) {
                $select->offset((int) $start);
            }

            if ($resultType)
                return $this->table->selectWith($select)->toArray();
            else
            return $this->table->selectWith($select)->current();
        } catch (\Exception $e) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(),true);
            return array();
        } 
        
    }

    /**
     * @desc used of this function for count data from any table on condition base
     * @param stirng $table
     * @param array $condition
     * @return array 
     */
    public function countData($table, $conditions = array()) {
        try{
                $this->table = $this->initTableObject($table);
                $select = $this->table->getSql()->select();
                if (!empty($columns)) {
                    $select->columns($columns);
                }
                if (!empty($conditions)) {
                    $select->where($conditions);
                }

                $resultSet = $this->table->selectWith($select);
                $count = $resultSet->count();
                return $count;
        } catch (\Exception $e) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(),true);
            return array();
        } 
        
    }

    /**
     * @desc used of this function update data
     * @param stirng $table
     * @param array $data
     * @param array $condition
     * 
     * @return boolean $isUpdated
     */
    public function updateData($table, $data, $condition) {
        try{
            $this->table = $this->initTableObject($table);
            return $isUpdated = $this->table->update($data, $condition);
        } catch (\Exception $e) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(),true);
            return array();
        } 
        
    }

    /**
     * @desc used of this function to delete data
     * @param stirng $table
     * @param array  $condition
     * 
     * @return boolean $isDeleted
     */
    public function deleteData($table, $condition) {
        try{
            $this->table = $this->initTableObject($table);
            return $isDeleted = $this->table->delete($condition);
        } catch (\Exception $e) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(),true);
            return array();
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

    /** used of this function for print query */
    private function printquery() {
        $profiler = $this->adapter->getProfiler();
        $queryProfiles = $profiler->getQueryProfiles();

        foreach ($queryProfiles as $key => $row) {
            print_r($row->toArray());
        }
    }

    /*
     * @desc Function written to make publish/unpublish any data from any table
     * @param int/string (1 or 0/ Y or N) $publishValue
     * @param int publishContentId
     * @param int $publishId
     * @param string $table
     * @param string $column
     * @param string $whereColumn
     */

    public function publishUnpublishContent($publishValue, $publishId, $table, $column, $whereColumn) {
        $this->table = $this->initTableObject($table);
        $updateData[$column] = $publishValue;
        return $this->table->update($updateData, array(
                    $whereColumn => $publishId
        ));
    }
    
    
    /**
     * @desc used of this function for insert bulk data
     * @param stirng $table_name
     * @param array $data
     * 
     * @return int lastInsertValue
     */
    function insertBulkData($table_name, $data) {
        try {
            $query = "INSERT INTO " . $table_name;
            if (!empty($data)) {
                $values = array();
                foreach ($data as $key => $item) {
                    if ($key == 0) {
                        $tablekey = array_keys($item);
                    }
                    $addString = "(";
                    foreach ($item as $addValues) {
                        $addString .= "'{$addValues}',";
                    }
                    $addString = rtrim($addString, ',');
                    $addString .= ")";
                    $values[] = $addString;
                }
                $values = implode(", ", $values);
                $query .= "( " . implode(',', $tablekey) . ") VALUES  {$values}";
            }
            $statement = $this->adapter->createStatement($query);
            $statement->prepare();
            $result = $statement->execute();
        } catch (\Exception $e) {
            $this->log($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), true);
            return array();
        }
    }
    
        
    
    
    
    
    
    use CommonFunctionTrait;
}
