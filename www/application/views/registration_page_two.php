<div class="page-header">
          <h1>Humans vs Zombies <small> Dead of Winter </small></h1>
        </div>
        <div class="row">
          <div class="span10">
            <h2> Sign Safety Waiver (Required) </h2>
              <div class="span12">
              <?php //echo form_open("http://postcatcher.in/catchers/4f1182876366150100000004"); 
                   echo form_open($this->uri->uri_string())
               ?>

                  <div class="clearfix">
                    <label id = test >Safety Waiver</label>
                    <div class = "linkbutton">
                    <a href = "http://dl.dropbox.com/u/30973/dean_of_students_waiver.pdf">
                      <button class="btn primary"> READ ME </button>
                    </a>
                    </div>
                  </div> 

               <div class="clearfix">
                      <label>Agreements</label>
                      <div class="input">
                          <ul class="inputs-list">
                              <li>
                                  <label>
									<?php echo form_error('waiver'); ?>
                                      <input type="checkbox" name="waiver" value="signed" />
                                      I accept the terms of the Safety Waiver
                                  </label>
                                  <label>
                                      <input type="checkbox" name="awesome" value="yes">
                                      I am awesome
                                  </label>
                              </li>
                          </ul>
                      </div>
                  </div>
                  <label>Signature</label>
                  <div class="input">
					<?php echo form_error('sig'); ?>
                    <input type="text" name="sig" value="<?php echo set_value('sig'); ?>"/>
                  </div>
                  </fieldset>
              </div> 
            <h2> Profile Information (Optional) </h2>
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
                        <label>   </label>
								            <input type="checkbox" name="originalzombiepool" value="1"> Enter me into the Original Zombie Pool
                        </label>
                    </div>         
                  </fieldset>
                      <div class="actions">
                        <input type="submit" value = "Save and Finish" class = "btn success"/></form> 
                    </div>          
              </div>  
          </div>
        </div>