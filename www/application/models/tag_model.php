<?php
class Tag_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    public function storeNewTag($human_code, $zombie_id, $claimed_tag_time_offset, $long=null, $lat=null){
        //$this->load->library('Exceptions');
        //throw new Exception('Could not store tag.');
        throw new DatastoreException('Could not store tag.');
    }
}
?>