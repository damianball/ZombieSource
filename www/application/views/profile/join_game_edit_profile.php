<?php //very similar to edit_profile_fields but different enough to need it's own view..cut me some slack I'm in a hurry ?>
  <legend>Profile Information (Optional) </legend>
    <div class="row-fluid join_game_edit_profile">      
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
    </div>