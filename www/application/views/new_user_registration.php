<?php
if ($use_username) {
  $username = array(
    'name'  => 'username',
    'id'  => 'username',
    'value' => set_value('username'),
    'maxlength' => $this->config->item('username_max_length', 'tank_auth'),
    'size'  => 30,
  );
}
$email = array(
  'name'  => 'email',
  'id'  => 'email',
  'value' => set_value('email'),
  'maxlength' => 80,
  'size'  => 30,
);
$password = array(
  'name'  => 'password',
  'id'  => 'password',
  'value' => set_value('password'),
  'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
  'size'  => 30,
);
$confirm_password = array(
  'name'  => 'confirm_password',
  'id'  => 'confirm_password',
  'value' => set_value('confirm_password'),
  'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
  'size'  => 30,
);
$captcha = array(
  'name'  => 'captcha',
  'id'  => 'captcha',
  'maxlength' => 8,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>

<div class="page-header">
 <h1>Humans vs Zombies <small> Dead of Winter </small></h1>
</div>
<div class = "row" >
   <div class="main">
      <div id = "title2">
      <h2>New User Registration </h2>
      </div>
      <div id = "fields">
        <form>
          <fieldset>

          <?php
          if(!form_error($username['name'])){
            echo '
                <div class="clearfix">
                <label for="xlInput"> Username </label>
                <div class="input"> '. form_input($username) . 
                '</div>
                </div><!-- /clearfix --> ';
          }
          else{
            echo '
                <div class="clearfix error">
                <label for="errorInput"> Username </label>
                <div class="input"> ' . form_input($username) .
            
                '<span class="help-inline">' .form_error($username['name']). '</span>
                </div>
                </div><!-- /clearfix -->';
          }
          //---------------------------------------------
          if(!form_error($email['name'])){
            echo '
                <div class="clearfix">
                <label for="xlInput"> Email </label>
                <div class="input"> '. form_input($email) . 
                '</div>
                </div><!-- /clearfix --> ';
          }
          else{
            echo '
                <div class="clearfix error">
                <label for="errorInput"> Email </label>
                <div class="input"> ' . form_input($email) .
            
                '<span class="help-inline">' .form_error($email['name']). '</span>
                </div>
                </div><!-- /clearfix -->';
          }
          //---------------------------------------------

          if(!form_error($password['name'])){
            echo '
                <div class="clearfix">
                <label for="xlInput"> Password </label>
                <div class="input"> '. form_input($password) . 
                '</div>
                </div><!-- /clearfix --> ';
          }
          else{
            echo '
                <div class="clearfix error">
                <label for="errorInput"> Password </label>
                <div class="input"> ' . form_input($password) .
            
                '<span class="help-inline">' .form_error($password['name']). '</span>
                </div>
                </div><!-- /clearfix -->';
          }
          //---------------------------------------------          
          if(!form_error($username['name'])){
            echo '
                <div class="clearfix">
                <label for="xlInput"> Confirm Passowrd </label>
                <div class="input"> '. form_input($confirm_password) . 
                '</div>
                </div><!-- /clearfix --> ';
          }
          else{
            echo '
                <div class="clearfix error">
                <label for="errorInput"> Confirm Password </label>
                <div class="input"> ' . form_input($confirm_password) .
            
                '<span class="help-inline">' .form_error($confirm_password['name']). '</span>
                </div>
                </div><!-- /clearfix -->';
          }


          
          ?>
                   
            </fieldset>
          </form>
      </div>
      <div class = "test">
            <input type="submit" value = "Register" class = "btn success"/>  </form> 
            <?php echo form_close(); ?> 
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






