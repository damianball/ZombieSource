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

    public function getActiveGames(){
      $game_list = array();
      foreach ($this->ci->Game_model->getGameIDs() as $game_id){
        if($this->ci->Game_model->getStateID($game_id) == 2) { //TODO replace 2 with check for state with name "active"
          $game_list[] = $this->getGameByGameID($game_id);
        }
      }
      return $game_list;
    }
}