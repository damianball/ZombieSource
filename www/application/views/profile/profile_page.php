
<div class = "row" >
      <h2> Register a Kill </h2>
        <?php //echo form_open("http://postcatcher.in/catchers/4f1182876366150100000004"); 
             echo form_open($this->uri->uri_string())
         ?>

         <div class="clearfix">
            <label>Human Code</label>
            <div class="input">
              <?php echo form_error('sig'); ?>
              <input type="text" name="sig" value="<?php echo set_value('sig'); ?>"/>
            </div>
        </div> 
      
        <div id = "feed_friends"> Feed friends (optional)  </div>
         <div class="clearfix">

            <label>Username</label>
            <div class="input">
              <?php echo form_error('sig'); ?>
              <input type="text" name="sig" value="<?php echo set_value('sig'); ?>"/>
            </div>
        </div> 
         <div class="clearfix">
            <label>Username</label>
            <div class="input">
              <?php echo form_error('sig'); ?>
              <input type="text" name="sig" value="<?php echo set_value('sig'); ?>"/>
            </div>
         </div>         
          <div class="clearfix">
            <label>Username</label>
            <div class="input">
              <?php echo form_error('sig'); ?>
              <input type="text" name="sig" value="<?php echo set_value('sig'); ?>"/>
            </div>
         </div>    
        <div class="actions">
          <input type="submit" value = "Submit kill" class = "btn success"/></form> 
      </div>   

   </div>
   <div class="sidebar">
      <h3>Info</h3>
      <div class = "infoitem">
         <b> Game Play:</b> <br>
         Feb 6th - Feb 12th
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Registration Deadline:</b><br>
         Jan 27th
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Orientation Dates:</b><br>
         Jan 30th - Feb 3rd 
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Contact:</b><br>
         <a href = "mailto:UofIHvZ@gmail.com"> UofIHvZ@gmail.com </a> <br>
         <a href = "http://www.facebook.com/groups/194292097284119/"> Facebook Group </a>
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <a href = "http://www.facebook.com/groups/194292097284119/"> Rules </a>
      </div>
   </div>
</div>



