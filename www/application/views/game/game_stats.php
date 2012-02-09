
<h2> Humans: <?php echo $human_count; ?> Zombies: <?php echo $zombie_count; ?> Starved Zombies: <?php echo $zombie_count; ?></h2>
<div id = "chart1"></div>

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


