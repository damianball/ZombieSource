<?php
class Game_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	// implement later
	//public function createGame($name, $timezoneid){}
	
	public function setGameTimezone($gameid, $timezoneid){}
	public function setGameName($gameid, $name){}
	public function setGameState($gameid, $stateid){}
	
	public function getGameState($gameid){}
	public function getGameName($gameid){}
	public function getGameTimezone($gameid){}
	
	public function getGameSetting($gameid,$name){}
	public function setGameSetting($gameid, $name, $value){}
	
	public function getGames(){}
}
?>