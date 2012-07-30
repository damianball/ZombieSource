
    <!--<div class="fill">
      <div class="container">
        -->
    <div class="navbar-inner">
      <div class="container">
        <a class="brand" href="<?php echo site_url("home"); ?> ">ZombieSource</a>
        <div class="nav-collapse">
          <ul class="nav">
            <li><a href = "<?php echo site_url("home"); ?> ">Home</a></li>
            <li><a href = "<?php echo site_url("profile"); ?> ">Profile</a></li>
            <li><a href = "<?php echo site_url("game"); ?> ">Game</a></li>
             <?php  
                 if($this->tank_auth->is_moderator()){
                   echo "<li><a href = ";
                   echo site_url("admin");
                   echo ">Moderator</a></li>";
                 }
             ?> 
          </ul>
            <a href="<?php echo site_url("auth/logout"); ?>" id = "signout" class="btn success pull-right"> <?php echo $this->tank_auth->get_username(); ?>  - sign out </a>
        </div>
      </div>
    </div>
    <div id = topline></div>


          

                     