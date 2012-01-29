
<div class="row">
    <div class="span10">
      <h2> Register a New Team </h2>
        <div class="span12">
              <?php 
             echo form_open($this->uri->uri_string())
              ?>
            <fieldset>
              <div class="clearfix">
                  <label>Name</label>
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
              <div class="clearfix">
                  <label>Description</label>
                  <div class="input">
                      <?php echo form_error('major'); ?>
                      <textarea name="description" cols="40" rows="5" value="<?php echo set_value('Description'); ?>"></textarea></br>
                  </div>
              </div>
              
            </fieldset>
                <div class="actions">
                  <input type="submit" value = "Save and Finish" class = "btn success"/></form> 
              </div>          
        </div>  
    </div>
 </div>