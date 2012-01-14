

<div class="page-header">
 <h1>Humans vs Zombies <small> Dead of Winter </small></h1>

</div>

<div class = "row" >
   <div class="main">

      <div id = "title">
      <?php
      if($waiver != "TRUE"){
         echo '<div class="alert-message info">
                  <p><strong>Hey!</strong> Visit your profile to sign the safety waiver </p>
               </div>';
      }
      else {
         echo ' 
               <div class="alert-message success">
                  <p><strong>Hey!</strong> Welcome to our beta, don\'t feel bad if something breaks </p>
               </div>
             ';
          }
      ?>
      </div>
      <div id = "graphbox">
         <div id="info1">
            <div id = "label">
            <h2> Sign Ups: </h2>
            </div>
            <div id = "homepage_data">
               <div id = "graph2" class = "homepage_graph">
                  <span style = "font-size: 50px;"> 62 </span>
               </div>
               <div id = "graph1" class = "homepage_graph"></div>
            </div>
         </div>
         <div id="info2">
         <?php
            if($this->tank_auth->is_logged_in()){
               echo '<div id = "countdown_box">
                        <h3><div id = "countdown"></div><h3>
                     </div>';
            }
            else {
               echo '
                 <div id = "new"> <h2> New here? You should </h2> </div>
                  <div class="linkbutton">
                     <a href="' . site_url("auth/register") . '"> 
                        <button id = test class="btn success"> Register </button>
                     </a>
                  </div>
                   ';
                }
         ?>
         </div>


      </div>
   </div>
   <div class="sidebar">
      <h3>Info</h3>
      <div class = "infoitem">
         <b> Game Play:</b> <br>
         Feb 6th - Feb 12th
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Registration Deadline:</b><br>
         Jan 27th
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Orientation Dates:</b><br>
         Jan 30th - Feb 3rd 
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Contact:</b><br>
         <a href = "mailto:UofIHvZ@gmail.com"> UofIHvZ@gmail.com </a> <br>
         <a href = "http://www.facebook.com/groups/194292097284119/"> Facebook Group </a>
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <a href = "http://www.facebook.com/groups/194292097284119/"> Rules </a>
      </div>
   </div>
</div>


      <script type="text/javascript" language="javascript"> 
      var chart;
      $(document).ready(function() {
         chart = new Highcharts.Chart({
            chart: {
               renderTo: 'graph1',
               plotBackgroundColor: "transparent",
            },
            title: {
               text: ""
            },
            tooltip: {
               formatter: function() {
                  return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
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
                     name: 'male',    
                     y: 60,
                     color: "#FF4500"
                   },
                  {
                     name: 'female',    
                     y: 38,
                     color: "#FF8000"
                  },
                  {
                     name: 'female',    
                     y: 38,
                     color: "#FF2000"
                  },
                  {
                     name: 'other',    
                     y: 2,
                     color: "#FF1000"
                  }

               ]
            }]
         });
      });
      </script>

      <script>
      var end = new Date('6 Feb 2012 00:00:00'); // set expiry date and time..
      var _second = 1000;
      var _minute = _second * 60;
      var _hour = _minute * 60;
      var _day = _hour *24
      var timer;

      function showRemaining()
      {
       var now = new Date();
       var distance = end - now;
       if (distance < 0 ) {
          // handle expiry here..
          clearInterval( timer ); // stop the timer from continuing ..
          alert('Expired'); // alert a message that the timer has expired..
           
          return; // break out of the function so that we do not update the counters with negative values..
       }
       var days = Math.floor(distance / _day);
       var hours = Math.floor( (distance % _day ) / _hour );
       var minutes = Math.floor( (distance % _hour) / _minute );
       var seconds = Math.floor( (distance % _minute) / _second );

       document.getElementById('countdown').innerHTML =  days + ' days, ';
       document.getElementById('countdown').innerHTML += hours+ ' hours ';
       document.getElementById('countdown').innerHTML +='until outbreak ';

      }

      timer = setInterval(showRemaining, 1000);
      </script>

