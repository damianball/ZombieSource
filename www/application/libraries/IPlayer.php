<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

interface IPlayer{
    // use the reflection class to test
    // for example
    // $class = new ReflectionClass('Human');
    // if ($class->implementsInterface('IPlayer'))
 
	public function getStatus();
	public function getPublicStatus();
	public function canParticipate();

}