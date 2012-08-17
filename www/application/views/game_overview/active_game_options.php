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
    <h3>Profile Information (Optional)</h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">      
        <div class="clearfix">
          <label>Age</label>
          <div class="input">
             <div class="error-text"><?php echo form_error('age'); ?></div>
              <select name = "age">
                <option> </option>
                  <?php
                    for($i = 10; $i < 114; $i = $i + 1 ){
                        echo "<option value =". $i . " > ";
                        echo $i;
                        echo "</option> ";
                    }
                  ?>
            </select>
          </div>
        </div>
        <div class="clearfix">
          <label>Gender</label>
          <div class="input" >
             <div class="error-text"><?php echo form_error('gender'); ?></div>
              <select name = "gender">
                  <option></option>
                  <option value = "male" >Male</option>
                  <option value = "female" >Female</option>
                  <option value = "other" >Other</option>
              </select>
          </div>
        </div>
        <div class="clearfix">
          <label>Major</label>
          <div class="input">
              <div class="error-text"><?php echo form_error('major'); ?></div>
              <input type="text" name = "major" value="<?php echo set_value('major'); ?>"/>
          </div>
        </div>
        <br>
        <div class="clearfix">
          <label class="checkbox">   
              <input type="checkbox" name="originalzombiepool" value="1"> Enter me into the Original Zombie Pool
          </label>
        </div>
        <br>
    </div>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Cancel</a>
    <a href="#" class="btn btn-info" data-gameid='<?php echo $gameid?>' data-dismiss="modal">Join</a>
  </div>
</div>

<?php
  if($user_in_game){
    echo '<a class="btn btn-danger leave_game_modal" data-toggle="modal" data-gameid=' . $gameid . '>Leave Game</a>';
  }elseif($registration_open){
    if($profile_is_empty){
      echo '<a class="btn btn-info join_game_modal" data-toggle="modal" data-gameid=' . $gameid . ' >Join Game</a>';
    }else{
      echo '<a class="btn btn-info join_game" data-gameid=' . $gameid . ' >Join Game</a>';
    }  
  }else{
    echo '<h2> Sorry, Registration is closed </h2>';
  }
?>

