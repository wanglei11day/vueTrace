<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="accc_form" class="form-horizontal">
        <fieldset id="accc_aff_data">
          <legend><?php echo $text_your_info; ?></legend>
    
		  <?php if ($use_website) { ?>
          <div class="form-group<?php if ($website_required) { ?> required<?php } ?>">
            <label class="col-sm-2 control-label" for="input-website"><?php echo $entry_website; ?></label>
            <div class="col-sm-10">
			<?php if ($website_textarea) { ?>
			  <textarea name="website" id="input-website" class="form-control"><?php echo $website; ?></textarea>
			<?php } else { ?>
              <input type="text" name="website" value="<?php echo $website; ?>" placeholder="<?php echo $entry_website; ?>" id="input-website" class="form-control" />
			<?php } ?>
			<?php if ($website_required) { ?><div class="err" id="error_website"><?php echo $error_website; ?></div><?php } ?>
            </div>
          </div>
		  <?php } else { ?>
		  <input type="hidden" name="website" value="<?php echo $website; ?>" />
		  <?php } ?>
		  <?php if ($use_tax) { ?>
          <div class="form-group<?php if ($tax_required) { ?> required<?php } ?>">
            <label class="col-sm-2 control-label" for="input-tax"><?php echo $entry_tax; ?></label>
            <div class="col-sm-10">
              <input type="text" name="tax" value="<?php echo $tax; ?>" placeholder="<?php echo $entry_tax; ?>" id="input-tax" class="form-control" />
     		  <?php if ($tax_required) { ?><div class="err" id="error_tax"><?php echo $error_tax; ?></div><?php } ?>			  
            </div>
          </div>
		  <?php } else { ?>
		  <input type="hidden" name="tax" value="<?php echo $tax; ?>" />
		  <?php } ?>
		  <?php if ($use_payment) { ?>
          <div class="form-group<?php if ($payment_required) { ?> required<?php } ?>">
            <label class="col-sm-2 control-label"><?php echo $entry_payment; ?></label>
            <div class="col-sm-10">
			  <?php if ($use_cheque) { ?>
              <div class="radio">
                <label>
                  <?php if ($payment == 'cheque') { ?>
                  <input type="radio" name="payment" value="cheque" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="cheque" />
                  <?php } ?>
                  <?php echo $text_cheque; ?></label>
              </div>
			  <?php } ?>
			  <?php if ($use_paypal) { ?>
              <div class="radio">
                <label>
                  <?php if ($payment == 'paypal') { ?>
                  <input type="radio" name="payment" value="paypal" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="paypal" />
                  <?php } ?>
                  <?php echo $text_paypal; ?></label>
              </div>
			  <?php } ?>
			  <?php if ($use_bank) { ?>
              <div class="radio">
                <label>
                  <?php if ($payment == 'bank') { ?>
                  <input type="radio" name="payment" value="bank" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="bank" />
                  <?php } ?>
                  <?php echo $text_bank; ?></label>
              </div>
			  <?php } ?>
			  <?php if ($payment_required) { ?><div class="err" id="error_payment"><?php echo $error_payment; ?></div><?php } ?>			  
            </div>
          </div>		  
		  <?php if ($use_cheque) { ?>
          <div class="form-group payment<?php if ($payment_required) { ?> required<?php } ?>" id="payment-cheque">
            <label class="col-sm-2 control-label" for="input-cheque"><?php echo $entry_cheque; ?></label>
            <div class="col-sm-10">
              <input type="text" name="cheque" value="<?php echo $cheque; ?>" placeholder="<?php echo $entry_cheque; ?>" id="input-cheque" class="form-control" />
			<?php if ($payment_required) { ?><div class="err" id="error_cheque"><?php echo $error_cheque; ?></div><?php } ?>			  
            </div>
          </div>
		  <?php } else { ?>
		  <input type="hidden" name="cheque" value="" />
		  <?php } ?>
		  <?php if ($use_paypal) { ?>
          <div class="form-group payment<?php if ($payment_required) { ?> required<?php } ?>" id="payment-paypal">
            <label class="col-sm-2 control-label" for="input-paypal"><?php echo $entry_paypal; ?></label>
            <div class="col-sm-10">
              <input type="text" name="paypal" value="<?php echo $paypal; ?>" placeholder="<?php echo $entry_paypal; ?>" id="input-paypal" class="form-control" />
			<?php if ($payment_required) { ?><div class="err" id="error_paypal"><?php echo $error_paypal; ?></div><?php } ?>			  
            </div>
          </div>
		  <?php } else { ?>
		  <input type="hidden" name="paypal" value="" />
		  <?php } ?>
		  <?php if ($use_bank) { ?>
          <div class="payment" id="payment-bank">
			<?php if ($use_bank_name) { ?>
            <div class="form-group<?php if ($payment_required) { ?> required<?php } ?>">
              <label class="col-sm-2 control-label" for="input-bank-name"><?php echo $entry_bank_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bank_name; ?>" id="input-bank-name" class="form-control" />
				<?php if ($payment_required) { ?><div class="err" id="error_bank_name"><?php echo $error_bank_name; ?></div><?php } ?>
              </div>
            </div>
			<?php } else { ?>
		    <input type="hidden" name="bank_name" value="" />
		    <?php } ?>
			<?php if ($use_bank_branch_number) { ?>
            <div class="form-group<?php if ($payment_required) { ?> required<?php } ?>">
              <label class="col-sm-2 control-label" for="input-bank-branch-number"><?php echo $entry_bank_branch_number; ?></label>
              <div class="col-sm-10">
                <input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" placeholder="<?php echo $entry_bank_branch_number; ?>" id="input-bank-branch-number" class="form-control" />
				<?php if ($payment_required) { ?><div class="err" id="error_bank_branch_number"><?php echo $error_bank_branch_number; ?></div><?php } ?>
              </div>
            </div>
			<?php } else { ?>
		    <input type="hidden" name="bank_branch_number" value="" />
		    <?php } ?>
			<?php if ($use_bank_swift_code) { ?>
            <div class="form-group<?php if ($payment_required) { ?> required<?php } ?>">
              <label class="col-sm-2 control-label" for="input-bank-swift-code"><?php echo $entry_bank_swift_code; ?></label>
              <div class="col-sm-10">
                <input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" placeholder="<?php echo $entry_bank_swift_code; ?>" id="input-bank-swift-code" class="form-control" />
				<?php if ($payment_required) { ?><div class="err" id="error_bank_swift_code"><?php echo $error_bank_swift_code; ?></div><?php } ?>
              </div>
            </div>
			<?php } else { ?>
		    <input type="hidden" name="bank_swift_code" value="" />
		    <?php } ?>		  
			<?php if ($use_bank_account_name) { ?>
            <div class="form-group<?php if ($payment_required) { ?> required<?php } ?>">
              <label class="col-sm-2 control-label" for="input-bank-account-name"><?php echo $entry_bank_account_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" placeholder="<?php echo $entry_bank_account_name; ?>" id="input-bank-account-name" class="form-control" />
				<?php if ($payment_required) { ?><div class="err" id="error_bank_account_name"><?php echo $error_bank_account_name; ?></div><?php } ?>
              </div>
            </div>
			<?php } else { ?>
		    <input type="hidden" name="bank_account_name" value="" />
		    <?php } ?>		  
			<?php if ($use_bank_account_number) { ?>
            <div class="form-group<?php if ($payment_required) { ?> required<?php } ?>">
              <label class="col-sm-2 control-label" for="input-bank-account-number"><?php echo $entry_bank_account_number; ?></label>
              <div class="col-sm-10">
                <input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" placeholder="<?php echo $entry_bank_account_number; ?>" id="input-bank-account-number" class="form-control" />
				<?php if ($payment_required) { ?><div class="err" id="error_bank_account_number"><?php echo $error_bank_account_number; ?></div><?php } ?>
              </div>
            </div>
			<?php } else { ?>
		    <input type="hidden" name="bank_account_number" value="" />
		    <?php } ?>		  
          </div>
		  <?php } else { ?>
		<input type="hidden" name="bank_name" value="" />
		<input type="hidden" name="bank_branch_number" value="" />
		<input type="hidden" name="bank_swift_code" value="" />
		<input type="hidden" name="bank_account_name" value="" />
		<input type="hidden" name="bank_account_number" value="" />				
		<?php } ?>
		  <?php } else { ?>
		<input type="hidden" name="payment" value="" />
		<input type="hidden" name="cheque" value="" />
		<input type="hidden" name="paypal" value="" />
		<input type="hidden" name="bank_name" value="" />
		<input type="hidden" name="bank_branch_number" value="" />
		<input type="hidden" name="bank_swift_code" value="" />
		<input type="hidden" name="bank_account_name" value="" />
		<input type="hidden" name="bank_account_number" value="" />		
		<?php } ?>
        </fieldset>    
    <div class="buttons">
      <div class="pull-right">
	  <?php if ($text_agree) { ?>
		<?php echo $text_agree; ?> 
		<div class="err" style="display:none;" id="error-agree"></div>
        <input type="checkbox" name="confirm" value="1" <?php if ($agree) { ?>checked="checked"<?php } ?> /> 
	  <?php } ?>
		<input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
      </div>
    </div>
