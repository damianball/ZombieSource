<?php
class User_model extends CI_Model{
    private $table_name			= 'users';			// user accounts
    private $profile_table_name	= 'user_profiles';	// user profiles
    private $data_table_name    = 'user_data';      // user data <- migrate from profiles

    function __construct(){
        parent::__construct();
    }

    public function getEmailByUserID($userid){
        if($userid != null){
            $this->db->select('email');
            $this->db->where('id', $userid);
            $query = $this->db->get($this->table_name);
            if ($query->num_rows() == 1) return $query->row()->email;
            return NULL;
        } else {
            throw new UnexpectedValueException('userid cannot be null');
        }
    }

    public function getUsernameByUserID($userid){
        if($userid != null){
            $this->db->select('username');
            $this->db->where('id', $userid);
            $query = $this->db->get($this->table_name);
            if ($query->num_rows() == 1) return $query->row()->username;
            return NULL;
        } else {
            throw new UnexpectedValueException('userid cannot be null');
        }
    }

    public function getUserIDByUsername($username){
        if($username != null){
            $this->db->select('id');
            $this->db->like('username', $username);
            $query = $this->db->get($this->table_name);
            if ($query->num_rows() == 1){
                return $query->row()->id;
            } else {
                return NULL;
            }
        } else {
            throw new UnexpectedValueException('username cannot be null');
        }
    }
}