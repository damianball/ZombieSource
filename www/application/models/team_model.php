<?php
class Team_model extends CI_Model{
    private $table_name = 'team';
    private $teamData = null;
    private $teamFields = array(
        'id' => 'uuid',
        'gameid' => 'uuid',
        'name' => 'string',
        'datecreated' => 'datetime',
        'private' => 'int',
        'regcode' => 'string',
        'gravatar_email' => 'string',
        'description' => 'string'
    );
    private $teamEditableFields = array(
        'private' => 'int',
        'regcode' => 'string',
        'gravatar_email' => 'string',
        'description' => 'string'
    );

    function __construct(){
        parent::__construct();
    }

    //hack from chandler, @damian will want to rewrite
    public function getAllTeamIDsByGameID($gameid){
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('gameid',$gameid);
        $query = $this->db->get();
        $teamidArray = array();
        foreach($query->result() as $row){
            $teamidArray[] = $row->id;
        }
        return $teamidArray;
    }

    public function getGameIDByTeamID($teamid){
        $this->db->select('gameid');
        $this->db->from($this->table_name);
        $this->db->where('id', $teamid);
        $query = $this->db->get();
        return $query->row()->gameid;
    }

    public function createTeam($name, $gameid){
        if($name == null || $name == ''){
            throw new UnexpectedValueException('game name cannot be null');
        }
        if($gameid == null || $gameid == ''){
            throw new UnexpectedValueException('gameid cannot be null');
        }

        //date created
        $datecreated = gmdate("Y-m-d H:i:s", time());

        //get new UUID
        $query = $this->db->query('SELECT UUID() as "uuid"');
        $uuid = $query->row()->{'uuid'};
        $data = array(
            'id' => $uuid,
            'gameid' => $gameid,
            'name' => $name,
            'datecreated' => $datecreated,
            'private' => null,
            'regcode' => null,
            'gravatar_email' => null,
            'description' => null
        );

        // @TODO: check that game/name pair is unique
        // @TODO: how do we know the query succeeded?
        $this->db->insert($this->table_name,$data);

        return $uuid;
    }

    private function populateTeamData($teamid){
        if(!$teamid){
            throw new UnexpectedValueException('teamid cannot be null');
        }

        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('id',$teamid);
        $query = $this->db->get();
        if ($query->num_rows() == 1){
            $this->teamData = $query->row_array();
        } else {
            throw new DatastoreException('Too many (or few) row results - '.$query->num_rows().' - for teamid '.$teamid.' query: '.$this->db->last_query());
        }
    }

    public function getTeamData($teamid, $name){
        if(!array_key_exists($name,$this->teamFields)){
            throw new UnexpectedValueException($name.' is not a valid field');
        }
        $this->populateTeamData($teamid);
        return $this->teamData[$name];
    }

    public function setTeamData($teamid, $name, $value){
        if(!$teamid){
            throw new UnexpectedValueException('teamid cannot be null');
        }
        if(!array_key_exists($name,$this->teamEditableFields)){
            throw new UnexpectedValueException($name.' is not a valid field');
        }
        $data = array(
            $name => $value
        );
        $this->db->where('id',$teamid);
        $this->db->update($this->table_name,$data);

        // clear the data, because it's stale
        $this->teamData = null;
    }
}
?>
