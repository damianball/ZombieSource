<?php
class Feed_model extends CI_Model{
    private $table_name = 'feed';
    private $feedFields = array(
        'zombieid' => 'uuid',
        'tagid' => 'uuid',
        'invalid' => 'int',
        'added_by_admin' => 'int',
        'datecreated' => 'datetime'
    );

    function __construct(){
        parent::__construct();
    }

    public function storeNewFeed($zombiePlayerID, $tagid, $datecreated = null, $adminAdded = null){
        if(!$zombiePlayerID){
            throw new UnexpectedValueException('zombie player id required');
        }
        if(!$tagid && !$adminAdded){
            throw new UnexpectedValueException('tag id or admin added required');
        }

        //date created
        if(!$datecreated){
            $datecreated = GameTime::gmdate();
        }

        //get new UUID
        $query = $this->db->query('SELECT UUID() as "uuid"');
        $uuid = $query->row()->{'uuid'};
        $data = array(
            'id' => $uuid,
            'zombieid' => $zombiePlayerID,
            'tagid' => $tagid,
            'datecreated' => $datecreated,
            'added_by_admin' => ($adminAdded ? 1 : 0)
        );

        // @TODO: how do we know the query succeeded?
        $this->db->insert($this->table_name,$data);

        return $uuid;

        //throw new DatastoreException('Could not store feed.');
    }

    public function getMostRecentFeedIDByPlayerID($playerid){
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('zombieid',$playerid);
        $this->db->where('invalid', 0);
        $this->db->order_by('datecreated','desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() != 1){
            throw new PlayerDoesNotHaveAnyValidFeedsException('Too many (or few) results for player feeds. Playerid: '.$playerid);
        }
        return $query->row()->id;
    }

    public function getData($feedid, $name){
        if(array_key_exists($name, $this->feedFields)){
            $this->db->select($name);
            $this->db->from($this->table_name);
            $this->db->where('id',$feedid);
            $query = $this->db->get();
            if ($query->num_rows() != 1){
                throw new DatastoreException('Too many (or few) records for feedid: '.$feedid.' name: '.$name);
            }
            return $query->row()->$name;
        } else {
            throw new UnexpectedValueException($name.' is not a valid field for feed');
        }
    }
}
?>