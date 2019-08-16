<div id="modal-quicksignup" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title main-heading"><?php echo $text_signin_register ?></h4>
			</div>
		<div class="modal-body product-info">
		
			<div class="clearfix box-product-infomation tab-v3">
				<ul class="nav nav-tabs" role="tablist">
				
                    <li class="active"><a href="#quick-login" data-toggle="tab"><?php echo $text_login;?></a></li>
                    <li><a href="#quick-register" data-toggle="tab"><?php echo $text_register;?></a></li>
				</ul>
				<div class="tab-content text-left">
					<div class="tab-pane active" id="quick-login">
						<h4 class="modal-title"><?php echo $text_returning ?></h4>
						<span><?php echo $text_returning_customer ?></span>
						
						<div class="form-group required">
							<label class="control-label" for="input-email"><?php echo $entry_email_phone; ?></label>
							<input type="text" name="email" value=""  id="input-email" class="form-control" />
						</div>
						<div class="form-group required">
							<label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
							<input type="password" name="password" value="" id="input-password" class="form-control" />
						</div>
						
						<div class="form-group">
						<button type="button" class="btn btn-primary loginaccount"  data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_login ?></button>
						<?php if($is_weixin){ ?>
						<!--
							<a  class="btn btn-primary"  href="<?php echo $weixinlogin ?>" data-loading-text=""><?php echo $text_weixin ?></a>-->
						<?php } ?>
						
						</div>
						<div class="form-group">
						<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
						</div>
					</div>
					
					
					<div class="tab-pane" id="quick-register">
						<h4 class="modal-title"><?php echo $text_new_customer ?></h4>
						<span><?php echo $text_details; ?></span>
						<div class="form-group required">
							<label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
							<input type="text" name="name" value="" id="input-name" class="form-control" />
						</div>

						<div class="form-group required">
							<label class="control-label" for="input-telephone"><?php echo $entry_email; ?></label>
							<div class="row">
								<div id="email_telephone" class="col-xs-12 col-md-12">
									<input type="text" name="email"  value="" id="input-telephone" class="form-control" />
								</div>

								<div id="send-code" class="col-xs-5 col-md-3" style="text-align: right; display: none;">
									<input type="submit" parent="quick-register" id="quick_sendsnsverifycode" value="<?php echo $button_sendverifycode; ?>" style="padding: 6px 18px;" class="btn btn-primary" />
								</div>
							</div>

						</div>
						<div class="form-group required" id="verify-code-div" style="display: none;">
							<label class="control-label" for="input-verify-code"><?php echo $entry_quick_verify_code; ?></label>
							<input type="text" name="verify_code" value="" id="input-verify-code" class="form-control" />
						</div>
						<div class="form-group required">
							<label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
							<input type="password" name="password" value="" id="register-input-password" class="form-control" />
						</div>
						<div class="form-group required">
							<label class="control-label" for="input-comfirm-password"><?php echo $entry_comfirm_password; ?></label>
							<input type="password" name="comfirm" value="" id="register-input-comfirm-password" class="form-control" />
						</div>
						<?php if ($text_agree) { ?>
						<div class="buttons">
						  <div class="pull-right">
							<input type="checkbox" name="agree" value="1" />&nbsp;<?php echo $text_agree; ?>
							<button type="button" class="btn btn-primary createaccount"  data-loading-text="<?php echo $text_loading; ?>" ><?php echo $button_continue; ?></button>
						  </div>
						</div>
						<?php }else{ ?>
						<div class="buttons">
							<div class="pull-left">
								<button type="button" class="btn btn-primary createaccount" data-loading-text="<?php echo $text_loading; ?>" ><?php echo $button_continue; ?></button>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				
			
				
			</div>
		</div>
		</div>
	</div>
</div>
<style>
.quick_signup{
	cursor:pointer;
}
#modal-quicksignup .form-control{
	height:auto;
}</style>

