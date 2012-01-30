      <div class="box">  
        <?php 
          echo form_open('game/leave_team');
        ?>
        <input name = "teamid" style = "display: none;" value = "<?php echo $teamid ?>"> </input>
        <input type="submit" value = "Leave Team"  class = "alert-message success profile_button"/></form> 
      </div>