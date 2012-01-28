<div class="page-header">
  <h1>Humans vs Zombies <small> Dead of Winter </small></h1>
</div>
<div class="row">
    <div class="span10">

      <h2> Update Profile Information </h2>
        <div class="span12">
            <fieldset>
              <div class="clearfix">
                  <label>Age</label>
                  <div class="input">
        <?php echo form_error('age'); ?>
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
        <?php echo form_error('gender'); ?>
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
        <?php echo form_error('major'); ?>
                      <input type="text" name = "major" value="<?php echo set_value('major'); ?>"/>
                  </div>
              </div>
              <div class="clearfix">
                  <label>Email</label>
                  <div class="input">
                      <?php echo form_error('major'); ?>
                      <input type="text" name = "major" value="<?php echo set_value('major'); ?>"/>
                  </div>
              </div>
              <div class="clearfix">
                  <label>Photo (Gravatar Email)</label>
                  <div class="input">
                      <?php echo form_error('major'); ?>
                      <input type="text" name = "major" value="<?php echo set_value('major'); ?>"/>
                  </div>
                  <a id = "setup_gravatar" href= "https://en.gravatar.com/site/signup/">
                   Click here to set up a Gravatar 
                   </a>
              </div>                 
            </fieldset>
                <div class="actions">
                  <input type="submit" value = "Save and Finish" class = "btn success"/></form> 
              </div>          
        </div>  
    </div>
 </div>