<div class="modal hide" id="leave">
  <div class="modal-header">
    <h3>Hey!</h3>
  </div>
  <div class="modal-body">
    <p>Are you sure you want to leave the game??</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">No</a>
    <a href="#" class="btn btn-primary leave_game" data-gameid='<?php echo $gameid?>' data-dismiss="modal">Yes</a>
  </div>
</div>

<div class="modal hide" id="join">
  <div class="modal-header">
    <h3>Hey</h3>
  </div>
  <div class="modal-body">
    <p>Joining this game will remove you from </p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">No</a>
    <a href="#" class="btn btn-primary leave_game" data-gameid='<?php echo $gameid?>' data-dismiss="modal">Yes</a>
  </div>
</div>

<?php
  if($user_in_game){
    echo '<a class="btn btn-danger leave_game_modal" data-toggle="modal" data-gameid=' . $gameid . '>Leave Game</a>';
  }elseif($registration_open){
    echo '<a class="btn btn-info join_game" data-gameid=' . $gameid . ' >Join Game</a>';
  }else{
    echo '<h2> Sorry, Registration is closed. </h2>';
  }
?>
