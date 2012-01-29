<?php
class Player_model extends CI_Model{
    private $table_name = 'player';

	function __construct(){
		parent::__construct();
	}
	
	public function getPlayerID($userid, $gameid){
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('userid',$userid);
        $this->db->where('gameid',$gameid);
        $query = $this->db->get();
        if($query->num_rows() != 1){
            throw new UnexpectedValueException('Did not find a playerid for userid '.$userid.' and gameid'.$gameid);
        }
		
		return $query->row()->id;
	}

    public function getActivePlayers(){
        $query = $this->db->query('SELECT id FROM player');
        $result = $query->result_array();
        return $result;
    }

	public function getPlayerData($playerid, $name){
		$query = $this->db->query('SELECT value FROM player_data WHERE playerid = '.$this->db->escape($playerid).' AND name = '.$this->db->escape($name).' ORDER BY timestamp DESC LIMIT 1');
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
	
	public function getNumberOfPlayersInGame($gameid){
		$query = $this->db->query('SELECT COUNT(id) as count FROM player WHERE gameid = '.$this->db->escape($gameid));
		$result = $query->row();
		$count = "";
		if(isset($result->{'count'})){
			$count = $result->{'count'};
		}
		
		return $count;
	}
	
	// both name and value must match exactly(upper/lower)
	public function getNumberOfPlayersInGameByNVP($gameid,$name,$value){
		$query = $this->db->query('SELECT COUNT(*) as count FROM player_data 
									LEFT JOIN (player) ON (player.id = player_data.playerid) 
									WHERE player_data.name = '.$this->db->escape($name).' 
									AND player_data.value = '.$this->db->escape($value).' 
									AND player.gameid = '.$this->db->escape($gameid));
		$result = $query->row();
		$count = "";
		if(isset($result->{'count'})){
			$count = $result->{'count'};
		}
		
		return $count;
	}
        
        // @TODO: write isActiveHuman
        public function isActiveHuman($playerid){
            
        }
        
        // @TODO: write isActiveZombie
        public function isActiveZombie($playerid){
            return true;
        }
}
?>