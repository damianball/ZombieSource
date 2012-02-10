<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

interface ILogger {
    public function toArray();
    public function getLoggerName();
}