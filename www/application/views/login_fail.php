<?php
$login = array(
    'name'  => 'login',
    'id'    => 'login',
    'value' => set_value('login'),
    'maxlength' => 80,
    'size'  => 30,
);

    $login_label = 'Login';

$password = array(
    'name'  => 'password',
    'id'    => 'password',
    'size'  => 30,
);
$remember = array(
    'name'  => 'remember',
    'id'    => 'remember',
    'value' => 1,
    'checked'   => set_value('remember'),
    'style' => 'margin:0;padding:0',
);
$captcha = array(
    'name'  => 'captcha',
    'id'    => 'captcha',
    'maxlength' => 8,
);
?>

adsfasdfsd
<table>
    <tr>

        <td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
    </tr>
    <tr>

        <td style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td>
    </tr>

    <tr>
        <td colspan="3">
            <?php echo form_checkbox($remember); ?>
            <?php echo form_label('Remember me', $remember['id']); ?>
            <?php echo anchor('/auth/forgot_password/', 'Forgot password'); ?>
            <?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Register'); ?>
        </td>
    </tr>
</table>
