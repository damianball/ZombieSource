<?php
class Player_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function getPlayerID($userid, $gameid){
		$query = $this->db->query('SELECT id FROM player WHERE userid = '.$this->db->escape($userid).' AND gameid = '.$this->db->escape($gameid));
		$result = $query->row();
		$id = "";
		if(isset($result->{'id'})){
			$id = $result->{'id'};
		}
		
		return $id;
	}
	public function getPlayerData($playerid, $name){
		$query = $this->db->query('SELECT value FROM player_data WHERE playerid = \''.$this->db->escape($playerid).'\' AND name = \''.$this->db->escape($name).'\' ORDER BY timestamp DESC LIMIT 1');
		$result = $query->row();
		$value = "";
		if(isset($result->{'value'})){
			$value = $result->{'value'};
		}
		
		return $value;
	}
	public function setPlayerData($playerid, $name, $value){
		$added = false;

		//date created
		$datecreated = gmdate("Y-m-d H:i:s", time());

		//insert new player data
		$data = array(
			'playerid' => $playerid,
			'name' => $name,
			'timestamp' => $datecreated,
			'value' => $value
		);
		$this->db->insert('player_data',$data);
		$added = true;
		
		return $added;
	}
	public function createPlayerInGame($userid, $gameid){
		$added = false;
			
		//date created
		$datecreated = gmdate("Y-m-d H:i:s", time());
			
		//get new UUID
		$query = $this->db->query('SELECT UUID() as "uuid"');
		$uuid = $query->row()->{'uuid'};
			
		//insert new player
		$data = array(
			'id' => $uuid,
			'userid' => $userid,
			'gameid' => $gameid,
			'datecreated' => $datecreated,
			'original_zombie' => NULL
		);
		$this->db->insert('player',$data);
		$added = true;
				
		return $added;
	}
}
?>