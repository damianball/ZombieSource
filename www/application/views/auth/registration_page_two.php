
  <?php //echo form_open("http://postcatcher.in/catchers/4f1182876366150100000004");
             echo form_open($this->uri->uri_string(), array('class'=>'form-horizontal'));
         ?>
  <div class="row-fluid">
<!--       <legend> Sign Safety Waiver (Required) </legend>
        
            <div class="clearfix">
              <label>Safety Waiver</label>
              <div class = "linkbutton">
              <a href = "http://dl.dropbox.com/u/30973/dean_of_students_waiver.pdf">
                <button class="btn btn-primary btn-margin"> READ ME </button>
              </a>
              </div>
              
            </div>
            <br>
         <div class="clearfix">
                <label>Agreements</label>
                <div class="input">
                    <ul class="inputs-list unstyled">
                        <li>
                            <label class="checkbox">
  					                   <div class="error-text"><?php echo form_error('waiver');?></div>
                                <input type="checkbox" name="waiver" value="signed" />
                                I accept the terms of the Safety Waiver
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="awesome" value="yes">
                                I am awesome
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <label>Signature</label>
            <div class="input">
  	         <div class="error-text"><?php echo form_error('sig'); ?></div>
              <input type="text" name="sig" value="<?php echo set_value('sig'); ?>">
            </div>
            <br>
            -->
      
      <legend> Profile Information (Optional) </legend>
 
            
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
                <div class="actions">
                  <input type="submit" value = "Save and Finish" class = "btn btn-margin"/>
              </div>
      </div>
<?php echo form_close(); ?>
