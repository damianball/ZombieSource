<?php
class Tag_model extends CI_Model{
    private $table_name = 'tag';
    private $tagFields = array(
        'taggerid' => 'uuid',
        'taggeeid' => 'uuid',
        'datetimeclaimed' => 'datetime',
        'datetimecreated' => 'datetime',
        'longitude' => 'float',
        'latitude' => 'float',
        'invalid' => 'int'
    );

    function __construct(){
        parent::__construct();
    }

    public function storeNewTag($humanPlayerID, $zombiePlayerID, $dateclaimed = null, $long = null, $lat = null){
        if(!$humanPlayerID){
            throw new UnexpectedValueException('human player id required');
        }
        if(!$zombiePlayerID){
            throw new UnexpectedValueException('zombie player id required');
        }

        //date created
        $datecreated = gmdate("Y-m-d H:i:s", time());

        if(!$dateclaimed){
            $dateclaimed = $datecreated;
        }

        //get new UUID
        $query = $this->db->query('SELECT UUID() as "uuid"');
        $uuid = $query->row()->{'uuid'};
        $data = array(
            'id' => $uuid,
            'taggerid' => $zombiePlayerID,
            'taggeeid' => $humanPlayerID,
            'datetimecreated' => $datecreated,
            'datetimeclaimed' => $dateclaimed,
            'longitude' => $long,
            'latitude' => $lat
        );

        // @TODO: check that game/name pair is unique
        // @TODO: how do we know the query succeeded?
        $this->db->insert($this->table_name,$data);

        return $uuid;

        //throw new DatastoreException('Could not store tag.');
    }

    // return true if the player has been tagged
    public function validTagExistsForPlayer($playerid){
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('taggeeid',$playerid);
        $this->db->where('invalid',0);
        $query = $this->db->get();

        if($query->num_rows() == 1){
            return true;
        }
        return false;
    }

    public function numTagsForDate($date_id, $game_id){
        $query = $this->db->query(
          'SELECT COUNT(*) as count
            FROM tag, game
            JOIN player
            WHERE player.id = taggeeid
            AND game.id = \'' . $game_id . '\'
            AND tag.datetimeclaimed >= '. $date_id .'
            AND tag.datetimeclaimed < DATE_ADD(' . $date_id . ', INTERVAL 24 hour)'
        );
        return $query->row()->{'count'};
    }

    // returns array of taggees
    public function getTaggeeIDAndTimeByPlayerID($playerid){
        $this->db->select('taggeeid, datetimeclaimed');
        $this->db->from($this->table_name);
        $this->db->where('taggerid', $playerid);
        $this->db->where('invalid', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    //returns true if player has tagged anyone
    public function checkForTagByPlayerID($playerid){
        // @TODO: do this via COUNT
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('taggerid',$playerid);
        $this->db->where('invalid',0);
        $query = $this->db->get();
        if($query->num_rows() >= 1){
            return true;
        }
        return false;
    }

    // return the tagid if the player has been tagged
    public function getTagIDForPlayer($playerid){
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('taggeeid',$playerid);
        $this->db->where('invalid',0);
        $query = $this->db->get();

        // @TODO: throw an exception if this returns more than one result
        if($query->num_rows() == 1){
            return $query->row()->id;
        }
        return false;
    }

    public function getNumberOfTagsMadeByPlayerID($playerid){
        $this->db->select('COUNT(*) as count');
        $this->db->from($this->table_name);
        $this->db->where('taggerid',$playerid);
        $this->db->where('invalid',0);
        $query = $this->db->get();

        if($query->num_rows() == 1){
            return $query->row()->count;
        }
        return false;
    }

    public function getTagsInOrder(){
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('invalid',0);
        $this->db->order_by('datetimeclaimed', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function countTagsByGameID($gameid){
        $this->db->select('COUNT(tag.id) as count');
        $this->db->from($this->table_name);
        $this->db->join('player', 'tag.taggerid = player.id');
        $this->db->where('tag.invalid', 0);
        $this->db->where('player.gameid', $gameid);
        $query = $this->db->get();
        return $query->row()->count;
    }

    public function getData($tagid, $name){
        if(array_key_exists($name, $this->tagFields)){
            $this->db->select($name);
            $this->db->from($this->table_name);
            $this->db->where('id',$tagid);
            $query = $this->db->get();
            if (!$query || $query->num_rows() != 1){
                throw new DatastoreException('Too many (or few) records for tagid: '.$tagid.' name: '.$name);
            }
            return $query->row()->$name;
        } else {
            throw new UnexpectedValueException($name.' is not a valid field for feed');
        }
    }

    public function invalidateTag($tagid){
        if(!$tagid){
            throw new UnexpectedValueException('tagid cannot be null');
        }
        $data = array(
            "invalid" => 1
        );
        $this->db->where('id',$tagid);
        $this->db->update($this->table_name,$data);
    }

}
?>
