<?php

// team healper

function getAllTeamsByGameID($gameid){
    if(!$gameid){
        throw new UnexpectedValueException('Gameid not set.');
    }
    $CI =& get_instance();
    $CI->load->model('Team_model','',TRUE);
    $teamids = $CI->Team_model->getAllTeamIDsByGameID($gameid);

    $CI->load->library('TeamCreator');
    $teamArray = array();
    foreach($teamids as $i){
        $teamArray[] = $CI->teamcreator->getTeamByTeamID($i);
    }

    return $teamArray;
}

function getTeamProfileDataArray($team){
    $CI =& get_instance();
    $CI->load->helper('gravatar_helper');

    $data = array();
    $data['team_name'] = $team->getData('name');
    $data['description'] = $team->getData('description');
    $data['team_gravatar_email'] = $team->getData('gravatar_email');
    $data['profile_pic_url'] = getGravatarHTML($data['team_gravatar_email'], $data['team_name'], 250);
    $data['teamid'] = $team->getTeamID();

    return $data;
}

function getHTMLLinkToTeam($team){
    $teamid = $team->getTeamID();
    $teamName = $team->getData('name');
    $link = "<a href = \"" . site_url("/team/$teamid") .  "\"> $teamName </a>";
    return $link; 
}

function getHTMLLinkToTeamProfile($team){
    $name = $team->getData('name');
    $id = $team->getTeamID();
    $link = "<a href = \"" . site_url("/team/$id") .  "\"> $name </a>";
    return $link; 
}

?>