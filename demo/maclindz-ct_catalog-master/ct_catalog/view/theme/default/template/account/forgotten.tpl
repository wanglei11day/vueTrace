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
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_email; ?></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend><?php echo $text_your_email; ?></legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>

            <div class="row" style="margin: 0px;">
              <div id="email_telephone" class="col-xs-12 col-md-12" style="width: 60%">
                <input type="text" name="email"  value="" id="forgotten-input-telphone" class="form-control fix-input" />
              </div>

              <div id="send-code" class="col-xs-5 col-md-3" style="text-align: right; display: none; width:40%;">
                <input type="submit" parent="quick-register" id="quick_sendsnsverifycode" value="<?php echo $button_sendverifycode; ?>" style="padding: 4px 12px;" class="btn btn-primary" />
              </div>
            </div>
          </div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
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