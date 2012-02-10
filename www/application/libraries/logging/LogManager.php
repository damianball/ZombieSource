<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'config/logger.php');
require_once(APPPATH . 'libraries/logging/AnalyticsLogger.php');
require_once(APPPATH . 'libraries/logging/ExceptionLogger.php');
require_once(APPPATH . 'libraries/logging/TimeLogger.php');


class LogManager{
    // singleton instance
    private static $instance = null;
    private static $fileHandle = null;

    function __construct()
    {
        //$this->ci =& get_instance();
    }

    private static function openFiles(){
        $date = gmdate("Y-m-d");
        self::$fileHandle = fopen(LOG_DIR . "/" . $date . "-" . LOG_FILENAME . ".log","a");
    }

    private static function closeFiles(){
        fclose(self::$fileHandle);
        self::$fileHandle = null;
    }

    private static function getNewID(){
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        for ($i = 0; $i < 22; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        } 
        // @TODO: Need to make this check if the human code already exists...
        //$this->Player_model->humanCodeExists($string)
        return $string;
    }

    public static function stopLogging(){
        self::closeFiles();
    }

    public static function startLogging(){
        self::openFiles();
    }

    public static function storeLog($ilogger){
        if(!self::$fileHandle) self::openFiles();

        if(!($ilogger instanceof ILogger)){
            throw new LogManagerException('Store log object does not implement ILogger.');
        }

        $data = array(
                'id' => uniqid().'-'.self::getNewID(),
                'timestamp' => gmdate("Y-m-d H:i:s"),
                'version' => 1,
                'server' => $_SERVER['HTTP_HOST'],
                'uri' => $_SERVER['REQUEST_URI'],
                'logger' => $ilogger->getLoggerName(),
                'payload' => $ilogger->toArray()
        );

        fwrite(self::$fileHandle,json_encode($data)."\r\n");
    }
}
?>