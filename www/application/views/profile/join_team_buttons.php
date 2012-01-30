      <?php 
        echo form_open('game/join_team');
      ?>
      <input name = "teamid" style = "display: none;" value = "<?php echo $teamid ?>"> </input>
      <input type="submit" value = "Join This Team" id = "player_status" class = "alert-message success"/></form> 