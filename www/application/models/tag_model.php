<?php
class Tag_model extends CI_Model{
    private $table_name = 'tag';

    function __construct(){
        parent::__construct();
    }

    public function storeNewTag($human_code, $zombie_id, $claimed_tag_time_offset, $long=null, $lat=null){
        //$this->load->library('Exceptions');
        //throw new Exception('Could not store tag.');
        throw new DatastoreException('Could not store tag.');
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