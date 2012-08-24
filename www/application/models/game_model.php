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

	public function getDescription($gameid){
	  $this->db->select('description');
	  $this->db->from($this->table_name);
	  $this->db->where('id',$gameid);
	  $query = $this->db->get();
	  if($query->num_rows() != 1){
	      throw new GameDoesNotExistException('Did not find a game for gameid '.$gameid);
	  }
	  return $query->row()->description;
	}

		public function getPhotoURL($gameid){
	  $this->db->select('game_photo_url');
	  $this->db->from($this->table_name);
	  $this->db->where('id',$gameid);
	  $query = $this->db->get();
	  if($query->num_rows() != 1){
	      throw new GameDoesNotExistException('Did not find a game for gameid '.$gameid);
	  }
	  return $query->row()->game_photo_url;
	}


	public function getRegistrationState($gameid){
	  $this->db->select('registration_state');
	  $this->db->from($this->table_name);
	  $this->db->where('id',$gameid);
	  $query = $this->db->get();
	  if($query->num_rows() != 1){
	      throw new GameDoesNotExistException('Did not find a game for gameid '.$gameid);
	  }
	  return $query->row()->registration_state;
	}

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

    public function getEndTime($gameid){
        $this->db->select('end_date');
        $this->db->from($this->table_name);
        $this->db->where('id', $gameid);
        $query = $this->db->get();
        if($query->num_rows() != 1){
            throw new GameDoesNotExistException('Did not find a game for gameid '.$gameid);
        }
        return $query->row()->end_date;
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

    public function getCurrentGame(){
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('game_stateid', 2);
        $query = $this->db->get();
        if($query->num_rows() == 1){
            return $query->row()->id;
        }

        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('game_stateid', 3);
        $this->db->order_by('end_date', 'desc');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row()->id;
        }
        throw new GameDoesNotExistException('The current game could not be determined.');
    }

	public function getGameTimezone($gameid){}

	public function getGameSetting($gameid,$name){}
	public function setGameSetting($gameid, $name, $value){}

	public function getGames(){}

    public function getTagNews($gameid){
        $this->db->select('tagger_user.username as tagger, tagger_data.value as tagger_gravatar_email, tagger_user.email as tagger_email, taggee_user.username as taggee, taggee_data.value as taggee_gravatar_email, taggee_user.email as taggee_email, datetimeclaimed');
        $this->db->from('tag');
        $this->db->join('player as tagger', 'taggerid = tagger.id');
        $this->db->join('users as tagger_user', 'tagger.userid = tagger_user.id');
        $this->db->join('user_data as tagger_data', 'tagger_data.userid = tagger.id AND tagger_data.name="gravatar_email"', 'left');
        $this->db->join('player as taggee', 'taggeeid = taggee.id');
        $this->db->join('users as taggee_user', 'taggee.userid = taggee_user.id');
        $this->db->join('user_data as taggee_data', 'taggee_data.userid = taggee.id AND taggee_data.name="gravatar_email"', 'left');
        $this->db->where('tagger.gameid', $gameid);
        //$this->db->where('taggee.gameid', $gameid); // unnecessary, only a sanity check
        $this->db->where('tag.invalid !=', 1);
        $this->db->order_by('datetimeclaimed', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

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
