<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Braaaaains</title>
  <!--<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
   -->
   <link rel="stylesheet/less" type="text/css" href="<?php echo base_url();?>css/bootstrap_zombies/less/bootstrap.less">
   <link rel="stylesheet/less" type="text/css" href="<?php echo base_url();?>css/bootswatch.less">
   
   
  <script type="text/javascript" src="<?php echo base_url();?>js/less.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 
 </head>
  <body>

    <div class = "container">
   
  	<div class="row">
  	<div class="well span8 offset1">
  		<h1> ZombieSource </h1>
  		<h3><small>Forgot your Password?  Zombie ate your brains? No fear!  Just type in your email!</small></h3>
  	</div>
  </div>

<div class="row">
<div class="well span4 offset3">
<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>

	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<div class="error-text"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>
	</tr>
  <br>
<?php echo form_submit('reset', 'Get a new password'); ?>
<?php echo form_close(); ?>


</div>
</div>
      </div>
    <hr>


  </body>
</html>