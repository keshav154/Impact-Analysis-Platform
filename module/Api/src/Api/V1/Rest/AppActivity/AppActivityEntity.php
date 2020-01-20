<?php
namespace Api\V1\Rest\AppActivity;

class AppActivityEntity {

   // public $UUID;
    //public $USR_NAME;
    //public $USR_AGE;
    //public $USR_AVATAR;
    public $USER_OID;
    //public $USR_GENDER;
   // public $USR_LANGUAGE;
    public $GL_MODULE_ID;
    public $GL_MODULE_NAME;
    public $GL_LEVEL_ID;
    public $GL_LEVEL_NAME;
    public $GL_LEVEL_KNOWLEDGE_DOMAIN;
    public $GL_LEVEL_COGNITIVE_DOMAIN;
    public $GL_LEVEL_TYPE;
    public $GL_LEVEL_INTERACTIVITY;
    public $GL_QUESTION_ID;
    public $GL_QUESTION_COGNITIVE;
    public $GL_QUESTION_ACTION_VERB;
    public $LL_QUESTION_TYPE;
    public $TR_USER_SCORE;
    public $HOST_IP;
    public $DEVICE_BROWSER_VERSION;
    public $DEVICE_MODEL;
    public $DEVICE_KERNEL_VERSION;
    public $DEVICE_SERIAL_NUMBER;
    public $DEVICE_PLATFORM;
    public $ATTEMPTED_ON;

    public function getArrayCopy() {
        return array(
            //'UUID' => $this->UUID,
            //'USR_NAME' => $this->USR_NAME,
           // 'USR_AGE' => $this->USR_AGE,
            //'USR_AVATAR' => $this->USR_AVATAR,
            'USER_OID' => $this->USER_OID,
            //'USR_GENDER' => $this->USR_GENDER,
            //'USR_LANGUAGE' => $this->USR_LANGUAGE,
            'GL_MODULE_ID' => $this->GL_MODULE_ID,
            'GL_MODULE_NAME' => $this->GL_MODULE_NAME,
            'GL_LEVEL_ID' => $this->GL_LEVEL_ID,
            'GL_LEVEL_NAME' => $this->GL_LEVEL_NAME,
            'GL_LEVEL_KNOWLEDGE_DOMAIN' => $this->GL_LEVEL_KNOWLEDGE_DOMAIN,
            'GL_LEVEL_COGNITIVE_DOMAIN' => $this->GL_LEVEL_COGNITIVE_DOMAIN,
            'GL_LEVEL_TYPE' => $this->GL_LEVEL_TYPE,
            'GL_LEVEL_INTERACTIVITY' => $this->GL_LEVEL_INTERACTIVITY,
            'GL_QUESTION_ID' => $this->GL_QUESTION_ID,
            'GL_QUESTION_COGNITIVE' => $this->GL_QUESTION_COGNITIVE,
            'GL_QUESTION_ACTION_VERB' => $this->GL_QUESTION_ACTION_VERB,
            'LL_QUESTION_TYPE' => $this->LL_QUESTION_TYPE,
            'TR_USER_SCORE' => $this->TR_USER_SCORE,
            'HOST_IP' => $this->HOST_IP,
            'DEVICE_BROWSER_VERSION' => $this->DEVICE_BROWSER_VERSION,
            'DEVICE_MODEL' => $this->DEVICE_MODEL,
            'DEVICE_KERNEL_VERSION' => $this->DEVICE_KERNEL_VERSION,
            'DEVICE_SERIAL_NUMBER' => $this->DEVICE_SERIAL_NUMBER,
            'DEVICE_PLATFORM' => $this->DEVICE_PLATFORM,
            'ATTEMPTED_ON' => $this->ATTEMPTED_ON
        );
    }
   

