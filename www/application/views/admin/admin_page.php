
  <div id = "game_panel">
 <div class="alert alert-info"><strong>With great power comes great responsibilty.</strong>
  This is the Moderator Tools page. Undoing changes made on this page is not trivial. So, be careful. </div>

  </br>
  </br>
  </div>
  <div class = "tinyline"></div>
<!--   <div id = "player_panel">
    Search for a player
    <input id ="player_chooser" type="text" class="span3" style="margin: 10px auto;" data-provide="typeahead" data-items="4" data-source='<?php echo $player_list?>'/>
    <button id = "manage_player" class = "btn success"> Manage Player</button>
    <div class ="controls" id = "player_controls" ></div>
  </div> -->

  <?php foreach($player_in_game as $gameid=>$player_list){ ?>
  <div class="row-fluid">

    <div class="well span12">
      <h2><?php echo $game_names[$gameid];?></h2>
      <div class="tabbable"> 
        <ul class="nav nav-tabs">
          <li class="active"><a href="#p1-<?php echo $gameid?>" data-toggle="tab">Manage Players</a></li>
          <li><a href="#p2-<?php echo $gameid?>" data-toggle="tab">Game Settings</a></li>
          <li><a href="#p3-<?php echo $gameid?>" data-toggle="tab">Original Zombies</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="p1-<?php echo $gameid?>">
            <div class="form-horizontal">
              <fieldset>
                <div class="control-group">
                  <div id="player_panel<?php echo $gameid?>">
                    <label class="control-label">Search for a player</label>
                    <div class="controls">
                      <input id ="player_chooser<?php echo $gameid?>" type="text" placeholder="Username or Real Name" data-provide="typeahead" data-items="4" data-source='<?php echo $player_list?>'>
                        OR<br>
                      <input id="gameid<?php echo $gameid?>" type="hidden" value="<? echo $gameid ?>">
                    <input id="humancode_chooser<?php echo $gameid?>" type="text" placeholder="Human Code" />
                      <span class="help-inline">
                        <button id="manage_player<?php echo $gameid?>" class="btn">Manage Player</button>
                      </span>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class ="controls" id = "player_controls<?php echo $gameid?>" ></div>
          </div>
          <div class="tab-pane" id="p2-<?php echo $gameid?>">
            <div>
              <button id="regenerate_zombie_tree<?php echo $gameid?>" class="btn" value="<?php echo $gameid?>">Regenerate Zombie Family Tree</button>
              <button id="a" class="btn" value="<?php echo $gameid?>">Check for Missed Achievements</button>
              <div id="message<?php echo $gameid?>"></div>
            </div>
            <div class="game-state-controls">
              <div class="admin-button-group">
                <div class="control-title"> <h3> Gameplay </h3></div>
                <div class="btn-group" data-toggle="buttons-radio">
                  <button class="btn btn-danger active">Stopped</button>
                  <button class="btn btn-danger">Started</button>
                </div>
              </div>

              <div class="admin-button-group">
                <div class="control-title"> <h3> Registration </h3></div>
                <div class="btn-group" data-toggle="buttons-radio">
                  <button class="btn btn-warning active">Open</button>
                  <button class="btn btn-warning">Closed</button>
                </div>
              </div>
            </div>
          <div class="email-links">
              <h3> Mailing lists </h3>
              <a class="btn" href="/admin/<?php echo $url_slug[$gameid]?>/email_list"> All Players</a>
              <a class="btn" href="/admin/<?php echo $url_slug[$gameid]?>/email_list?type=humans"> Humans </a>
              <a class="btn" href="/admin/<?php echo $url_slug[$gameid]?>/email_list?type=zombies"> Zombies </a>
            </div>
          </div>       

          <div class="tab-pane" id="p3-<?php echo $gameid?>">
            <div class="game-state-controls">
              <div class="admin-button-group">
                <div class="control-title"> <h3> Original Zombies </h3></div>
                <div class="alert alert-info"> OZs are not notifed of their OZ status. Please reach out to them manually and confirm their participation. </p>
                </div>
                <div class="alert alert-danger oz-alert hide <?php echo $gameid?>"> </div>
                <div class="btn-group" data-toggle="buttons-radio">
                  <button class="btn btn-primary active">Hidden   </button>
                  <button class="btn btn-primary">Revealed</button>
                </div>
              </div>
            </div>
            <div class="oz-creator">
              <div class="oz-list<?php echo $gameid?>">
                <div class="list-options">
                  <div class="input-prepend input-append">
                    <div id="create_oz<?php echo $gameid?>" class="btn"> Create OZ </div><input class="span5" type="text" placeholder="Username" data-provide="typeahead" data-items="4" data-source='<?php echo $player_list?>'>
                    <div id="create_random_oz<?php echo $gameid?>" class="btn"> Random OZ From Pool</div>
                  </div>
                </div>
                <div class="oz-table<?php echo $gameid?>">

                </div>

              </div>
              </div>

            </div>

          </div>
        </div>
      </div>


    </div>
  </div>
    <script type="text/javascript">
    $(document).ready(function(){
        $(".oz-alert").fadeOut();

        function load_ozs() {
          $(".oz-table<?php echo $gameid?>").load("admin/original_zombies",
            { 
              gameid: "<?php echo $gameid?>"
            }
          )
        }

        load_ozs();

        $(document).on("click", ".remove_oz<?php echo $gameid?>", function(e){
          var $el = $(e.target);
          var player_id = $el.data('player-id');
          
          $.post('admin/remove_oz',
            {
              player_id: player_id,
            })
            .success(function(data){
              $(".oz-alert").fadeOut();
              load_ozs();
            })
            .fail(function(data){
              var $alert = $(".oz-alert.<?php echo $gameid?>");
              $alert.html(JSON.parse(data.responseText).error);
              $alert.fadeIn();
            })
        });

       $("#create_oz<?php echo $gameid?>").click(function(e) {
          $.post('admin/create_oz',
            {
              username: $(e.target).siblings("input").val(),
              gameid: '<?php echo $gameid?>'
            },
            function(data) {
              $(".oz-alert").fadeOut();
              load_ozs();
            }
          )
          .success(function(data){
            $(".oz-alert").fadeOut();
            load_ozs();
          })
          .fail(function(data){
            var $alert = $(".oz-alert.<?php echo $gameid?>");
            $alert.html(JSON.parse(data.responseText).error);
            $alert.fadeIn();
          });
        });


       $("#create_random_oz<?php echo $gameid?>").click(function(){
          $.post('admin/create_random_oz',{gameid: '<?php echo $gameid?>'})
          .success(function(data){
            $(".oz-alert").fadeOut();
            load_ozs();
          })
          .fail(function(data){
            var $alert = $(".oz-alert.<?php echo $gameid?>");
            $alert.html(JSON.parse(data.responseText).error);
            $alert.fadeIn();
          });
        });

        $("#manage_player<?php echo $gameid?>").click(function(){
            $("#player_controls<?php echo $gameid?>").load('admin/player_controls',
                {player:$('#player_chooser<?php echo $gameid?>').val().split(" - ")[0],
                 human_code:$('#humancode_chooser<?php echo $gameid?>').val(),
                 gameid:$('#gameid<?php echo $gameid?>').val()});
        });
        $("#regenerate_zombie_tree<?php echo $gameid; ?>").click(function(){
            $("#message<?php echo $gameid; ?>").load("admin/regenerate_zombie_tree",
                {gameid:"<?php echo $gameid; ?>"});
        });
        $("#check_missed_achievements<?php echo $gameid; ?>").click(function(){
            $("#message<?php echo $gameid; ?>").load("admin/check_missed_achievements",
                {gameid:"<?php echo $gameid; ?>"});
        });
    });
    </script>
    <?php } ?>


  <div class = "tinyline"></div>




 <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.js"></script>

  <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-typeahead.js"></script>


