<?php
  if($user_in_game){
    echo '<button class="btn btn-info leave_game " data-gameid=' . $gameid . ' >Leave Game</button>';
  }else{
    echo '<h2> Sorry, Registration is closed </h2>';
  }
?>
