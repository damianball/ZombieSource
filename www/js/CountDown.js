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
