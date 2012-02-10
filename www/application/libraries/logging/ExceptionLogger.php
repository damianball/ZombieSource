<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/logging/ILogger.php');
require_once(APPPATH . 'libraries/logging/AbstractLogger.php');

class ExceptionLogger extends AbstractLogger  implements ILogger{

    private $logger_name = 'exceptionlogger';

    public function getLoggerName(){
        return $this->logger_name;
    }

    public function toArray(){
        
    }
}