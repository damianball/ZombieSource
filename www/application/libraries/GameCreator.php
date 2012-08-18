<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Game.php');

// Factory class for instanciating a game

class GameCreator{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();
        $this->ci->load->model('Game_model','',true);
    }

    public function getGameByGameID($gameid){
      return new Game($gameid);
    }
}