</form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--

$('.err').hide();

<?php if ($use_payment) { ?>
$('input[name=\'payment\']').on('change', function() {
	$('.payment').hide();
	
	$('#payment-' + this.value).show();
});

$('input[name=\'payment\']:checked').trigger('change');
<?php } ?>

$('#accc_form').submit(function() {
	$('.err').hide();
	$('.err').addClass('text-danger');
	var _error = false;
<?php
	if($error_agree) { ?>
	if(!$('[name="confirm"]').is(':checked')) {		
		$('#error-agree').text('<?php echo $error_agree; ?>');
		$('#error-agree').show();
		_error = true;
	}	
<?php } ?>
<?php
	if ($website_required) { ?>
	if($('[name="website"]').val().replace(/[\n\r\s]+/g, '').length < 1) {		
		$('#error_website').show();
		_error = true;
	}
<?php } ?>	
<?php
	if ($tax_required) { ?>
	if($('[name="tax"]').val().replace(/[\n\r\s]+/g, '').length < 1) {		
		$('#error_tax').show();
		_error = true;
	}
<?php } ?>	
<?php
	if ($payment_required) { ?>
	var _payment = $('[name="payment"]:checked').val();
	if(_payment.replace(/[\n\r\s]+/g, '').length < 1) {		
		$('#error_payment').show();
		_error = true;
	} else {
		$('[name^="'+_payment+'"]').each(function() {	
			if($(this).is(':hidden')) return;
			if($(this).val().replace(/[\n\r\s]+/g, '').length < 1) {				
				$('#error_'+$(this).attr('name')).show();
				_error = true;			
			}		
		});
	}	
<?php } ?>		
	return !_error;
});	

<?php
	foreach ($errors as $field => $error) {
		if ($error) { ?>
$('#error_<?php echo $field;?>').show();
<?php 	} 
	} ?>	
//--></script> 
<?php //+mod by yp tracking input start
	if(isset($tracking_input_show) && $tracking_input_show) {?>
<script type="text/javascript"><!--
	var _TI_ = <?php echo $tracking_input_settings_json;?>;
//--></script>
<?php } //+mod by yp tracking input end 
?>
<?php echo $footer; ?>