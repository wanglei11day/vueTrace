<?php if ($hurrys) { ?>
<p><?php echo $text_hurry_tip; ?></p>
<?php foreach ($hurrys as $key => $item) { ?>
<div class="radio">
  <label>
    <?php if ($hurry == $key) { ?>
    <input type="radio" name="hurry" value="<?php echo $key; ?>" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="hurry" value="<?php echo $key; ?>" />
    <?php } ?>
    <?php echo $item; ?>
  
  </label>
</div>
<?php } ?>
<?php }else{ ?>
<div class="tip">
  <p><?php echo $text_tip_rush_service; ?></p>
</div>
<?php } ?>


<div class="buttons">
  <div class="pull-right">
   <input type="button" value="<?php echo $button_continue; ?>" id="button-hurry" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
   </div>
</div>