    public function exchangeArray(array $array) {
        //$this->UUID = isset($array['UUID']) ? $array['UUID'] : null;
       // $this->USR_NAME = isset($array['USR_NAME']) ? $array['USR_NAME'] : null;
       // $this->USR_AGE = isset($array['USR_AGE']) ? $array['USR_AGE'] : null;
        //$this->USR_AVATAR = isset($array['USR_AVATAR']) ? $array['USR_AVATAR'] : null;
        $this->USER_OID = isset($array['USER_OID']) ? $array['USER_OID'] : null;
        //$this->USR_GENDER = isset($array['USR_GENDER']) ? $array['USR_GENDER'] : null;
        $this->USR_LANGUAGE = isset($array['USR_LANGUAGE']) ? $array['USR_LANGUAGE'] : null;
        $this->GL_MODULE_ID = isset($array['GL_MODULE_ID']) ? $array['GL_MODULE_ID'] : null;
        $this->GL_MODULE_NAME = isset($array['GL_MODULE_NAME']) ? $array['GL_MODULE_NAME'] : null;
        $this->GL_LEVEL_ID = isset($array['GL_LEVEL_ID']) ? $array['GL_LEVEL_ID'] : null;
        $this->GL_LEVEL_NAME = isset($array['GL_LEVEL_NAME']) ? $array['GL_LEVEL_NAME'] : null;
        $this->GL_LEVEL_KNOWLEDGE_DOMAIN = isset($array['GL_LEVEL_KNOWLEDGE_DOMAIN']) ? $array['GL_LEVEL_KNOWLEDGE_DOMAIN'] : null;
        $this->GL_LEVEL_COGNITIVE_DOMAIN = isset($array['GL_LEVEL_COGNITIVE_DOMAIN']) ? $array['GL_LEVEL_COGNITIVE_DOMAIN'] : null;
        $this->GL_LEVEL_TYPE = isset($array['GL_LEVEL_TYPE']) ? $array['GL_LEVEL_TYPE'] : null;
        $this->GL_LEVEL_INTERACTIVITY = isset($array['GL_LEVEL_INTERACTIVITY']) ? $array['GL_LEVEL_INTERACTIVITY'] : null;
        $this->GL_QUESTION_ID = isset($array['GL_QUESTION_ID']) ? $array['GL_QUESTION_ID'] : null;
        $this->GL_QUESTION_COGNITIVE = isset($array['GL_QUESTION_COGNITIVE']) ? $array['GL_QUESTION_COGNITIVE'] : null;
        $this->GL_QUESTION_ACTION_VERB = isset($array['GL_QUESTION_ACTION_VERB']) ? $array['GL_QUESTION_ACTION_VERB'] : null;
        $this->LL_QUESTION_TYPE = isset($array['LL_QUESTION_TYPE']) ? $array['LL_QUESTION_TYPE'] :null;
        $this->TR_USER_SCORE = isset($array['TR_USER_SCORE']) ? $array['TR_USER_SCORE'] : null;
        $this->HOST_IP = isset($array['HOST_IP']) ? $array['HOST_IP'] : null;
        $this->DEVICE_BROWSER_VERSION = isset($array['DEVICE_BROWSER_VERSION']) ? $array['DEVICE_BROWSER_VERSION'] : null;
        $this->DEVICE_MODEL = isset($array['DEVICE_MODEL']) ? $array['DEVICE_MODEL'] : null;
        $this->DEVICE_KERNEL_VERSION = isset($array['DEVICE_KERNEL_VERSION']) ? $array['DEVICE_KERNEL_VERSION'] :null;
        $this->DEVICE_SERIAL_NUMBER = isset($array['DEVICE_SERIAL_NUMBER']) ? $array['DEVICE_SERIAL_NUMBER'] :null;
        $this->DEVICE_PLATFORM = isset($array['DEVICE_PLATFORM']) ? $array['DEVICE_PLATFORM'] :null;
        $this->ATTEMPTED_ON = isset($array['ATTEMPTED_ON']) ? $array['ATTEMPTED_ON'] :null;        
    }
}