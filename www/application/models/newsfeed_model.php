<?php
class Newsfeed_model extends CI_Model{
    private $table_name = 'newsfeed';
    function __construct(){
        parent::__construct();
    }

    public function insertNewsItem($message_text, $message_type, $message_payload, $gameid, $external=FALSE){
        $data = array(
            'message_text' => $message_text,
            'message_type' => $message_type,
            'message_payload' => $message_payload,
            'date_created' => gmdate("Y-m-d H:i:s", time()),
            'gameid' => $gameid,
            'external' => $external
        );
        $this->db->insert($this->table_name, $data);
        return TRUE;
    }

    public function getNewsItemsByGameID($gameid, $limit=20){
        $this->db->select('message_text, date_created, message_type');
        $this->db->from($this->table_name);
        $this->db->where('gameid', $gameid);
        $this->db->order_by('date_created', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

}
?>
