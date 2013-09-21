<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

interface ILogStore {
    public function write($data);
}