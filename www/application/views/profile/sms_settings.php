


<div class = "row-fluid sms_faq well span3">


  <h2> Subscription Settings </h2>

  <div class="alert alert-green save_success">
    Settings Saved
  </div>

  <div class="alert alert-red save_fail">
        Invalid Phone!
  </div>


  <div class="clearfix">
      <label><h3>SMS Number</h3> (exactly 10 digits) </label>
      <div class="input">
         <?php echo form_error('phone'); ?>
          <input type="text" name = "phone" value="<?php echo $phone; ?>"/>
      </div>
  </div>

    <div class="clearfix subscription_option">
      <label class="checkbox">   
          <input type="checkbox" <?php echo $daily_updates ? "checked" : "" ?> name="daily_updates" value="1"> <h3> Daily Updates </h3>
      </label>
      <div class ="subscription_description">
        Recieve an update on zombie count every night.
      </div>
    </div>

      <div class="clearfix subscription_option">
      <label class="checkbox">   
          <input type="checkbox" <?php echo $team_updates ? "checked" : "" ?> name="team_updates" value="1"> <h3> Team Updates.</h3>
      </label>
      <div class ="subscription_description">
        Recieve a text if someone on your team gets turned into a zombie!
      </div>
    </div>


      <div class="clearfix subscription_option">
      <label class="checkbox">   
          <input type="checkbox" <?php echo $mission_updates? "checked" : "" ?> name="mission_updates" value="1"> <h3> Mission Updates.</h3>
      </label>    
      <div class ="subscription_description">
        Recieve mission updates from admins.
      </div>
    </div>
  <div class = "row-fluid"> 
    <div class = "btn btn-info btn-margin save_sms_settings" disabled="disabled"> Save </div>
  </div>
</div>



<div class = "row-fluid sms_faq well span3">
 <h2> Commands  </h2> <h3> Text us at: +1 503-766-4978  </h3>
<table cellspacing="20px">
<tr>
<div class = "command_item">
<td><strong>stop</strong></td>
<td>Stop recieving any text messages from Zombie Source.</td>
</div>
</tr>
<tr>
  <div class = "command_item">
<td><strong>start</strong></td>
<td>Resume receiving texts according to your subscription settings.</td>
</div>
</tr>

<tr>
  <div class = "command_item">
<td><strong>tag human_code</strong></td>
<td>    Tag a user by sending the word tag and then their human code.</td>
</div>
</tr>

<tr>
  <div class = "command_item">
<td><strong>stats</strong></td>
<td>Get human and zombie counts at any time.</td>
</div>
</tr>
</table>


</div>
 <script type="text/javascript">

  $(document).ready(function(){
      $(".alert").hide();

      $(".save_sms_settings").attr("disabled", true);

      $('input[name="phone"]').change(function(event){
          $(".save_sms_settings").attr("disabled", false);
          $(".alert").hide();
      })
      
      $('input[name="daily_updates"]').change(function(event){
          $(".save_sms_settings").attr("disabled", false);
          $(".alert").hide();
      })
      $('input[name="team_updates"]').change(function(event){
          $(".save_sms_settings").attr("disabled", false);
          $(".alert").hide();
      })
      $('input[name="mission_updates"]').change(function(event){
          $(".save_sms_settings").attr("disabled", false);
          $(".alert").hide();
      })

    $(document).on("click", ".save_sms_settings", function(event){
      if(!($(".save_sms_settings").attr("disabled") == "disabled")){        
        var data = {};
        data['phone']             = $('[name="phone"]').val();
        data['daily_updates']     = $('[name="daily_updates"]').is(':checked');
        data['team_updates']      = $('[name="team_updates"]').is(':checked');
        data['mission_updates']   = $('[name="mission_updates"]').is(':checked');

        $.ajax({
          url: "save_sms_settings",
          type: "POST",
          data: data,
          success: function(response){
            var response = jQuery.parseJSON(response);
            if(response.phone_success == "false"){
              $(".save_fail").show();
            }else{
              $(".save_success").show();
              $(".save_sms_settings").attr("disabled", true);
            }
          }
        });
      }
    });
  });

  </script>
