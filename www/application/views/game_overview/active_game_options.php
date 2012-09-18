<div class="modal hide" id="leave">
  <div class="modal-header">
    <h3>Hey!</h3>
  </div>
  <div class="modal-body">
    <p>Are you sure you want to leave the game?</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">No</a>
    <a href="#" class="btn btn-primary leave_game" data-gameid='<?php echo $gameid?>' data-dismiss="modal">Yes</a>
  </div>
</div>

<div class="modal hide" id="join">
  <div class="modal-header">
 </div>
  <div class="modal-body">
    <?php
    if(!$waiver_signed){
    echo $join_game_sign_waiver;

    }
    if($profile_is_empty){
      echo $join_game_edit_profile;
    }else{
      echo '<br>
            <div class="clearfix">
              <label class="checkbox">
                  <input type="checkbox" name="originalzombiepool" value="1"> Enter me into the Original Zombie Pool
              </label>
            </div>
            <br>';
    }
    ?>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Cancel</a>
    <a href="#" class="btn btn-info join_game" data-gameid='<?php echo $gameid?>' data-dismiss="modal">Join</a>
  </div>
</div>

<?php
  if($user_in_game){
    echo '<a class="btn btn-danger leave_game_modal" data-toggle="modal" data-gameid=' . $gameid . '>Leave Game</a>';
  }elseif($registration_open){
    echo '<a class="btn btn-info join_game_modal" data-toggle="modal" data-gameid=' . $gameid . ' >Join Game</a>';
  }else{
    echo '<h1><small>Registration is closed</small></h1>';
  }
?>

