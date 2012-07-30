<?php
  if($user_in_game){
    echo '<button class="btn btn-info leave_game" data-gameid=' . $gameid . ' >Leave Game</button>';
  }else{
    echo '<button class="btn btn-info join_game" data-gameid=' . $gameid . ' >Join Game</button>';
  }
?>
