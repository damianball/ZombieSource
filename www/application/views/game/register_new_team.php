
<div class="row">
    <div class="span10">
      <h2> Register a New Team </h2>
      <hr>
        <div class="span12">
              <?php 
              echo form_open($this->uri->uri_string());
              ?>
            <fieldset>
              <div class="clearfix">
                  <label>Team Name</label>
                  <div class="input">
                      <div class="error-text"><?php echo form_error('team_name'); ?></div>
                      <input type="text" name = "team_name" value=""/>
                  </div>
              </div>

              <div class="clearfix">
                  <label>Photo (Gravatar Email)</label>
                  <div class="input">
                      <div class="error-text"><?php echo form_error('team_gravatar_email'); ?></div>
                      <input type="text" name = "team_gravatar_email" value=""/>
                  </div>
                  <a id = "setup_gravatar" href= "https://en.gravatar.com/site/signup/">
                   Click here to set up a Gravatar 
                   </a>
              </div>   
              <div class="clearfix">
                  <label>Description</label>
                  <div class="input">
                      <div class="error-text"><?php echo form_error('description'); ?></div>
                      <textarea name="description" cols="40" rows="5" value=""></textarea></br>
                  </div>
              </div>    
            </fieldset>
                <div class="actions">
                  <input type="submit" value = "Save and Finish" class = "btn success"/></form> 
              </div>          
        </div>  
    </div>
 </div>