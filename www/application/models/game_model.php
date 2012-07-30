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
	
	public function getStateID($gameid){
	  $this->db->select('game_stateid');
	  $this->db->from($this->table_name);
	  $this->db->where('id',$gameid);
	  $query = $this->db->get();
	  if($query->num_rows() != 1){
	      throw new GameDoesNotExistException('Did not find a game for gameid '.$gameid);
	  }
	  return $query->row()->game_stateid;
	}

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

	public function getGameIDBySlug($game_slug){
	  $this->db->select('id');
	  $this->db->from($this->table_name);
	  $this->db->where('url_slug',$game_slug);
	  $query = $this->db->get();
	  if($query->num_rows() != 1){
	      throw new GameDoesNotExistException($game_slug . 'is not a valid url slug');
	  }
	  return $query->row()->id;
	}
	
	public function getGameSlugByGameID($gameid){
	  $this->db->select('url_slug');
	  $this->db->from($this->table_name);
	  $this->db->where('id',$gameid);
	  $query = $this->db->get();
	  if($query->num_rows() != 1){
	      throw new GameDoesNotExistException($game_slug . 'is not a valid url slug');
	  }
	  return $query->row()->url_slug;
	}

	public function getGameTimezone($gameid){}
	
	public function getGameSetting($gameid,$name){}
	public function setGameSetting($gameid, $name, $value){}
	
	public function getGames(){}

	public function getGameIDs(){
		$this->db->select('id');
		$this->db->from($this->table_name);
		$query=$this->db->get();
		
		$gameidArray = array();
		foreach($query->result() as $row){
			$gameidArray[] = $row->id;
		}
		return $gameidArray;
	}
}
?>