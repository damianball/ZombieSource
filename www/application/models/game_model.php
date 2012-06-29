<?php
class Game_model extends CI_Model{
	private $table_name = 'game';

	function __construct(){
		parent::__construct();
	}
	
	// implement later
	public function createGame($name, $timezoneid){
	}
	
	public function setGameTimezone($gameid, $timezoneid){}
	public function setGameName($gameid, $name){}
	public function setGameState($gameid, $stateid){}
	public function getGameState($gameid){}
	public function getGameName($gameid){
	  $this->db->select('name');
	  $this->db->from($this->table_name);
	  $this->db->where('id',$gameid);
	  $query = $this->db->get();
	  if($query->num_rows() != 1){
	      throw new GameDoesNotExistException('Did not find a game for gameid '.$gameid);
	  }
	  return $query->row()->name;
	}
	public function getGameTimezone($gameid){}
	
	public function getGameSetting($gameid,$name){}
	public function setGameSetting($gameid, $name, $value){}
	
	public function getGames(){}
}
?>