<h2> Much more to come! </h2>

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
                  name: 'Male',    
                  y: <?php echo $male; ?>,
                  color: "#39869B"

                },
               {
                  name: 'Female',    
                  y: <?php echo $female; ?>,
                  color: "#46A1B9"

               },
               {
                  name: 'Other',    
                  y: <?php echo $other; ?>,
                  color: "#7CBBCF"
               },
               {
                  name: 'No Response',    
                  y: <?php echo $noresponse; ?>,
                  color: "#B5D4E0"

               }

            ]
         }]
      });
   });
  </script>


