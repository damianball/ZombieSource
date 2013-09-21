<?php
class Job_model extends CI_Model{
  private $table_name = 'job';

  function __construct(){
    parent::__construct();
  }

  public function getJobParamsByNotificationIDGameID($notification_id,$game_id){
    $this->db->from($this->table_name);
    $this->db->where('notification_id', $notification_id);
    $this->db->where('game_id', $game_id);

    $query = $this->db->get();
    if($query->num_rows() != 1){
        throw new NoJobException('Did not find a job for gameid '.$game_id.' notification_id ' .$notification_id);
    }
    return $query->row();
  }


  public function updateLastRunTime($notification_id,$game_id){

    $data = array(
            "last_run_date" => date('Y-m-d H:i:s', time())
    );
    $this->db->where('notification_id', $notification_id);
    $this->db->where('game_id', $game_id);
    $this->db->update($this->table_name, $data);

  }
}
?>
