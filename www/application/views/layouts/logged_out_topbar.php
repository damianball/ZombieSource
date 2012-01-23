
    <div class="fill">
      <div class="container">
        <a class="brand">ZombieSource</a>
          <ul class="nav">
            <li><a href = "<?php echo site_url("home"); ?> ">Home</a></li>
          </ul>
          <div class = "basic_link">
              <a href="<?php echo site_url("auth/forgot_password"); ?>" class=" pull-right"> Reset Password </a>
          </div>
          <form action = "<?php echo site_url("auth/login"); ?>" method="post" accept-charset="utf-8" class="pull-right">
            <input <input placeholder="Username" class="input-small" type="text" name="login" value="" id="login" maxlength="80" size="30"  />
            <input <input placeholder="Password" class="input-small" type="password" name="password" value="" id="password" size="30"  />
            <button class="btn success" type="submit">Sign in</button>
          </form>
      </div>
    </div>
    <div id = topline></div>
    