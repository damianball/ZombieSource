<?php
class User_model extends CI_Model{
    private $table_name			= 'users';			// user accounts
    private $profile_table_name	= 'user_profiles';	// user profiles
    private $data_table_name    = 'user_data';      // user data <- migrate from profiles
    private $table_editable_fields = array(
    );

    function __construct(){
        parent::__construct();
    }

    public function profileIsEmpty($userid){
        if($userid != null){
            $this->db->select('*');
            $this->db->where('userid', $userid);
            $query = $this->db->get($this->data_table_name);
            if ($query->num_rows() > 0) return false;
            return true;
        } else {
            throw new UnexpectedValueException('userid cannot be null');
        }
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
        if($username != NULL){
            $this->db->distinct();
            $this->db->select('id');
            $this->db->like('username', $username);
            $query = $this->db->get($this->table_name);
            if ($query->num_rows() > 0){
                return $query->row()->id;
            } else {
                return NULL;
            }
        } else {
            throw new UnexpectedValueException('username cannot be null');
        }
    }

       private function getUserTableData($userid, $name){
         $this->db->select($name);
         $this->db->from($this->table_name);
         $this->db->where('id',$userid);
         $query = $this->db->get();

         if($query->num_rows() != 1){
             throw new DatastoreException('More (or less) results for userid than expected, results: '.$query->num_rows());
         }

         return $query->row()->$name;
     }

     private function getUserDataTableData($userid, $name){
         $query = $this->db->query('SELECT value FROM user_data WHERE userid = '.$this->db->escape($userid).' AND name = '.$this->db->escape($name).' ORDER BY timestamp DESC LIMIT 1');
         $result = $query->row();
         $value = null;
         if(isset($result->{'value'})){
             $value = $result->{'value'};
         }

         return $value;
     }

     public function getUserData($userid, $name){
         if(array_key_exists($name, $this->table_editable_fields)){
             return $this->getUserTableData($userid, $name);
         } else {
             return $this->getUserDataTableData($userid, $name);
         }
     }

     private function setUserDataTableData($userid, $name, $value){
         //date created
         $datecreated = gmdate("Y-m-d H:i:s", time());

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

     private function setUserTableData($userid, $name, $value){
         $data = array(
             $name => $value
         );
         $this->db->where('id',$userid);
         $this->db->update($this->table_name,$data);
         $added = true;
         return $added;
     }

     public function setUserData($userid, $name, $value){
         if(array_key_exists($name, $this->table_editable_fields)){
             return $this->setUserTableData($userid, $name, $value);
         } else {
             return $this->setUserDataTableData($userid, $name, $value);
         }
     }




}
