

      <h2> Register a Kill </h2>
         <?php //echo form_open("http://postcatcher.in/catchers/4f1182876366150100000004"); 
             echo form_open($this->uri->uri_string());
         ?>

         <?php if($form_error != '') print("<div class=\"error\">".$form_error."</div>");?>
      
         <div class="clearfix">
            <label>Human Code</label>
            <div class="input">
              <?php echo form_error('human_code'); ?>
              <input type="text" name="human_code" value="<?php echo set_value('human_code'); ?>"/>
            </div>
        </div> 
      
        <div id = "feed_friends"> Feed friends (optional)  </div>
         <div class="clearfix">
            <label>Username</label>
            <div class="input">
              <?php echo form_error('friend1'); ?>
              <input type="text" name="friend1" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source='<?php echo $zombie_list?>'>
            </div>
        </div> 
         <div class="clearfix">
            <label>Username</label>
            <div class="input">
              <?php echo form_error('friend2'); ?>
              <input type="text" name="friend2" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source='<?php echo $zombie_list?>'>
            </div>
         </div>         
          <div class="clearfix">
            <label>Username</label>
            <div class="input">
              <?php echo form_error('friend3'); ?>
              <input type="text" name="friend3" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source='<?php echo $zombie_list?>'>
            </div>
         </div>  
        <div class="actions">
          <input type="submit" value = "Submit kill" class = "btn success"/></form> 
        </div>


 <script type="text/javascript">
  $('.typeahead').typeahead()
  </script>