<script type="text/javascript"><!--
var diy_btn_click = false;
$(document).delegate('.quick_signup', 'click', function(e) {
	
	$('#modal-quicksignup').modal('show');
	var id = this.id;
	if(id=='diy_btn'){
		diy_btn_click = true;
	}else{
		diy_btn_click = false;
	}
});
//--></script>
<script type="text/javascript"><!--
	$('#quick-register input').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#quick-register .createaccount').trigger('click');
		}

	});
    $("#input-telephone").on('blur',function(e){
        et = $("#input-telephone").val();
        et = et.replace(/(^\s*)|(\s*$)/g, "");
        if(et==''){
            return;
        }
        if(et.indexOf("@")>-1){
            $("#email_telephone").removeClass();
            $("#email_telephone").addClass("col-xs-12 col-md-12");
            $("#send-code").hide();
            $("#verify-code-div").hide();
        }else{
            $("#email_telephone").removeClass();
            $("#email_telephone").addClass("col-xs-7 col-md-9");
            $("#send-code").show();
            $("#verify-code-div").show();
            if(!isNumber1(et)){
                alert("<?php $is_not_number;?>");
            }
        }

    });

    function isNumber1(value) {
        var patrn = /^[0-9]*$/;
        if (patrn.exec(value) == null || value == "") {
            return false
        } else {
            return true
        }
    }
    $('#quick_sendsnsverifycode').on('click', function(e) {
        e.preventDefault();
        var parent = $(this).attr('parent');
        var telephone = $("#"+parent+" #input-telephone").val();
        var node = this;

        if(telephone==''){
            alert("<?php echo $tip_message_empty;?>");
            return false;
        }
        $(this).attr('disabled',"true");
        var value=$(this).val();
        $.ajax({
            url: 'index.php?route=tool/api/send_register_vercode',
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
$('#quick-register .createaccount').click(function() {
	$.ajax({
		url: 'index.php?route=common/quicksignup/register',
		type: 'post',
		data: $('#quick-register input[type=\'text\'], #quick-register input[type=\'password\'], #quick-register input[type=\'checkbox\']:checked'),
		dataType: 'json',
		beforeSend: function() {
			$('#quick-register .createaccount').button('loading');
			$('#modal-quicksignup .alert-danger').remove();
		},
		complete: function() {
			$('#quick-register .createaccount').button('reset');
		},
		success: function(json) {
			$('#modal-quicksignup .form-group').removeClass('has-error');
			
			if(json['islogged']){
				 window.location.href="index.php?route=account/account";
			}
			if (json['error_name']) {
				$('#quick-register #input-name').parent().addClass('has-error');
				$('#quick-register #input-name').focus();
			}
			if (json['error_email']) {
				$('#quick-register #input-email').parent().addClass('has-error');
				$('#quick-register #input-email').focus();
			}
			if (json['error_telephone']) {
				$('#quick-register #input-telephone').parent().addClass('has-error');
				$('#quick-register #input-telephone').focus();
			}
            if (json['error_verify_code']) {
                $('#quick-register #input-verify-code').parent().addClass('has-error');
                $('#quick-register #input-verify-code').focus();
            }
			if (json['error_password']) {
				$('#quick-register #register-input-password').parent().addClass('has-error');
				$('#quick-register #register-input-password').focus();
			}
			if (json['error']) {
				$('#modal-quicksignup .modal-header').after('<div class="alert alert-danger" style="margin:5px;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['now_login']) {
				$('.quick-login').before('<li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li><li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li><li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li><li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li><li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li></ul></li>');
				
				$('.quick-login').remove();
			}
			if (json['success']) {
				$('#modal-quicksignup .main-heading').html(json['heading_title']);
				success = json['text_message'];
				success += '<div class="buttons"><div class="text-right"><a onclick="loacation();" class="btn btn-primary">'+ json['button_continue'] +'</a></div></div>';
				$('#modal-quicksignup .modal-body').html(success);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#quick-login input').on('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#quick-login .loginaccount').trigger('click');
	}
});
$('#quick-login .loginaccount').click(function() {
	$.ajax({
		url: 'index.php?route=common/quicksignup/login',
		type: 'post',
		data: $('#quick-login input[type=\'text\'], #quick-login input[type=\'password\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#quick-login .loginaccount').button('loading');
			$('#modal-quicksignup .alert-danger').remove();
		},
		complete: function() {
			$('#quick-login .loginaccount').button('reset');
		},
		success: function(json) {
			$('#modal-quicksignup .form-group').removeClass('has-error');
			if(json['islogged']){
				 if(json['redirect']){
				 	window.location.href=json['redirect'];
				 }else{
				 	window.location.href="index.php?route=account/account";
				 }
				 
			}
			
			if (json['error']) {
				$('#modal-quicksignup .modal-header').after('<div class="alert alert-danger" style="margin:5px;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				$('#quick-login #input-email').parent().addClass('has-error');
				$('#quick-login #input-password').parent().addClass('has-error');
				$('#quick-login #input-email').focus();
			}
			if(json['success']){
				loacation();
				$('#modal-quicksignup').modal('hide');
			}
			
		}
	});
});
//--></script>
<script type="text/javascript"><!--
function loacation() {
	if(diy_btn_click){
	    $('#button-diy').trigger("click");
	}else{
		location.reload();
	}
	
}
//--></script>