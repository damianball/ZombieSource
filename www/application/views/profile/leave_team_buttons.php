      <div class = "profile_button">
        <?php 
          echo form_open('game/leave_team');
        ?>
        <input name = "teamid" style = "display: none;" value = "<?php echo $teamid ?>"> </input>
        <input type="submit" value = "Leave Team" id = "player_status" class = "alert-message success"/></form> 
      </div>
      <div class = "profile_button">
        <?php 
          echo form_open('profile/edit_team');
        ?>
        <input name = "teamid" style = "display: none;" value = "<?php echo $teamid ?>"> </input>
        <input type="submit" value = "Edit Team" id = "player_status" class = "alert-message success"/></form>
      </div> 
