<?php
class Tag_model extends CI_Model{
    private $table_name = 'tag';

    function __construct(){
        parent::__construct();
    }

    public function storeNewTag($humanPlayerID, $zombiePlayerID, $claimed_tag_time_offset = null, $long = null, $lat = null){
        if(!$humanPlayerID){
            throw new UnexpectedValueException('human player id required');
        }
        if(!$zombiePlayerID){
            throw new UnexpectedValueException('zombie player id required');
        }

        //date created
        $datecreated = gmdate("Y-m-d H:i:s", time());

        //date claimed
        if($claimed_tag_time_offset) {
            $dateclaimed = gmdate("Y-m-d H:i:s", time() - $claimed_tag_time_offset);
        } else {
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
        $teamidArray = array();
        if($query->num_rows() == 1){
            return true;
        }
        return false;
    }
}
?>