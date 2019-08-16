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
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_email; ?></p>
      <form action="<?php echo $action; ?>" method="post" id="forgotten-form" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend><?php echo $text_your_email; ?></legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>

            <div class="col-sm-8">
              <input type="text" name="email"  value="<?php echo $telephone; ?>" id="forgotten-input-telphone" class="form-control fix-input" />
            </div>
            <div id="send-code" class="col-sm-2">
              <input type="submit" parent="quick-register" id="forgotten_sendsnsverifycode" value="<?php echo $button_sendverifycode; ?>" style="padding: 4px 12px;" class="btn btn-primary" />
            </div>

          </div>
          <div class="form-group required">
            <label class="control-label" for="forgotten-input-verify-code"><?php echo $entry_quick_verify_code; ?></label>
            <div class="col-sm-10">
              <input type="text" name="verify_code" value="" id="forgotten-input-verify-code" class="form-control" />
            </div>
          </div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" id="btn-continue" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
    $("#forgotten-input-telphone").on('blur',function(e){
        et = $("#forgotten-input-telphone").val();
        et = et.replace(/(^\s*)|(\s*$)/g, "");
        if(et==''){
            return;
        }
        if(et.indexOf("@")>-1){
            $("#forgotten_email_telephone").removeClass();
            $("#forgotten_email_telephone").addClass("col-xs-12 col-md-12");
            $("#forgotten-send-code").hide();
            $("#forgotten-input-verify-code").hide();
        }else{
            $("#forgotten_email_telephone").removeClass();
            $("#forgotten_email_telephone").addClass("col-xs-7 col-md-9");
            $("#forgotten-send-code").show();
            $("#forgotten-verify-code-div").show();
            if(!isNumber2(et)){
                alert("<?php $is_not_number;?>");
            }
        }

    });

    $("#btn-continue").on('click',function(e){
        e.preventDefault();
        et = $("#forgotten-input-telphone").val();
        et = et.replace(/(^\s*)|(\s*$)/g, "");
        if(et.indexOf("@")>-1){
            $("#forgotten-form").submit();
        }else{
            action = $("#forgotten-form").attr('action');
            location = action+'&step=2';
        }
    });
    function isNumber2(value) {
        var patrn = /^[0-9]*$/;
        if (patrn.exec(value) == null || value == "") {
            return false
        } else {
            return true
        }
    }
    $('#forgotten_sendsnsverifycode').on('click', function(e) {
        e.preventDefault();
        var parent = $(this).attr('parent');
        var telephone = $("#"+parent+" #forgotten-input-telphone").val();
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
</script>
<?php echo $footer; ?>