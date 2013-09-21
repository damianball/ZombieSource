
<div class="row">
    <div class="span10">
      <h2> Edit Team <small> <?php echo $team_name ?></small></h2>
      <hr>
        <div class="span12">
              <?php
              echo form_open($this->uri->uri_string());
              ?>
            <fieldset>

              <div class="clearfix">
                  <label>Photo (Gravatar Email)</label>
                  <div class="input">
                      <?php echo form_error('team_gravatar_email'); ?>
                      <input type="text" name = "team_gravatar_email" value="<?php echo $team_gravatar_email; ?>"/>
                  </div>
                  <a id = "setup_gravatar" href= "https://en.gravatar.com/site/signup/">
                   Click here to set up a Gravatar
                   </a>
              </div>
              <div class="clearfix">
                  <label>Description</label>
                  <div class="input">
                      <?php echo form_error('description'); ?>
                      <textarea name="description" cols="40" rows="5" ><?php echo $description; ?></textarea></br>
                  </div>
              </div>
            </fieldset>
                <div class="actions">
                  <input type="submit" value = "Save and Finish" class = "btn btn-margin"/></form>
              </div>
        </div>
    </div>
 </div>
