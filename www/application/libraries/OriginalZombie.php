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
        if($this->canParticipate()){
            if($this->isExposed()){
                return "original zombie";
            } else {
                return "human";
            }
        } else {
            if(parent::isActive()){
                return "starved zombie";
            } else {
                return "inactive";
            }
        }
    }

    public function getKills(){
        if($this->isExposed()){
            return parent::getKills();
        } else {
            return null;
        }
    }

    public function secondsSinceLastFeed(){
        if($this->isExposed()){
            return parent::secondsSinceLastFeed();
        } else {
            return null;
        }
    }

    public function isExposed(){
        if($this->getData('exposed') == 1){
            return true;
        } else {
            return false;
        }
    }
}
