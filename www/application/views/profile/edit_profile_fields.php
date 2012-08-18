
            <fieldset>
              <div class="clearfix">
                  <label>Age</label>
                  <div class="input">
                      <?php echo form_error('age'); ?>
                      <select name = "age">
                        <option> </option>
                          <?php
                            for($i = 13; $i < 114; $i = $i + 1 ){
                                if($i == $age){
                                  echo "<option SELECTED value =". $i . " > ";
                                  echo $i;
                                  echo "</option> ";
                                }else{
                                  echo "<option value =". $i . " > ";
                                  echo $i;
                                  echo "</option> ";
                                }
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
                          <?php
                            $options = array("Male"=> "male", "Female" => "female", "Other" => "other");
                            foreach($options as $key => $value){
                              if($value == $gender){
                                echo "<option SELECTED value = \"$value\">$key</option>";
                              }
                              else{
                                echo "<option value = \"$value\">$key</option>";
                              }
                            }
                          ?>
                      </select>
                </div>
              </div>
              <div class="clearfix">
                  <label>Major</label>
                  <div class="input">
                     <?php echo form_error('major'); ?>
                      <input type="text" name = "major" value="<?php echo $major; ?>"/>
                  </div>
              </div>
              <div class="clearfix">
                  <label>Photo (Gravatar Email)</label>
                  <div class="input">
                      <td style="color: red;"><?php echo form_error('gravatar_email'); ?></td>
                      <input type="text" name = "gravatar_email" value="<?php echo $gravatar_email; ?>"/>
                  </div>
                  <a  target="_blank" id = "setup_gravatar" href= "https://en.gravatar.com/site/signup/">
                   Click here to set up a Gravatar
                   </a>
              </div>
            </fieldset>