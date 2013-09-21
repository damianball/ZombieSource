<table class="table table-striped">
<?php foreach($original_zombies as $oz){ ?>
  <tr>
  <td class="list-remove">
    <div data-player-id="<?php echo $oz->getPlayerID() ?>" class="remove_oz<?php echo $gameid?> btn btn-danger btn-mini">x</div>
  </td>
  <td><?php echo $oz->getUser()->getUsername()?></td>
  <td><?php echo $oz->getUser()->getEmail()?></td>
  <td><?php echo $oz->getUser()->getData('phone')?></td>
  </tr>
<?php } ?>

</table>