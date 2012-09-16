<?php //very similar to edit_profile_fields but different enough to need it's own view..cut me some slack I'm in a hurry ?>
  <legend>Profile Information (Optional) </legend>
    <div class="row-fluid join_game_edit_profile">
      <div class ="span4">
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
      <div class="clearfix">
        <label class="checkbox">   
            <input type="checkbox" name="originalzombiepool" value="1"> Enter me into the Original Zombie Pool
        </label>
      </div>
      <br>
      </div>
      <div class = "well join_game_setup_sms">
        <label class "phone_label">SMS Settings <span class="ten_digits">(phone must be 10 digits) </span> </label>
          <input class = "error" type="text" placeholder="10 Digit Phone Number" name="phone">
          <div class="checkbox small_subscription_option">
            <input type="checkbox" checked="checked" name="daily_updates"> Daily Updates: Recieve an update on zombie count every night. 
          </div>      

          <div class="checkbox small_subscription_option">
            <input type="checkbox"checked="checked" name="team_updates"> Team Updates: Recieve a text if someone on your team gets turned into a zombie! 
          </div>  

          <div class="checkbox small_subscription_option" >
            <input type="checkbox" name="mission_updates"> Mission Updates: Recieve mission updates from admins. 
          </div>            

      </div>
    </div>

