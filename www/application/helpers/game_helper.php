<?php

function getActivePlayers($gameid){
    if(!$gameid){
        throw new UnexpectedValueException('Gameid not set.');
    }
    $CI =& get_instance();
    $CI->load->model('Player_model','',TRUE);
    $playerids = $CI->Player_model->getActivePlayerIDsByGameID($gameid);

    $CI->load->library('PlayerCreator');
    $playerArray = array();
    foreach($playerids as $i){
        $playerArray[] = $CI->playercreator->getPlayerByPlayerID($i);
    }

    return $playerArray;
}

function getPublicActiveZombies($gameid){
    $players = getActivePlayers($gameid);
    $active_zombies =  array();
    foreach($players as $player){
        if(is_a($player, 'Zombie') && $player->isActive()){
            if(is_a($player, 'OriginalZombie') && !$player->isExposed()){
                continue;
            }
            $active_zombies[] = $player;
        }
    }
    return $active_zombies;
}

function getActiveZombies($gameid){
    $players = getActivePlayers($gameid);
    $active_zombies = array();
    foreach($players as $player){
        if(is_a($player, 'Zombie') && $player->isActive()){
            $active_zombies[] = $player;
        }
    }
    return $active_zombies;
}

function getActiveHumans($gameid){
    $humans = getActivePlayers($gameid);
    $active_humans =  Array();
    foreach($humans as $human){
        if(is_a($human, 'Human') && $human->isActive()){
            $active_humans[] = $human;
        }
    }
    return $active_humans;
}


function getActiveZombiesString($gameid){
    $zombies = getPublicActiveZombies($gameid);
    $my_string = "[";
    foreach($zombies as $zombie){
        $username = $zombie->getUser()->getUsername();
        $my_string .= "\"$username\",";
    }
    $my_string .= "\"\"]";
    return $my_string;
}

function getPlayerString($gameid){
    $players = getActivePlayers($gameid);
    $my_string = "[";
    foreach($players as $player){
        $username = $player->getUser()->getUsername();
        $my_string .= "\"$username\",";
    }
    $my_string .= "\"\"]";
    return $my_string;
}

?>