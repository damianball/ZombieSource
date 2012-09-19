<?php
class Userdata_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function getUserData($userid, $name){
		$query = $this->db->query('SELECT value FROM user_data WHERE userid = '.$this->db->escape($userid).' AND name = \''.$this->db->escape($name).'\' ORDER BY timestamp DESC LIMIT 1');
		$result = $query->row();
		$value = "";
		if(isset($result->{'value'})){
			$value = $result->{'value'};
		}
		
		return $value;
	}
	public function setUserData($userid, $name, $value){
		$added = false;

		//date created
		$datecreated = GameTime::gmdate();

		//insert new user data
		$data = array(
			'userid' => $userid,
			'name' => $name,
			'timestamp' => $datecreated,
			'value' => $value
		);
		$this->db->insert('user_data',$data);
		$added = true;
		
		return $added;
	}
}
?>