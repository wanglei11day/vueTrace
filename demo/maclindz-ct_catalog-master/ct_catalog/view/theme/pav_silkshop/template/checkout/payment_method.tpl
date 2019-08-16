<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method;?></p>
<?php foreach ($payment_methods as $payment_method) { ?>
<?php $curcode = $payment_method['code']; ?>
<?php
$class='';

if($paycode != '')
{
    if($paycode == $curcode)
    {

      if($curcode=='cmbpay'){
        $class= 'hidden-md hidden-lg';
      }else{
        $class='';
      }

    }
    else
    {
    $class= 'hidden-xs';
    if($curcode=='weih5pay'){
        if($is_weixin){
          $class= 'hidden-xs hidden-md hidden-lg';
        }else{
          $class= 'hidden-xs hidden-md hidden-lg';
        }

      }
      if($curcode=='weixin_native'){
        if($is_weixin){
          $class= 'hidden-md hidden-lg';
        }else{
          $class= 'hidden-xs';
        }
      }

    }
}
else
{
  if($curcode=='cmbpay'){
    $class= 'hidden-md hidden-lg';
  }
  if($curcode=='weih5pay'){
    if($is_weixin){
      $class= 'hidden-xs hidden-md hidden-lg';
    }else{
      $class= 'hidden-md hidden-lg';
    }

  }
  if($curcode=='weixin_native'){
    if($is_weixin){
      $class= 'hidden-md hidden-lg';
    }else{
      $class= 'hidden-xs';
    }
  }
}




?>
<div class="radio payment-radio <?php echo $class ?> ">
  <label>
    <?php if ($payment_method['code'] == $paycode) { ?>
    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" />
    <?php } ?>
    <?php echo $payment_method['title']; ?>
    <?php if ($payment_method['terms']) { ?>
    (<?php echo $payment_method['terms']; ?>)
    <?php } ?>
  </label>
</div>
<?php } ?>
<?php } ?>
<div style="border-top: 1px solid #ddd;">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="fapiao_chkbox" id="fapiao_chkbox" value="0"  />
      <?php echo $text_need_fapiao; ?>
    </label>
  </div>
  <div class="form-group" style="display: none;" id="fapiao_div">
    <label class="col-sm-2 control-label" style="height: 40px;padding: 6px 12px;" for="input-column"><span><?php echo $text_fapiao_title; ?></span></label>
    <div class="col-sm-10">
      <input type="text" name="fapiao" value="" placeholder="<?php echo $text_fapiao_title; ?>" id="input-fapiao" class="form-control" />
    </div>
  </div>
</div>
<div style="clear: both;">
<p><strong><?php echo $text_comments; ?></strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"><?php echo $comment; ?></textarea>
</p>
</div>

<?php if ($text_agree) { ?>
<div class="buttons">
  <div class="pull-right"><?php echo $text_agree; ?>
    <?php if ($agree) { ?>
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="agree" value="1" />
    <?php } ?>
    &nbsp;
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } else { ?>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } ?>
<script>

  <?php if(!$paycode) { ?>
    $('.payment-radio').each(function (i,ele) {
        var display =$(ele).css('display');
        if(display=='block'){
            $(ele).find('input').prop("checked",true);
            return false;
        }
    })
    <?php } ?>
   $('input[name=\'fapiao_chkbox\']').on('change', function() {
      chk =this.checked;
      if(chk){
        $("#fapiao_div").show();
      }else{
        $("#fapiao_div").hide();
      }
   });
</script>
