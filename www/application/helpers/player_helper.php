<?php

function userExistsInGame($userid, $gameid){
    $CI = & get_instance();
    $CI->load->model('Player_model','',TRUE);
    try{
        $CI->Player_model->getPlayerID($userid, $gameid);
    } catch (PlayerDoesNotExistException $e) {
        return FALSE;
    }
    return TRUE;
}

function playerExistsByPlayerID($playerid){
    $CI = & get_instance();
    $CI->load->model('Player_model','',TRUE);
    try{
        $CI->Player_model->playerExistsByPlayerID($playerid);
    } catch (PlayerDoesNotExistException $e) {
        return FALSE;
    }
    return TRUE;
}

// @TODO: Decide how to handle PlayerDoesNotExist...
function getPlayerIDByUserIDGameID($userid, $gameid){
    $CI = & get_instance();
    $CI->load->model('Player_model','',TRUE);
    $playerid = null;
    try{
        $playerid = $CI->Player_model->getPlayerID($userid, $gameid);
    } catch (PlayerDoesNotExistException $e) {
        return FALSE;
    }
    return $playerid;
}

// MOVE TO HELPER
function getHTMLLinkToProfile($player){
    $user = $player->getUser();
    $username = $user->getUsername();
    $id = $user->getUserID();
    $link = "<a href = \"" . site_url("/user/$id") .  "\"> $username </a>";
    return $link; 
}

// MOVE TO HELPER
function getHTMLLinkToPlayerTeam($player){
    if($player->isMemberOfATeam()){
        $CI =& get_instance();
        $CI->load->library('TeamCreator');
        $teamid = $player->getTeamID();
        $team = $CI->teamcreator->getTeamByTeamID($teamid);
        $CI->load->helper('team_helper');
        return getHTMLLinkToTeam($team);
    }else{
        return "none";
    }
}

// MOVE TO PLAYER HELPER?
function getPrivatePlayerProfileDataArray($player){
    $CI =& get_instance();
    $CI->load->helper('gravatar_helper');
    $CI->load->helper('team_helper');

    $data = array();
    $data['username'] = $player->getUser()->getUsername();
    $data['email'] = $player->getUser()->getEmail();
    $data['age'] = $player->getData("age");
    $data['gender'] = $player->getData("gender");
    $data['major'] = $player->getData("major");
    $data['profile_pic_url'] = getGravatarHTML($player->getData('gravatar_email'), $player->getUser()->getEmail(), 150);
    $data['gravatar_email'] = $player->getData('gravatar_email');
    $data['human_code'] = (is_a($player,'Human') ? $player->getHumanCode() : $data['human_code'] = null);
    $data['link_to_team'] = getHTMLLinkToPlayerTeam($player);
    return $data;
}

function getPublicPlayerProfileDataArray($player){
    $CI =& get_instance();
    $CI->load->helper('gravatar_helper');
    $CI->load->helper('team_helper');

    $data = array();
    $data['username'] = $player->getUser()->getUsername();
    $data['email'] = $player->getUser()->getEmail();
    $data['age'] = $player->getData("age");
    $data['gender'] = $player->getData("gender");
    $data['major'] = $player->getData("major");
    $data['profile_pic_url'] = getGravatarHTML($player->getData('gravatar_email'), $player->getUser()->getEmail(), 150);
    $data['gravatar_email'] = $player->getData('gravatar_email');
    $data['human_code'] = (is_a($player,'Human') ? $player->getHumanCode() : $data['human_code'] = null);
    $data['link_to_team'] = getHTMLLinkToPlayerTeam($player);
    return $data;
}

?>