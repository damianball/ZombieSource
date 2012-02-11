<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Player.php');
require_once(APPPATH . 'libraries/IPlayer.php');

class Human extends Player implements IPlayer{
    private $ci = null;

    public function __construct($playerid){
        parent::__construct($playerid);
        $this->ci =& get_instance();
    }

    // @Implements getStatus()
    public function getStatus(){
        return "human"; 
    }

    // @Implements getPublicStatus()
    public function getPublicStatus(){
        return "human"; 
    }

    public function isViewable(){
        if(parent::isActive()) {
            return true;
        } else {
            return false;
        }
    }

    public function canParticipate(){
        if(parent::isActive()){
            return true;
        } else {
            return false;
        }
    }

    // MOVE TO HUMAN
    public function getHumanCode(){
        if(!$this->humanCodeExists()){
            // probably need to lock the player_data table 
            $human_code = $this->newHumanCode();
            $this->saveData('human_code', $human_code);
        }
        return $this->getData("human_code");
    }

    // MOVE TO HUMAN
    private function newHumanCode(){
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            $string = '';
            for ($i = 0; $i < 5; $i++) {
                $string .= $characters[rand(0, strlen($characters) - 1)];
            } 
        }while(false);
        // @TODO: Need to make this check if the human code already exists...
        //$this->Player_model->humanCodeExists($string)
        return $string;
    }

    // MOVE TO HUMAN
    public function humanCodeExists(){
        $isCode = FALSE;
        if($this->getData("human_code")){
            $isCode = TRUE;
        }
        return $isCode;
    }
}