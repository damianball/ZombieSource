<?php
class Achievement_model extends CI_Model{
    private $table_name = 'achievement';

    function __construct(){
        parent::__construct();
        $this->load->helper('date_helper');
    }

    public function getAchievementsByPlayerID($playerid){
        $this->db->select('name, description, image_url, achievement.achievement as id');
        $this->db->from($this->table_name);
        $this->db->join('achievement_type', 'achievement.achievement = achievement_type.id');
        $this->db->where('playerid', $playerid);
        $this->db->where('achievement.invalid', 0);
        $query = $this->db->get();
        if(!$query){
            return array();
        }
        return $query->result_array();
    }

    public function countAchievementsByPlayerID($playerid){
        $this->db->select('COUNT(id) as count');
        $this->db->from($this->table_name);
        $this->db->where('playerid', $playerid);
        $this->db->where('invalid', 0);
        $query = $this->db->get();
        return $query->row()->count;
    }

    public function checkAchievementExistsByPlayerIDAchievementID($playerid, $achievementid){
        $this->db->select('COUNT(id) as count');
        $this->db->from($this->table_name);
        $this->db->where('playerid', $playerid);
        $this->db->where('achievement', $achievementid);
        $this->db->where('invalid', 0);
        $query = $this->db->get();
        return ($query->row()->count > 0);
    }

    public function getAchievementDataByPlayerIDAchievementType($playerid, $achievementtype){
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('playerid', $playerid);
        $this->db->where('achievement_type', $achievementtype);
        $this->db->where('invalid', 0);
        $query = $this->db->get();
        if($query->num_rows() == 0){
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function getAchievementType($typeid){
        $this->db->select('*');
        $this->db->from('achievement_type');
        $this->db->where('id', $typeid);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getUserInfoByAchievementType($typeid, $gameid){
        $this->db->select('users.username as username, users.ID as userid, achievement.dateachieved as date');
        $this->db->from($this->table_name);
        $this->db->join('player', 'player.id = achievement.playerid');
        $this->db->join('users', 'player.userid = users.id');
        $this->db->where('achievement.achievement', $typeid);
        $this->db->where('player.gameid', $gameid);
        $this->db->where('invalid', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAchievementTypes(){
        $this->db->select('*');
        $this->db->from('achievement_type');
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getKillCountByPlayerID($playerid){
        $this->db->select('COUNT(id) as count, MAX(datetimeclaimed) as latest');
        $this->db->from('tag');
        $this->db->where('taggerid', $playerid);
        $this->db->where('invalid', 0);
        $query = $this->db->get();
        return $query->row();
    }

    public function getLargestKillStreakInXHours($tag, $num_hours){
        $end_time = $tag->getTagDateTimeClaimed();
        $start_time = getUTCTimeLessXSeconds($end_time, $num_hours * 60 * 60);
        $taggerid = $tag->getTaggerID();
        $this->db->select('COUNT(id) as count, MAX(datetimeclaimed) as latest');
        $this->db->from('tag');
        $this->db->where('taggerid', $taggerid);
        $this->db->where('invalid', 0);
        $this->db->where("datetimeclaimed BETWEEN '" . $start_time . "' AND '" . $end_time . "'");
        $query = $this->db->get();
        return $query->row();
    }

    public function addAchievement($playerid, $achievementid, $date){
        $data = array(
            'id' => NULL,
            'playerid' => $playerid,
            'achievement' => $achievementid,
            'dateachieved' => $date,
            'invalid' => 0
        );
        return $this->db->insert($this->table_name, $data);
    }

    public function invalidateAchievement($playerid, $achievementid){
        $this->db->where('playerid', $playerid);
        $this->db->where('achievement', $achievementid);
        $this->db->where('invalid', 0);
        return $this->db->update($this->table_name, array('invalid' => 1));
    }
}
?>
