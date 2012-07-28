<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Game.php');

// Factory class for instanciating a team

class GameCreator{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function getGameByGameID($gameid){
      return new Game($gameid);
    }
}