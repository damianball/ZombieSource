<?php

function tweet_text($subject){
    $tweets = array(
        'tag' => '%TAGGER% spread the zombie infection to %TAGGEE%!',
        'team_destroyed' => '%TEAM% has been wholly assimilated by the zombies!',
        'achievement_earned' => '%PLAYER% earned %ACHIEVEMENT_NAME%!'
    );
    return $tweets[$subject];
}

function tweet($text, $message_type, $message_payload, $gameid, $external){
    $CI =& get_instance();
    $CI->load->library('tweet');
    $CI->load->model('Newsfeed_model', '', TRUE);
    $CI->tweet->logout();
    $CI->tweet->set_tokens(array('oauth_token' => $CI->config->item('twitter_oauth_token'),
                                 'oauth_token_secret' => $CI->config->item('twitter_oauth_token_secret'),
    ));
    $CI->tweet->get_tokens();

    // Stuff it in the DB
    // @TODO: don't have this tied so tightly with tweeting
    $CI->Newsfeed_model->insertNewsItem($message_payload['html'],
        $message_type,
        json_encode($message_payload),
        $gameid,
        $external);
    // Returns an object with information about the tweet
    return $CI->tweet->call('post', 'statuses/update', array('status' => $text));
}

function tweet_tag($tag){
    $tagger = $tag->getTagger();
    if($tagger->getStatus() == 'zombie' && $tagger->getPublicStatus() == 'human'){
        $tagger_username = "A deadly creature";
        $tagger_open = '';
        $tagger_close = '';
        $tagger_gravatar = '<img src="http://i.imgur.com/YidMp.png" class="twtr-pic">';
    } else {
        $user = $tagger->getUser();
        $tagger_username = $user->getUsername();
        $tagger_open = '<a href="/user/' . $user->getUserID() . '">';
        $tagger_close = '</a>';
        $tagger_gravatar = $user->getGravatarHTML();
    }
    $taggee_user = $tag->getTaggee()->getUser();
    $taggee_username = $taggee_user->getUsername();
    $taggee_open = '<a href="/user/' . $taggee_user->getUserID() . '">';
    $taggee_close = '</a>';
    $taggee_gravatar = $taggee_user->getGravatarHTML();

    $text = tweet_text('tag');
    $text = str_replace('%TAGGER%', $tagger_username, $text);
    $text = str_replace('%TAGGEE%', $taggee_username, $text);
    $html = tweet_text('tag');
    $html = str_replace('%TAGGER%', $tagger_open . $tagger_gravatar . " " . $tagger_username . $tagger_close, $html);
    $html = str_replace('%TAGGEE%', $taggee_open . $taggee_gravatar . " " . $taggee_username . $taggee_close, $html);
    $payload = array('tagid' => $tag->getTagID(),
                     'html' => $html,
                     'text' => $text);
    tweet($text, 1, $payload, $tagger->getGameID(), FALSE);
}

function tweet_team_destroyed($team){
    $name = $team->getData('name');
    $open = '<a href="/team/' . $team->getTeamID() . '">';
    $close = '</a>';
    $text = tweet_text('team_destroyed');
    $text = str_replace('%TEAM%', $name, $text);
    $html = tweet_text('team_destroyed');
    $html = str_replace('%TEAM%', $open . $team->getGravatarHTML() . " " . $name . $close, $html);
    $payload = array('teamid' => $team->getTeamID(),
                     'html' => $html,
                     'text' => $text);
    tweet($text, 2, $payload, $team->getGameID(), FALSE);
}

function achievement_earned($achievementid, $playerid){
    $CI =& get_instance();
    $CI->load->library('PlayerCreator');
    $player = $CI->playercreator->getPlayerByPlayerID($playerid);
    if($player->getStatus() == 'zombie' && $player->getPublicStatus() == 'human'){
        // cloaked OZ
        $username = 'A deadly creature';
        $gravatar = '<img src="http://i.imgur.com/YidMp.png" class="twtr-pic">';
        $open = '';
        $close = '';
    } else {
        $user = $player->getUser();
        $username = $user->getUsername();
        $gravatar = $user->getGravatarHTML();
        $open = '<a href="/user/' . $user->getUserID() . '">';
        $close = '</a>';
    }
    $CI->load->model('Achievement_model');
    $typeinfo = $CI->Achievement_model->getAchievementType($achievementid);
    $typename = $typeinfo->name;
    $imageurl = $typeinfo->image_url;
    $desc = $typeinfo->description;
    $text = tweet_text('achievement_earned');
    $text = str_replace('%PLAYER%', $username, $text);
    $text = str_replace('%ACHIEVEMENT_NAME%', $typename, $text);
    $html = tweet_text('achievement_earned');
    $html = str_replace('%PLAYER%', $open . $gravatar . " " . $username . $close, $html, $html);
    $html = str_replace('%ACHIEVEMENT_NAME%', '<div class="achievement" style="display: inline;" rel="tooltip" title="' . $desc . '"><img class="twtr-pic" height="50px" width="50px" src="' . $imageurl . '"> <b>' . $typename . '</b></div>', $html);
    $payload = array('player' => $playerid,
                     'achievementid' => $achievementid,
                     'html' => $html,
                     'text' => $text);
    tweet($text, 5, $payload, $player->getGameID(), FALSE);
}

?>
