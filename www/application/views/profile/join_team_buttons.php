      <div class="box">
        <?php
          echo form_open("game/$url_slug/join_team");
        ?>
        <input name = "teamid" style = "display: none;" value = "<?php echo $teamid ?>"> </input>
        <input type="submit" value = "Join Team"  class = "btn btn-success alert-message success profile_button"/></form>
      </div>
