<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'config/logger.php');
require_once(APPPATH . 'libraries/logging/AnalyticsLogFile.php');
require_once(APPPATH . 'libraries/logging/CliLogFile.php');
require_once(APPPATH . 'libraries/logging/AnalyticsLogger.php');
require_once(APPPATH . 'libraries/logging/ExceptionLogger.php');
require_once(APPPATH . 'libraries/logging/TimeLogger.php');


class LogManager{
    // singleton instance
    private static $instance = null;
    private static $logStores = array();

    private static function openLogStores() {

    }

    private static function closeLogStores() {

    }

    private static function openFiles() {
        $date = gmdate("Y-m-d");
        self::$fileHandle = fopen(LOG_DIR . "/" . $date . "-" . LOG_FILENAME . ".log","a");
    }

    private static function closeFiles() {
        fclose(self::$fileHandle);
        self::$fileHandle = null;
    }

    private static function getNewID() {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        for ($i = 0; $i < 22; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        } 
        // @TODO: Need to make this check if the human code already exists...
        //$this->Player_model->humanCodeExists($string)
        return $string;
    }

    public static function stopLogging() {

    }

    public static function startLogging() {
        self::openLogStores();
    }

    public static function storeLog($ilogger) {

        if(!($ilogger instanceof ILogger)){
            throw new LogManagerException('Store log object does not implement ILogger.');
        }

        $logStore = 'analytics';

        // assumes access to REMOTE_ADDR etc... so a call through apache
        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            $data = array(
                    'id' => uniqid().'-'.self::getNewID(),
                    'timestamp' => gmdate("Y-m-d H:i:s"),
                    'version' => 3,
                    'origin' => 'web',
                    'remote_address' => $_SERVER['REMOTE_ADDR'],
                    'server' => $_SERVER['HTTP_HOST'],
                    'uri' => $_SERVER['REQUEST_URI'],
                    'logger' => $ilogger->getLoggerName(),
                    'payload' => $ilogger->toArray()
            );

        } else if (array_key_exists('SHELL', $_SERVER)) {

            // change log store
            $logStore = 'cli';

            $data = array(
                    'id' => uniqid().'-'.self::getNewID(),
                    'timestamp' => gmdate("Y-m-d H:i:s"),
                    'version' => 3,
                    'origin' => 'cli',
                    'shell' => $_SERVER['SHELL'],
                    'username' => $_SERVER['LOGNAME'],
                    'argc' => $_SERVER['argc'],
                    'argv' => json_encode($_SERVER['argv']),
                    'logger' => $ilogger->getLoggerName(),
                    'payload' => $ilogger->toArray()
            );
        }

        // @TODO: damian - this is a hack to deal with file permissions in /tmp
        if ($logStore == 'analytics' && !array_key_exists('analytics', self::$logStores)) {
            self::$logStores['analytics'] = new AnalyticsLogFile();
        } else if ($logStore == 'cli' && !array_key_exists('cli', self::$logStores)) {
            self::$logStores['cli'] = new CliLogFile();
        }

        // @TODO: damian - check if logstore exists and is a instance of iLogStore
        self::$logStores[$logStore]->write($data);
    }
}
?>