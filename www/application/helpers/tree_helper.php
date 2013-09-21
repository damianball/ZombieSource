<?php

function getZombieFamilyTreeJSON($gameid){
    $CI =& get_instance();
    $CI->load->helper('gravatar_helper');
    $CI->load->model('Tag_model');
    $tree = array('children' => array());
    foreach(getOriginalZombiePlayersByGameID($gameid) as $oz){
        $tree['children'][] = getTreeNodeByPlayerObject($CI, $oz, '', $oz->isExposed());
    }
    return json_encode($tree);
}

function getOriginalZombiePlayersByGameID($gameid){
    $CI =& get_instance();
    $CI->load->library('PlayerCreator');
    $CI->db->select('id');
    $CI->db->from('player');
    $CI->db->where('gameid', $gameid);
    $CI->db->where('original_zombie', 1);
    $query = $CI->db->get();
    $players = array();
    foreach($query->result_array() as $row){
        $players[] = $CI->playercreator->getPlayerByPlayerID($row['id']);
    }
    return $players;
}

function getTreeNodeByPlayerObject($CI, $player, $date, $showName=TRUE){
    $user = $player->getUser();
    $killInfo = $CI->Tag_model->getTaggeeIDAndTimeByPlayerID($player->getPlayerID());
    $kills = array();
    foreach($killInfo as $kill){
        $kills[] = getTreeNodeByPlayerObject($CI,
            $CI->playercreator->getPlayerByPlayerID($kill['taggeeid']),
            $kill['datetimeclaimed']);
    }
    if($showName){
        $gravatar = getGravatarHTML($user->getData('gravatar_email'), $user->getEmail(), 30, array(), false);
        $name = $user->getUsername();
    } else {
        $gravatar = "http://i.imgur.com/JdSwQ.png";
        $name = "Original Zombie";
    }
    return array(
        'gravatar' => $gravatar,
        'name' => $name,
        'date' => $date,
        'children' => $kills
    );
}

function writeZombieTreeJSONByGameID($gameid){
    $CI =& get_instance();
    $CI->load->model('Game_model');
    $f = @fopen("json/" . $CI->Game_model->getGameSlugByGameID($gameid) . ".json", "w");
    if($f){
        $bytes = fwrite($f, getZombieFamilyTreeJSON($gameid));
        fclose($f);
        return $bytes;
    }
    // @TODO: do something useful if cannot write to file
    return FALSE;
}

?>
