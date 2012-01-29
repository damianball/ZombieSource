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
            $this->db->select('id');
            $this->db->from('users');
            $this->db->join('player','player.userid = users.id');
            $this->db->where('player.id', $playerid);
            $query = $this->db->get('player');
            if ($query->num_rows() == 1) return $query->row();
            return NULL;
        } else {
            throw new UnexpectedValueException('playerid cannot be null');
        }
    }
}