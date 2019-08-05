<?php

namespace Api\V1\Rest\AppActivity;

use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class AppActivityMapper {

    protected $adapter;
    protected $table;

    public function __construct(AdapterInterface $adapter) {
        $this->adapter = $adapter;
    }

    //initialize object using TableGateway
    public function initTableObject($tableName) {
        $initTableObj = new TableGateway($tableName, $this->adapter);
        return $initTableObj;
    }

    /**
     * @desc used of this function for fetch data from any table on condition base
     */
    public function fetchData($conditions, $table) {
        $this->table = $this->initTableObject($table);
        return $this->table->select($conditions)->count();
    }
    
    /**
     * @desc used of this function for insert bulk data
     * @param stirng $table_name
     * @param array $data
     * 
     * @return int lastInsertValue
     */
    function insertBulkData($table_name, $data) {
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
            return $statement->execute();
    }

    /**
     * @desc used of this function for close db connection
     */
    function __destruct() {
        $this->adapter->getDriver()->getConnection()->disconnect();
    }

    
}
