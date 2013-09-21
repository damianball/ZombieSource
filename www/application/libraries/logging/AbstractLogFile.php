<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'config/logger.php');
require_once(APPPATH . 'libraries/logging/ILogStore.php');

abstract class AbstractLogFile implements ILogStore{
	protected static $date;
    protected static $fileHandle;
    protected static $logFilename;

    public function AbstractLogFile(){
        self::$date = gmdate("Y-m-d");
    	self::openFile();
    }

    protected static function openFile(){
        $date = gmdate("Y-m-d");
        self::$fileHandle = fopen(LOG_DIR . "/" . $date . "-" . self::$logFilename . ".log","a");
    }

    protected static function closeFile(){
        fclose(self::$fileHandle);
        self::$fileHandle = null;
    }

    public function write($data) {
    	if(!self::$fileHandle) self::openFile();

    	fwrite(self::$fileHandle,json_encode($data)."\r\n");
    }
}