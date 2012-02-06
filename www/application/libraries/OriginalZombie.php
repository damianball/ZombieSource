<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Zombie.php');
require_once(APPPATH . 'libraries/IPlayer.php');

class OriginalZombie extends Zombie implements IPlayer{
    private $ci = null;

    public function __construct($playerid){
        parent::__construct($playerid);
        $this->ci =& get_instance();
    }

    // @Implements getStatus()
    public function getStatus(){
        return "zombie"; 
    }

    // @Implements getPublicStatus()
    // this depends on what they are!!! :-)
    public function getPublicStatus(){
        return "human"; 
    }

    public function isPublicActive(){
        return false;
    }
}