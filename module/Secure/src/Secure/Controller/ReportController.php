<?php

namespace Secure\Controller;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Checkondispatch\Custom\AbstractApplicationController;
use Secure\Model\ReportTable;

class ReportController extends AbstractApplicationController {

    public function __construct() {
        
    }

    /**
     * @desc used for List of Activity data
     * @param viewModel (setting view type criteria)
     */
    function reportAction() {
        $request                = $this->getRequest();
        $this->CommonClass()->setCustomLayout($this, 'reportLayout');
        $dbadapter              = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $table                  = new ReportTable($dbadapter);
        $getActivityTableColumn = $table->getActivityTableColumn();
        if ($request->isXmlHttpRequest()) {
            $requestData    = $request->getQuery()->toArray();
            $sortColumnsArr = array(0 => 'ACTIVITY_ID', 1 => 'USR_NAME', 2 => 'GL_MODULE_ID', 3 => 'GL_MODULE_NAME');
            $pagingData     = $this->CommonClass()->setAndGetPagingUserData($sortColumnsArr, $requestData, 'ACTIVITY_ID');
            $activityData   = $table->getUserActivity($pagingData);
            $totalData      = $table->getUserActivityCount($pagingData);


            $retArrData     = $this->getJsonArray($activityData);
            $retJsonArrData = array(
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "result" => $retArrData,
            );
            $jsonModel      = new JsonModel();
            $jsonModel->setVariables($retJsonArrData);
            return $jsonModel;
        }
        $viewModel = $this->acceptableviewmodelselector($this->acceptCriteria);
        $viewModel->setVariables(['tableColumn' => array_column($getActivityTableColumn, 'Field')]);
        return $viewModel;
    }

    /**
     * @desc used to prepare list of All user data according to data table
     * @author Umar Farooque Khan
     */
    public function getJsonArray($activityData) {
        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $ConfigHelper      = $viewHelperManager->get('Config');

        $retArrData = array();
        if (count($activityData)) {
            $i = 0;
            foreach ($activityData as $ud) {
                $retArrData[$i][] = ucfirst($ud['FIRST_NAME']);
                $retArrData[$i][] = $ud['USER_AGE'];                
                $retArrData[$i][] = ($ud['GENDER_ID']=='M')?'Boy':'Girl';                
                $retArrData[$i][] = $ud['GL_MODULE_ID'];
                $retArrData[$i][] = $ud['GL_MODULE_NAME'];
                $retArrData[$i][] = $ud['GL_LEVEL_NAME'];
                $retArrData[$i][] = $ud['GL_LEVEL_KNOWLEDGE_DOMAIN'];
                $retArrData[$i][] = $ud['GL_LEVEL_COGNITIVE_DOMAIN'];
                $retArrData[$i][] = $ud['GL_LEVEL_TYPE'];
                $retArrData[$i][] = $ud['GL_LEVEL_INTERACTIVITY'];
                $retArrData[$i][] = $ud['GL_QUESTION_ID'];
                $retArrData[$i][] = $ud['GL_QUESTION_COGNITIVE'];
                $retArrData[$i][] = $ud['GL_QUESTION_ACTION_VERB'];
                $retArrData[$i][] = $ud['LL_QUESTION_TYPE'];
                $retArrData[$i][] = $ud['TR_USER_SCORE'];
                $retArrData[$i][] = $ud['HOST_IP'];
                $retArrData[$i][] = $ud['DEVICE_BROWSER_VERSION'];
                $retArrData[$i][] = $ud['DEVICE_MODEL'];
                $retArrData[$i][] = $ud['DEVICE_KERNEL_VERSION'];
                $retArrData[$i][] = $ud['DEVICE_SERIAL_NUMBER'];
                $retArrData[$i][] = $ud['DEVICE_PLATFORM'];
                $retArrData[$i][] = $this->CommonClass()->getDateFormat($ud['ATTEMPTED_ON']);
                $i++;
            }
        }
        return $retArrData;
    }

    /**
     * @desc used for List of Activity data
     * @param viewModel (setting view type criteria)
     */
    function indexAction() {
        $request    = $this->getRequest();
        $this->CommonClass()->setCustomLayout($this, 'reportLayout');
        $dbadapter  = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $table      = new ReportTable($dbadapter);
        //Score by Module
        $allModules = $table->getAllModule();
        $moduleArr  = array();
        $socreArr   = array();
        if (!empty($allModules)) {
            foreach ($allModules as $key => $modules) {
                $moduleArr[$key] = $modules['GL_MODULE_NAME'];
                $socreArr[$key]  = $modules['SCORE'];
            }
        }
        // Module Attempt
        $moduleAttemptArr   = $table->getAllModuleAttemt();
        
        $moduleAttemptArray = array();
        if (!empty($moduleAttemptArr)) {
            foreach ($moduleAttemptArr as $key => $modules) {
                $moduleAttemptArray[$key]['value'] = $modules['COUNT'];
                $moduleAttemptArray[$key]['name']  = $modules['COUNT'] . ' ' . $modules['GL_MODULE_NAME'];
            }
        }
        $totalModuleAttempt = count($moduleAttemptArray);
        $moduleAttempt      = json_encode($moduleAttemptArray);


        $viewModel = $this->acceptableviewmodelselector($this->acceptCriteria);
        $viewModel->setVariables(array(
            'allModules' => json_encode($moduleArr),
            'moduelsScore' => json_encode($socreArr),
            'moduleAttempt' => $moduleAttempt,
            'totalModuleAttempt' => $totalModuleAttempt,
        ));
        return $viewModel;
    }

}
