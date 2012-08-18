<?php
class Player_team_model extends CI_Model{
    private $table_name = 'player_to_team';
    private $teamFields = array(
        'playerid' => 'uuid',
        'teamid' => 'uuid',
        'datecreated' => 'datetime',
        'dateremoved' => 'datetime'
    );

    function __construct(){
        parent::__construct();
    }

    public function addPlayerToTeam($teamid, $playerid){
        if(!$teamid){
            throw new UnexpectedValueException('teamid name cannot be null or empty');
        }
        if(!$playerid){
            throw new UnexpectedValueException('playerid cannot be null or empty');
        }

        //date created
        $datecreated = gmdate("Y-m-d H:i:s", time());

        //get new UUID
        $query = $this->db->query('SELECT UUID() as "uuid"');
        $uuid = $query->row()->{'uuid'};

        $data = array(
            'id' => $uuid,
            'teamid' => $teamid,
            'playerid' => $playerid,
            'datecreated' => $datecreated
        );

        // @TODO: check that game/name pair is unique
        $this->db->insert($this->table_name,$data);
    }

    public function removePlayerFromTeam($teamid, $playerid){
        if(!$teamid) throw new UnexpectedValueException('teamid cannot be null');
        if(!$playerid) throw new UnexpectedValueException('playerid cannot be null');
        $currentTeamID = $this->getTeamIDByPlayerID($playerid);
        if($currentTeamID != $teamid){
            throw new UnexpectedValueException('teamid '.$teamid.' supplied does not match '.$currentTeamID);
        }

        //date removed
        $dateremoved = gmdate("Y-m-d H:i:s", time());

        $data = array(
            'dateremoved' => $dateremoved
        );

        $this->db->where('playerid',$playerid);
        $this->db->where('teamid',$teamid);
        $this->db->where('dateremoved IS NULL');
        $this->db->update($this->table_name,$data);
    }

    // returns an array of playerids
    public function getListOfPlayerIDByTeamID($teamid){
        if(!$teamid) throw new UnexpectedValueException('teamid cannot be null');

        $this->db->select('playerid');
        $this->db->from($this->table_name);
        $this->db->where('teamid',$teamid);
        $this->db->where('dateremoved IS NULL');
        $query = $this->db->get();
        $playeridArray = array();
        foreach($query->result() as $row){
            $playeridArray[] = $row->playerid;
        }
        return $playeridArray;
    }

    public function getListOfFormerPlayerIDByTeamID($teamid){
        if(!$teamid) throw new UnexpectedValueException('teamid cannot be null');
        $this->db->select('playerid');
        $this->db->select_max('datecreated', 'max_date');
        $this->db->from($this->table_name);
        $this->db->where('teamid', $teamid);
        $this->db->where('dateremoved is not NULL', NULL);
        $this->db->group_by('playerid');

        $query = $this->db->get();
        $playeridArray = array();
        foreach($query->result() as $row){
            $playeridArray[] = $row->playerid;
        }
        return $playeridArray;
    }

    public function getCountOfPlayersByTeamID($teamid){
        if(!$teamid) throw new UnexpectedValueException('teamid cannot be null');

        $this->db->select('COUNT(playerid) as count');
        $this->db->from($this->table_name);
        $this->db->where('teamid',$teamid);
        $this->db->where('dateremoved IS NULL');
        $query = $this->db->get();
        if($query->num_rows() != 1){
            throw new DatastoreException('Unexpected number of rows for count of players on a team: '+$teamid);
        }
        return $query->row()->count;
    }

    public function getTeamIDByPlayerID($playerid){
        if(!$playerid) throw new UnexpectedValueException('playerid cannot be null');

        $this->db->select('teamid');
        $this->db->from($this->table_name);
        $this->db->where('playerid',$playerid);
        $this->db->where('dateremoved IS NULL');
        $this->db->order_by('datecreated','desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() != 1){
            throw new PlayerNotMemberOfAnyTeamException('Too many (or few) results for playerid '.$playerid);
        }
        return $query->row()->teamid;
    }
}
?>
