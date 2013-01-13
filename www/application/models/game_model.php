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

  public function getUTCoffset($gameid){
        $query = $this->db->query(
            'select offset_from_utc from timezone
            join  game
            where game.timezoneid = timezone.id
            and   game.id = \'' . $gameid . '\''
        );
    if($query->num_rows() != 1){
        throw new GameDoesNotExistException('Did not find a game for gameid '.$gameid);
    }
    return $query->row()->offset_from_utc;
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
        $this->db->select('id, end_date');
        $this->db->from($this->table_name);
        $this->db->where('game_stateid', 2);
        $this->db->order_by('end_date', "desc");
        $this->db->limit(1);
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

  public function emailListFall2012(){
      $query = $this->db->query("select email from users
      join player
      WHERE
      (player.datecreated > '2012-06-01 00:00:00' AND player.userid = users.id)
      OR users.created > '2012-06-01 00:00:00'
      group by users.id
      ");
      $list = array();
      foreach($query->result() as $row){
        $list[] = $row->{'email'};
      }
      return $list;
  }

}
?>
