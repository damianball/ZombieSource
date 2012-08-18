<?php
// user helper
// @TODO: do some checking for null... ya know
function getUserIDByUsername($username){
    $CI =& get_instance();
    $CI->load->model('User_model','',TRUE);
    return $CI->User_model->getUserIDByUsername($username);
}

function getPublicUserProfileDataArray($user){
    $CI =& get_instance();
    $CI->load->helper('gravatar_helper');
    $CI->load->helper('team_helper');

    $data = array();
    $data['username'] = $user->getUsername();
    $data['email'] = $user->getEmail();
    $data['age'] = $user->getData("age");
    $data['gender'] = $user->getData("gender");
    $data['major'] = $user->getData("major");
    $data['profile_pic_url'] = getGravatarHTML($user->getData('gravatar_email'), $user->getEmail(), 150);
    $data['gravatar_email'] = $user->getData('gravatar_email');
    //$data['human_code'] = (is_a($player,'Human') ? $player->getHumanCode() : $data['human_code'] = null); $data['link_to_team'] = getHTMLLinkToPlayerTeam($player);
    //$data['status'] = $player->getStatus();
    $data['user'] = $user->getUserID();
    return $data;
}

function getPrivateUserProfileDataArray($user){
    $CI =& get_instance();
    $CI->load->helper('gravatar_helper');
    $CI->load->helper('team_helper');

    $data = array();
    $data['username'] = $user->getUsername();
    $data['email'] = $user->getEmail();
    $data['age'] = $user->getData("age");
    $data['gender'] = $user->getData("gender");
    $data['major'] = $user->getData("major");
    $data['profile_pic_url'] = getGravatarHTML($user->getData('gravatar_email'), $user->getEmail(), 150);
    $data['gravatar_email'] = $user->getData('gravatar_email');
    //$data['human_code'] = (is_a($player,'Human') ? $player->getHumanCode() : $data['human_code'] = null); $data['link_to_team'] = getHTMLLinkToPlayerTeam($player);
    //$data['status'] = $player->getStatus();
    $data['user'] = $user->getUserID();
    return $data;
}
?>
