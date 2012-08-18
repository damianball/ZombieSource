
<?php
// Essentially dump the latest tumblr post tagged with $tumblr_info_tag wherever this view is
$tumblr_username = $this->config->item('tumblr_username');
$tumblr_api_key = $this->config->item('tumblr_api_key');
$tumblr_info_tag = $this->config->item('tumblr_info_tag');
?>

<div id="gameInfo">
Loading game information...
</div>

<script>
$(document).ready(function() {
$.ajax({url: "http://api.tumblr.com/v2/blog/<?php echo $tumblr_username;?>.tumblr.com/posts?api_key=<?php echo $tumblr_api_key;?>&tag=<?php echo $tumblr_info_tag;?>",
        dataType: "jsonp",
        jsonp: 'jsonp',
        success: function(data){
            $("#gameInfo").html(data.response.posts[0].body);
    }})
});
</script>
