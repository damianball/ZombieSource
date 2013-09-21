<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/logging/ILogger.php');
require_once(APPPATH . 'libraries/logging/AbstractLogger.php');

class AnalyticsLogger extends AbstractLogger implements ILogger{

    private $logger_name = 'analyticslogger';
    private $action;
    private $value;
    private $payload;
    private $version = 1;

    public function __construct(){
        $this->action = null;
        $this->value = null;
        $this->payload = array();
    }

    public static function getNewAnalyticsLogger($action, $value){
        $instance = new self();
        $instance->action = $action;
        $instance->value = $value;

        return $instance;
    }

    public function getLoggerName(){
        return $this->logger_name;
    }

    public function addArrayToPayload($array){
        foreach($array as $key => $value){
            $this->payload[$key] = $value;
        }
    }

    public function addToPayload($key, $value){
        $this->payload[$key] = $value;
    }

    public function toArray(){
        $data = array(
            'version' => $this->version,
            'action' => $this->action,
            'value' => $this->value,
            'payload' => $this->payload
        );

        return $data;
    }
}