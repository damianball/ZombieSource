<?php
class User_player_model extends CI_Model{
    //private $table_name			= 'users';			// user accounts
    //private $profile_table_name	= 'user_profiles';	// user profiles
    //private $data_table_name    = 'user_data';      // user data <- migrate from profiles

    function __construct(){
        parent::__construct();
    }

    public function getUserIDByPlayerID($playerid){
        if($playerid != null){
            $this->db->select('users.id as id');
            $this->db->from('users');
            $this->db->join('player','player.userid = users.id');
            $this->db->where('player.id', $playerid);
            $query = $this->db->get();
            if ($query->num_rows() == 1) return $query->row()->id;
            return NULL;
        } else {
            throw new UnexpectedValueException('playerid cannot be null');
        }
    }
}