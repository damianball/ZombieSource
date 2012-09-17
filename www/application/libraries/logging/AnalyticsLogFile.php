<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'config/logger.php');
require_once(APPPATH . 'libraries/logging/ILogStore.php');

class AnalyticsLogFile implements ILogStore{
	protected static $date = FALSE;
    protected static $fileHandle;
    protected static $logFilename = 'analytics';

    protected static function openFile(){
        if (self::$date == FALSE) {
            self::$date = gmdate("Y-m-d");
        }

        self::$fileHandle = fopen(LOG_DIR . "/" . self::$logFilename . "-" . self::$date  . ".log","a");
    }

    protected static function closeFile(){
        fclose(self::$fileHandle);
        self::$fileHandle = null;
    }

    public function write($data) {
    	if(!self::$fileHandle) self::openFile();

    	fwrite(self::$fileHandle, json_encode($data) . "\r\n");
    }
}