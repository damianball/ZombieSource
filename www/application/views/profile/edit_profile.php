<div class="page-header">
  <h1>WSU Zombies</h1>
</div>
<div class="row">
    <div class="span10">

      <h2> Update Profile Information </h2>
        <div class="span12">
            <?php
             echo form_open($this->uri->uri_string());
             echo $edit_profile_fields;             
            ?>
              <div class="actions">
                  <input type="submit" value = "Save and Finish" class = "btn btn-margin"/>
              </div>
            </form>
        </div>
    </div>
 </div>
