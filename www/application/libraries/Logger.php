<?php

class Logger
{
    // singleton instance
    private static $instance = null;
    private static $fileHandle = null;

    function __construct()
    {
        //$this->ci =& get_instance();
    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new self();
            //LOGFILE_DIR.
            self::$fileHandle = fopen(LOGFILE_NAME,"a");
        }

        return self::$instance;
    }

    private function openFile(){
        self::$fileHandle = fopen(LOGFILE_NAME,"a");
    }

    public static function log($string){
        if(!self::$fileHandle) self::openFile();

        fwrite(self::$fileHandle,$string."\r\n");
    }
}