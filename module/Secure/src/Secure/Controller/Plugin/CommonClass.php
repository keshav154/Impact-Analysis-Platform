<?php

/**
 * CommonClass
 * common functions use in each controller of modules 
 *
 * @package      ZF2
 * @subpackage   Controller/Plugin
 * @author       Arun Kr Vishwakarma <arun.vishwakarma@pironcorp.com>
 * @created on   7 June, 2016
 */

namespace Secure\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;


class CommonClass extends AbstractPlugin {

    //set custom layouts
    public function setCustomLayout($thisContObj, $customLayout) {
        $layout = $thisContObj->layout();
        $layout->setTemplate("layout/$customLayout");
    }

    //set template on layout
    public function setCommonTemplate($viewModel, $module, $contDir, $tempName) {
        $elem = clone $viewModel;
        $elem->setTemplate("$module/$contDir/$tempName.phtml");
        $viewModel->addChild($elem, $tempName);
    }

    //get PC IP address
    function getIpAddress() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        $expIp     = explode(",", $ipaddress);
        if (count($expIp) > 1) {
            $ipaddress = $expIp[0];
        }
        return $ipaddress;
        //return getHostByName(getHostName());
    }

    /**
     * @desc To set pagination data for user
     * @param requested data by data table,default sorting column, sorting column array
     * @return pagination data
     * @author Umar Farooque Khan
     */
    function setAndGetPagingUserData($sortColumnsArr, $requestData, $defaultColumn) {


        $start   = @$requestData['start'];
        $length  = @$requestData['length'];
        $draw    = @$requestData['draw'];
        $sortBy  = @isset($sortColumnsArr[$requestData['order'][0]['column']]) ? $sortColumnsArr[$requestData['order'][0]['column']] : $defaultColumn;
        $orderBy = @isset($requestData['order'][0]['dir']) ? $requestData['order'][0]['dir'] : 'DESC';
        if (empty($requestData['order'][0]['column']) && $defaultColumn == $sortBy && $draw == 1) {
            $orderBy = 'DESC';
        }
        $searchKey = '';
        if (!empty($requestData['search']['value'])) {
            $searchKey = @$requestData['search']['value'];
        } else {
            if (isset($requestData['columns'][0]['search']['value'])) {
                $searchKey = $requestData['columns'][0]['search']['value'];
            }
        }
        $pagingData                 = array(
            'START' => $start,
            'LENGTH' => $length,
            'COLUMN' => $sortBy,
            'ORDER' => $orderBy,
            'SEARCH' => $searchKey,
        );
        if (isset($requestData['columns'][0]['search']['value']) && !empty($requestData['columns'][0]['search']['value']))
            $pagingData['name']         = $requestData['columns'][0]['search']['value'];
        if (isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value']))
            $pagingData['email']        = $requestData['columns'][1]['search']['value'];
        if (isset($requestData['columns'][2]['search']['value']) && !empty($requestData['columns'][2]['search']['value']))
            $pagingData['type']         = (int) $requestData['columns'][2]['search']['value'];
        if (isset($requestData['columns'][3]['search']['value']) && !empty($requestData['columns'][3]['search']['value']))
            $pagingData['joining_date'] = $requestData['columns'][3]['search']['value'];
        if (isset($requestData['columns'][4]['search']['value']) && !empty($requestData['columns'][4]['search']['value']))
            $pagingData['end_date']     = $requestData['columns'][4]['search']['value'];

        return $pagingData;
    }




    /**
     * @desc use of this function to set title, keywords, description
     * @return void
     */
    function setMetaData($thisContObj, $param) {
        $thisContObj->layout()->setVariables(
                array(
                    'title' => empty($param['title']) ? '' : $param['title'],
                    'keywords' => empty($param['keywords']) ? '' : $param['keywords'],
                    'description' => empty($param['description']) ? '' : $param['description'],
                    'alternate' => empty($param['alternate']) ? '' : $param['alternate'],
                    'canonical' => empty($param['canonical']) ? '' : $param['canonical'],
                    'properties' => empty($param['properties']) ? '' : $param['properties'],
                )
        );
    }


    /*
     * @desc    Use of this function is to get the time format
     * @author  Aditya Kumar
     * @param   $date $format
     * @result  $date
     */

    public function getDateFormat($date, $format = "") {
        $getData = date("Ymd", strtotime($date));
        $getHr   = date("H", strtotime($date));
        $getMin  = date("i", strtotime($date));
        if ($date != '' && $getData != '19700101' && $getData != "-00011130") {
            $dateFormat = !empty($format) ? $format : "d M Y";
            if ($getHr > 0 || $getMin > 0) {
                $dateFormat = !empty($format) ? $format : "d M Y, H:i";
            }
            if (!is_numeric($date)) {
                if ($getHr > 0 || $getMin > 0) {
                    $returnDate = date($dateFormat, strtotime($date)) . " GMT";
                } else {
                    $returnDate = date($dateFormat, strtotime($date));
                }
            } else {
                $returnDate = date($dateFormat, $date) . "GMT";
            }
            return ($returnDate == "1970-01-01" ? "---" : $returnDate);
        } else {
            return "---";
        }
    }

    

}
