<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Player.php');
require_once(APPPATH . 'libraries/IPlayer.php');

class Zombie extends Player implements IPlayer{
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
    public function getPublicStatus(){
        return "zombie"; 
    }

    // MOVE TO ZOMBIE
    public function secondsSinceLastFeed(){
      return 12000;
    }
    // MOVE TO ZOMBIE
    public function getKills(){
      return "N/A";
      // . ' hours ago'
    }

    public function isActive(){
        if(!$this->isStarved() && parent::isActive()){
            return true;
        }
        return false;
    }

    public function isStarved(){
        $secondsSinceFeed = $this->secondsSinceLastFeed();
        if($secondsSinceFeed > 60*60*48 ){
            return true;
        }
        return false;
    }

}