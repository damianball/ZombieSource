
    <div class="fill">
      <div class="container">
        <a class="brand">ZombieSource</a>
          <ul class="nav">
            <li><a href = "<?php echo site_url("home"); ?> ">Home</a></li>
            <li><a href = "<?php echo site_url("profile"); ?> ">Profile</a></li>
            <li><a href = "<?php echo site_url("Game"); ?> ">Game</a></li>

          </ul>
            <a href="<?php echo site_url("auth/logout"); ?>" class="btn success pull-right"> <?php echo $this->tank_auth->get_username(); ?>  - sign out </a>
        </div>
      </div>
    </div>
    <div id = topline></div>

