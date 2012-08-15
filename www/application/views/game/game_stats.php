
<h2> Game Statistics </h2>
<hr>
<div class="row">
    <?php
      $data["slug"] = $url_slug;
      $this->load->view("layouts/game_sidebar", $data);
    ?>   
      <h3>
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
         <div class="alert alert-red"> Starved Zombies: <?php echo $zombie_count; ?> </div>
      </div>
   </h3>
  
</div>
<div id = "chart1"></div>


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


