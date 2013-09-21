<?php

class ExceptionHook 
{
    public function SetExceptionHandler()
    {
        set_exception_handler(array(&$this, 'HandleExceptions'));
    }
     
    public function HandleExceptions($exception)
    {
    
        $msg ='Exception of type \''.get_class($exception).'\' occurred with Message: '.$exception->getMessage().' in File '.$exception->getFile().' at Line '.$exception->getLine();
        
        $msg .="\r\n Backtrace \r\n";
        $msg .=$exception->getTraceAsString();
        
        error($msg);     		
        mail('brains@zombiesource.org', 'An Exception Occurred', $msg, 'From: auto-notification@zombiesource.org');
    }
}