      <div class="box">
        <?php
          echo form_open("game/$url_slug/leave_team");
        ?>
        <input name = "teamid" style = "display: none;" value = "<?php echo $teamid ?>"> </input>
        <input type="submit" value = "Leave Team"  class = "btn btn-red alert-message success profile_button"/></form>
      </div>
