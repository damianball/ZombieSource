display_tweets($);
//setInterval(function(){display_tweets($)}, 30000);

function display_tweets($) {
  $.ajax({
            type: 'GET',
            url: newsfeed_url, //'http://search.twitter.com/search.json?q=' + twtr_search,
            dataType: 'json',
            success: function(data){
              console.log(data);
              $.each(data, function() {
                // console.log(data);

              var text = this.message_text;
              if(text.charAt(0) != '@') {
                // construct tweet and add append to our #tweets div
                var tweet = $("<tr></tr>").addClass('tweet').html(text);

                // analyse our tweet text and turn urls into working links, hash tags into search links, and @replies into profile links.
                // tweet.html('<td>' + '<a href="http://www.twitter.com/' + this.from_user + '" target="_blank"><img class="twtr-pic span1" src="'
                  // + this.profile_image_url + '" width="48" height="48" /></a>' + '<div class="twtr-text span2">' + this.from_user + '</div>' + '<div class="twtr-text span8">' +
                  tweet.html('<td><div class="twtr-text span12">' +
                  unescape(tweet.html()

                    // .replace(/((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/gi,'<a href="$1">$1</a>')

                    .replace(/(^|\s)#(\w+)/g,'$1<a href="http://search.twitter.com/search?q=%23$2">#$2</a>')

                    .replace(/(^|\s)@(\w+)/g,'$1<a href="http://twitter.com/$2">@$2</a>'))

                  // + '<br /><a href="http://www.twitter.com/' + this.from_user + '/status/' + this.id_str
                  // + '" class="view" target="_blank">' + $.timeSinceTweet(this.date_created) + '</a>
                  + '<br>' + $.timeSinceTweet(this.date_created)

                  + '</div>' + '</td>'

                 )


                .appendTo('#newsfeed')

                .fadeIn()
                .slideDown('slow');

                }

            $('.achievement').tooltip();
              });
            }
          });
}



(function($) {
  $.timeSinceTweet = function(time) {
    var date = new Date(time);
    var diff = ((new Date()).getTime() - date.getTime()) / 1000;
    var day_diff = Math.floor(diff / 86400);

    if (day_diff < 0 || day_diff >= 31 || isNaN(day_diff)) {

      return "a long time ago";
    }

    if(day_diff == 0) {
      if(diff < 60) {
        return Math.ceil(diff) + " seconds ago";
      }
      else if(diff < 120) {
        return "1 minute ago";
      }
      else if(diff < 3600) {
        return Math.floor( diff / 60 ) + " minutes ago";
      }
      else if(diff < 7200) {
        return "1 hour ago";
      }
      else if(diff < 86400) {
        return Math.floor( diff / 3600 ) + " hours ago";
      }
    }

    if(day_diff == 1) {
      return "Yesterday";
    }
    else if(day_diff < 7) {
      return day_diff + " days ago";
    }
    else if(day_diff < 31) {
      return Math.ceil( day_diff / 7 ) + " weeks ago";
    }
    else {
      return Math.ceil(day_diff / 30) + " months ago";
    }
  }
})(jQuery);
          // table_head = document.createElement('thead');
          // table_head.appendChild(document.createElement('tr'))
          // table_head.appendChild(document.createElement('th'))
          // table_body = document.createElement('tbody');

          // row = new Array();
          // var tweets = data['results']
          //   console.log(tweets);
          //   for(i=0; i<tweets.length; i++){
          //     row[i] = document.createElement('tr');
          //     col = document.createElement('td');

          //     var user_image = document.createElement("img");
          //     user_image.setAttribute('src', tweets[i]['profile_image_url']);
          //     user_image.setAttribute('class', 'twtr-pic span1');
          //     col.appendChild(user_image);

          //     user_name_div = document.createElement('div');
          //     user_name_div.setAttribute('class', 'twtr-text span2');
          //     user_name = document.createTextNode(unescape(tweets[i]['from_user']));
          //     user_name_div.innerHTML=tweets[i]['from_user'];
          //     // user_name_div.appendChild(user_name);
          //     col.appendChild(user_name_div);

          //     user_text_div = document.createElement('div');
          //     user_text_div.setAttribute('class', 'twtr-text span8');
          //     user_text = document.createTextNode(unescape(tweets[i]['text']));
          //     user_text_div.innerHTML = tweets[i]['text'];
          //     // user_text_div.appendChild(user_text);
          //     col.appendChild(user_text_div);

          //     row[i].appendChild(col);
          //     table_body.appendChild(row[i]);
          //   }
          //   document.getElementById('newsfeed').appendChild(table_body);

        // }
      // })
// new TWTR.Widget({
//   version: 2,
//   type: 'search',
//   search: <?php echo $twitter_search?>,
//   interval: 20000,
//   title: 'Tweet with <?php echo $twitter_hashtag?> to join in!',
//   width: 'auto',
//   height: 755,
//   theme: {
//     shell: {
//       background: '#DD4814',
//       color: '#ffffff'
//     },
//     tweets: {
//       background: '#ffffff',
//       color: '#444444',
//       links: '#DD4814'
//     }
//   },
//   features: {
//     scrollbar: false,
//     loop: false,
//     live: true,
//     behavior: 'all'
//   },
// }).render().start();
//

