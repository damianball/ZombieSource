<?php
class Game_setting_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    public function getSetting($gameid,$name){
        throw new DatastoreException('Could not store tag.');
    }
    
    public function storeSetting($gameid,$name,$value){
        
    }
}
?>