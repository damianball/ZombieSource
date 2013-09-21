<?php
class Notification_model extends CI_Model{
  private $table_name = 'notification';

  function __construct(){
    parent::__construct();
  }

    public function getNotificationIDByName($name){
      $this->db->select('id');
      $this->db->from($this->table_name);
      $this->db->where('name', $name);

      $query = $this->db->get();
      if($query->num_rows() != 1){
          throw new NoNotificationException('Did not find a Notification for name '. $name);
      }
      return $query->row()->id;
    }

    public function groupIDfromNotificationID($notification_id){
      $this->db->select('subscription_group_id');
      $this->db->from($this->table_name);
      $this->db->where('id', $notification_id);

      $query = $this->db->get();
      if($query->num_rows() != 1){
          throw new NoNotificationException('Did not find a Notification for name '. $notification_id);
      }
      return $query->row()->subscription_group_id;
  }

  public function updateLastRunTime($notification_id){
    $data = array(
            "last_run_date" => date('Y-m-d H:i:s', time())
    );
    $this->db->where('id', $notification_id);
    $this->db->update($this->table_name, $data);

  }

  public function getLastRunDate($notification_id){
      $this->db->select('last_run_date');
      $this->db->from($this->table_name);
      $this->db->where('id', $notification_id);

      $query = $this->db->get();
      if($query->num_rows() != 1){
          throw new NoNotificationException('Did not find a Notification for id '. $notification_id);
      }
      return $query->row()->last_run_date;
  }
}
?>
