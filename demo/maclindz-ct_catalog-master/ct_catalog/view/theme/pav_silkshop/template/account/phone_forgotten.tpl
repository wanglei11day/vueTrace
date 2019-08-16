<?php echo $header; ?>
<div class="breadcrumb">
	<div class="container">
		<ul>
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> / </li>
			<?php } ?>
		</ul>
		<h1 class="breadcrumb-heading"><?php echo $heading_title; ?></h1>
	</div>
</div>
<div class="container">

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
	<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
	<?php } ?>
      <h1><?php echo $heading_title; ?></h1>

      <div >


		  <form action="<?php echo $action; ?>" method="post" id="changpassword-form" enctype="multipart/form-data" class="form-horizontal">
			<fieldset>

					<legend><?php  echo $text_your_phone; ?></legend>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="forgotten-input-telephone"><?php echo $entry_telephone; ?></label>

						<div class="col-sm-8">
							<input type="text" name="telephone" value="<?php echo $telephone;?>" placeholder="<?php echo $entry_telephone; ?>" id="forgotten-input-telephone" class="form-control" />
						</div>


						<div class="col-sm-2">

							<div class="pull-right">
								<input type="submit" id="sendsnsverifycode" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
							</div>
						</div>
					</div>

			<legend></legend>
			 <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-phone-telephone"><?php echo $entry_verify_code; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="verify_code" value="" placeholder="<?php echo $entry_verify_code; ?>" id="input-phone-verify-code" class="form-control" />
				</div>
				
			   <input type="hidden" name="synctelephone" id="synctelephone" value=""></input> 
			  
			</div>
			
			 <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-phone-password"><?php echo $entry_password; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-phone-password" class="form-control" />
				</div>

			</div>
			 <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-phone-comfirmpassword"><?php echo $entry_comfirmpassword; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="verify_comfirmpassword" value="" placeholder="<?php echo $entry_comfirmpassword; ?>" id="input-phone-comfirmpassword" class="form-control" />
				</div>


			</div>
				<div class="form-group required">

					<div class="col-sm-12">

						<div class="pull-right">
							<input type="submit" id="changepassword" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
						</div>
					</div>
				</div>
			</fieldset>
			
		  </form>
	  </div>
      
	  
      </div>
    </div>
</div>
<script>

    $('#sendsnsverifycode').on('click', function(e) {
        e.preventDefault();
        var parent = $(this).attr('parent');
        var telephone = $("#forgotten-input-telephone").val();
        var node = this;

        if(telephone==''){
            alert("<?php echo $tip_message_empty;?>");
            return false;
        }
        $(this).attr('disabled',"true");
        var value=$(this).val();
        $.ajax({
            url: 'index.php?route=tool/api/send_passforget_code',
            type: 'post',
            dataType: 'json',
            data: 'telephone=' + telephone ,
            cache: false,
            beforeSend: function() {
                $(node).button('loading');
                $(node).attr('disabled',"true");
            },
            complete: function() {
            },
            success: function(json) {
                if (json['status']!=1) {
                    if(json['status'] == "20006"){
                        alert("<?php echo $error_already_registered;?>");
                    }else{
                        alert(json['info']);
                    }
                    $(node).button('reset');
                    $(node).timer('remove');
                    $(node).val(value);

                }else{
                    alert("<?php echo $tip_message_send_success;?>");
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $(node).button('reset');
                $(node).timer('remove');
                $(node).val(value);
            }
        });
        $(node).timer({
            duration: '60s',
            callback: function() {
                $(node).button('reset');
                $(node).timer('remove');
                $(node).val(value);
            },
            countdown:true,
            repeat: false
        });

        return false;
    });

</script>
</body>
</html>