<?php
class Game_setting_model extends CI_Model{
	private $table_name = 'game_setting';

    public function __construct(){
        parent::__construct();
    }

    public function getSetting($gameid,$name){
    	$result = null;

	  	$this->db->select('value');
	  	$this->db->from($this->table_name);
	  	$this->db->where('gameid',$gameid);
	  	$this->db->where('name',$name);
	  	$query = $this->db->get();
	  	
	  	if($query->num_rows() == 1){
	  	    $result = $query->row()->value;
	  	}

	  	return $result;
    }
    
    public function storeSetting($gameid,$name,$value){
    	$this->db->trans_start();

    	$game_setting = $this->getSetting($gameid, $name);

    	if (!$game_setting) {
	        //insert new data
	        $data = array(
	            'gameid' => $gameid,
	            'name' => $name,
	            'value' => $value
	        );
	        $this->db->insert('game_setting',$data);
    	} else {
    		// update existing data
	        $this->db->where('gameid', $gameid);
	        $this->db->where('name', $name);
	        $this->db->update($this->table_name, array('value' => $value));
    	}

    	$this->db->trans_complete();

        $added = true;
        return $added;
    }
}
?>