

      <h2> Register a Kill </h2>
      <hr>
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
          <div class="clearfix">
            <label>How many hours ago did this tag take place? (optional) </label>
            <div class="input" >
               <?php echo form_error('claimed_tag_time_offset'); ?>
                <select name = "claimed_tag_time_offset">
                    <option></option>
                    <option value = "1800" > 0.5</option>
                    <option value = "3600" >1.0</option>
                    <option value = "5400" >1.5</option>
                    <option value = "7200" >2.0</option>
                    <option value = "9000" >2.5</option>
                    <option value = "10800" >3.0</option>
                    <option value = "12600" >3.5</option>
                    <option value = "14400" >4.0</option>
                </select>
          </div>
        </div>
        <div id = "feed_friends"> Feed friends (optional)  </div>
        <?php
            for($i = 1; $i <= $max_feeds; $i++){
                echo '<div class="clearfix">';
                echo '<label>Zombie Friend '.$i.'</label>';
                echo '<div class="input">';
                echo form_error('zombie_friend_'.$i);
                echo '<input type="text" name="zombie_friend_'.$i.'" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" value="'.set_value('zombie_friend_'.$i).'" data-source='.$zombie_list.'>';
                echo '</div>';
                echo '</div>';
            }

        ?>
        <div class="actions">
          <input type="submit" value = "Submit kill" class = "btn btn-margin"/></form> 
        </div>


 <script type="text/javascript">
  $('.typeahead').typeahead()
  </script>

