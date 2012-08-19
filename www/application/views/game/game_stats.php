<?php if(!$is_player_in_game){ ?>
<div class="alert fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>You aren't in a game!</strong>
         You can't play until you join a game. Go to the 
         <a href="<?php echo site_url('overview')?>">Game Overview</a> page to join a game. 
    </div>
    <?php } ?>

<h1> <?php echo $game_name; ?>
 <!-- Check if game is closed and style accordingly  -->
<?php 
  if($is_closed){
    echo "<small> (Closed)</small></h1>";
    echo "<script type=\"text/javascript\">";
    echo "$(\".container\")[1].style.opacity = 0.5;";
    echo "</script>";
  }else{
    echo "</h1>";
  }
?>
<hr>
<h2> Game Statistics </h2>
<br>
<div class="row">
    <?php
      $data["slug"] = $url_slug;
      $this->load->view("layouts/game_sidebar", $data);
    ?>   
   
  <div class="row">
        <h4>
        <div class="span2">
           <div class="alert alert-blue"> Players: <?php echo $count; ?> </div>
        </div>
        <div class="span2">
           <div class="alert alert-green"> Humans: <?php echo $human_count; ?> </div>
        </div>
        <div class="span2">
           <div class="alert alert-yellow"> Zombies: <?php echo $zombie_count; ?> </div>
        </div>
        <div class="span3">
           <div class="alert alert-red"> Starved Zombies: <?php echo $starved_zombie_count; ?> </div>
        </div>
     </h4>
    <div class="span9" id="chart1"></div>
  </div>
</div>
  


  <script type="text/javascript">
   var chart;
   $(document).ready(function() {
      chart = new Highcharts.Chart({
         chart: {
            renderTo: 'chart1',
            plotBackgroundColor: "transparent",
         },
         title: {
            text: ""
         },
         tooltip: {
            formatter: function() {
               return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
            }
         },
         plotOptions: {
            pie: {
               allowPointSelect: false,
               cursor: 'pointer',
               dataLabels: {
                  enabled: false
               },
               showInLegend: true
            }
         },
          series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                {
                  name: 'Human',    
                  y: <?php echo $human_count; ?>,
                  color: "#04819e"

                },
               {
                  name: 'Zombie',    
                  y: <?php echo $zombie_count; ?>,
                  color: "#FF4500"

               },
               {
                  name: 'Starved Zombie',    
                  y: <?php echo $starved_zombie_count; ?>,
                  color: "#000 "
               }
            ]
         }]
      });
   });
  </script>


