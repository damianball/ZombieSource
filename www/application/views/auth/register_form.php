

<?php
if ($use_username) {
    $username = array(
        'name'  => 'username',
        'id'    => 'username',
        'value' => set_value('username'),
        'maxlength' => $this->config->item('username_max_length', 'tank_auth'),
        'size'  => 30,
    );
}
$email = array(
    'name'  => 'email',
    'id'    => 'email',
    'value' => set_value('email'),
    'maxlength' => 80,
    'size'  => 30,
);
//$emailsuffixoptions = array(
//	'1' => 'vandals.uidaho.edu',
//	'2' => 'uidaho.edu'
//);
$password = array(
    'name'  => 'password',
    'id'    => 'password',
    'value' => set_value('password'),
    'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
    'size'  => 30,
);
$confirm_password = array(
    'name'  => 'confirm_password',
    'id'    => 'confirm_password',
    'value' => set_value('confirm_password'),
    'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
    'size'  => 30,
);
$captcha = array(
    'name'  => 'captcha',
    'id'    => 'captcha',
    'maxlength' => 8,
);
?>

<div class="page-header">
 <h1>WSU Zombies</h1>
</div>
<div class = "row" >
   <div class="main">
    <div class="alert fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Did you play in Dead of Winter?</strong>
         Then you're already registered!
    </div>
      <div class="row">
      <div class="span12">
      <div class="span7">
      <div id = "fields">
          <fieldset>

            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
            <!-- still working on this! Works, but no red -->
                  <legend>User Registration</legend>
                <?php if ($use_username) { ?>
                <tr>
                    <td><?php echo form_label('Username', $username['id']); ?></td>
                    <td><?php echo form_input($username); ?></td>
                    <div class="error-text"><?php 
                        if(isset($errors["username"])){
                            echo $errors["username"];
                        }
                        echo form_error($username['name']); ?>
                    </div>
                </tr>
                <?php } ?>
                <tr>
                    <td><?php echo form_label('Email Address', $email['id']); ?></td>
                    <td><?php echo form_input($email); ?> <? //echo form_dropdown('emailsuffix', $emailsuffixoptions, '1'); ?></td>
                    <div class="error-text"><?php 
                        if(isset($errors["email"])){
                            echo $errors["email"];
                        }
                        echo form_error($email['name']); ?>
                  </div>
                </tr>
                <tr>
                    <td><?php echo form_label('Password', $password['id']); ?></td>
                    <td><?php echo form_password($password); ?></td>
                    <div class="error-text"><?php echo form_error($password['name']); ?>
                    </div>
                </tr>
                <tr>
                    <td><?php echo form_label('Confirm Password', $confirm_password['id']); ?></td>
                    <td><?php echo form_password($confirm_password); ?></td>
                    <div class="error-text"><?php echo form_error($confirm_password['name']); ?>
                    </div>
                </tr>

                <?php if ($captcha_registration) {
                    if ($use_recaptcha) { ?>
                <tr>
                    <td colspan="2">
                        <div id="recaptcha_image"></div>
                    </td>
                    <td>
                        <a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
                        <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
                        <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="recaptcha_only_if_image">Enter the words above</div>
                        <div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
                    </td>
                    <td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
                    <div class="error-text"><?php echo form_error('recaptcha_response_field'); ?>
                    </div>
                    <?php echo $recaptcha_html; ?>
                </tr>
                <?php } else { ?>
                <tr>
                    <td colspan="3">
                        <p>Enter the code exactly as it appears:</p>
                        <?php echo $captcha_html; ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
                    <td><?php echo form_input($captcha); ?></td>
                    <div class="error-text"><?php echo form_error($captcha['name']); ?>
                    </div>
                </tr>
                <?php }
                } ?>
            </table>
            <br>
              <div id = "register_button">
                    <input type="submit" value = "Register" class = "btn"/>  </form>
                    <?php echo form_close(); ?>
              </div>
          
            </fieldset>
      </div>
   </div>
   <div class="span4">
        <div class="well">
        <?php $this->load->view("layouts/gameinfo"); ?>
        </div>
   </div>
   </div>
   </div>
</div>


<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-alert.js"></script>
