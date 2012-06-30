    <div class="navbar-inner">
      <div class="container">
        <a class="brand" href="<?php echo site_url("home"); ?> ">ZombieSource</a>
        <div class="nav-collapse">
          <ul class="nav">
            <li><a href = "<?php echo site_url("home"); ?> ">Home</a></li>
          </ul>
          <ul class="nav pull-right">
              <li>
              <div class = "basic_link">
              
                <a href="<?php echo site_url("auth/forgot_password"); ?>"> Reset Password </a>
              
            </div>
            </li>
          </ul>
            
            
              <form action = "<?php echo site_url("auth/login"); ?>" method="post" accept-charset="utf-8" class="navbar-form pull-right" >
                
                  
                  <input placeholder="Username" class="input-small" type="text" name="login" value="" id="login" maxlength="80" size="30" class="span1" />
               
                
                  <input placeholder="Password" class="input-small" type="password" name="password" value="" id="password" size="30"  class="span1"/>
                

                <button class="btn btn-success" type="submit">Sign in</button>
                  
                
              </form>

            
        </div>
      </div>
    </div>
    <div id = topline></div>
    



    