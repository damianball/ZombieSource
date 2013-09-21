<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/logging/ILogger.php');
require_once(APPPATH . 'libraries/logging/AbstractLogger.php');

class TimeLogger extends AbstractLogger  implements ILogger{
    private $start_time;
    private $end_time;
    private $logger_name = 'timelogger';
    private $name;
    private $version = 1;

    public function __construct(){
        $this->start_time = null;
        $this->end_time = null;
        $this->name = null;
    }

    public static function getNewTimer($name){
        $instance = new self();
        $instance->name = $name;
        return $instance;
    }

    public function getLoggerName(){
        return $this->logger_name;
    }

    public function startTimer(){
        $this->start_time = microtime(TRUE);
    }

    public function stopTimer(){
        $this->end_time = microtime(TRUE);
    }

    public function toArray(){
        $data = array(
            'version' => $this->version,
            'payload' => array(
                'timer_name' => $this->name,
                'duration' => $this->end_time - $this->start_time
            )
        );

        return $data;
    }
}