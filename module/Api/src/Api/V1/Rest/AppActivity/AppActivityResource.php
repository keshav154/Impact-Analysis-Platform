<?php

namespace Api\V1\Rest\AppActivity;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\Rest\AbstractResourceListener;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Api\V1\Rest\AppActivity\AppActivityEntity;


class AppActivityResource extends AbstractResourceListener implements ServiceLocatorAwareInterface {

    // database mapper
    protected $mapper;
    // set service manager
    protected $serviceLocator;

    // intialize mapper
    public function __construct($mapper) {
        $this->mapper = $mapper;
    }

    // used of this function for set service locator
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    // used of this function for get service locator
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * @desc Used of this function for login and return login token on success
     *
     * @param  array $data
     * @return ApiProblem|mixed
     */
    public function create($data) {
        $data = json_decode(json_encode($data), true);
        $config = $this->getServiceLocator()->get('config');
 
        if (empty($data))
            return new ApiProblemResponse(new ApiProblem(300, $config['message']['001'], "Error", "Activity Information"));

        $entity = new AppActivityEntity();
        
        $saveActivity = array();
        $activity = array();
        $dataRow = $data['data'];
        $count = count($dataRow);
        if ($count) {
            if ($count < 5) {
                $limit = $count;
            } else {
                $limit = 5;
            }
            $i = 1;
            foreach ($dataRow as $dataRowK => $dataRowV) {
                if (is_array($dataRowV)) {
                    if ($i <= $limit) {
                        $entity->exchangeArray($dataRowV);
                         
                        $activity = $entity->getArrayCopy();
                        $checkUUidForQId = $this->mapper->fetchData(['GL_QUESTION_ID' => $activity['GL_QUESTION_ID'], 'UUID' => $activity['UUID']], 't_unicef_usractivity');
                         
                        if (empty($checkUUidForQId)) {
                            $saveActivity[] = $entity->getArrayCopy();
                        }
                    }
                   // echo '<pre>';print_r($saveActivity); 
                    if ($i == $limit) {
                        if (!empty($saveActivity)) {
                            $this->mapper->insertBulkData('t_unicef_usractivity', $saveActivity);                            
                            $saveActivity = array();
                        }
                        $i = 1;
                        if ($count < 5) {
                            $limit = $count;
                        } else {
                            $limit = 5;
                        }
                    }
                }
                $i++;
                $count--;
            }
            
            return new ApiProblemResponse(new ApiProblem(200, array("message" => $config['success']['200'], "data" => []), 'Success', 'App Activity'));
        }
        
    }

}
