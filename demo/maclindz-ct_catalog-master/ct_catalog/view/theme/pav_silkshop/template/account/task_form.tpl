<?php echo $header; ?>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> /</li>
            <?php } ?>
        </ul>
        
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
    <div id="content" class="<?php echo $class; ?>"> <?php echo $content_top; ?>
      <h1><?php echo $text_edit_task; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
            <input type="hidden" name="back_id" value="<?php echo $back_id;?>"/>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_priority; ?></label>
            <div class="col-sm-10">

              <select name="priority" class="form-control">
                 <?php foreach ($priorities as $pri) {  ?>
                    <?php if($pri['id'] == $priority) { ?>
                        <option selected="selected" value="<?php echo $pri['id']; ?>"><?php echo $pri['name']; ?></option>
                   <?php }else{ ?>
                         <option  value="<?php echo $pri['id']; ?>"><?php echo $pri['name']; ?></option>
                   <?php } ?>

                 <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>

           <div class="form-group ">
            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo $description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
            
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-tag"><?php echo $entry_tag; ?></label>
            <div class="col-sm-10">
              <input type="text" name="tag" value="<?php echo $tag; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag" class="form-control" />
              <?php if ($error_tag) { ?>
              <div class="text-danger"><?php echo $error_tag; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required" style="display: <?php echo $display;?>">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text"  name="email" <?php if($readonly) { ?> readonly="readonly" <?php } ?>value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />

                <input type="hidden" name="department_id" value="<?php echo $department_id;?>" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required" style="display: <?php echo $display;?>">
            <label class="col-sm-2 control-label" for="input-task-1"><?php echo $entry_customer_creation_id; ?></label>
            <div class="col-sm-10">
              <?php if($display) { ?>
                 <input type="hidden" name="customer_creation_id" value="<?php echo $customer_creation_id; ?>" />
              <?php }else{ ?>
                <select name="customer_creation_id" id="input-project" class="form-control"  >
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($projects as $project) { ?>
                  <?php if ($project['customer_creation_id'] == $customer_creation_id) { ?>
                  <option value="<?php echo $project['customer_creation_id']; ?>" selected="selected"><?php echo $project['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $project['customer_creation_id']; ?>"><?php echo $project['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
             <?php  } ?>
             
              
              <?php if ($error_project) { ?>
              <div class="text-danger"><?php echo $error_project; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-task-2"><?php echo $entry_date_end; ?></label>
            <div class="col-sm-10">
              <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" id="input-task-2" class="form-control" />
            </div>
          </div>




          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort" value="<?php echo $sort; ?>" placeholder="<?php echo $entry_sort; ?>" id="input-sort" class="form-control" />
              <?php if ($error_sort) { ?>
              <div class="text-danger"><?php echo $error_sort; ?></div>
              <?php } ?>
            </div>
          </div>

        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">

              <select name="status" class="form-control">
                 <?php foreach ($statuss as $pri) {  ?>
                    <?php if($pri['id'] == $status) { ?>
                        <option selected="selected" value="<?php echo $pri['id']; ?>"><?php echo $pri['name']; ?></option>
                   <?php }else{ ?>
                         <option  value="<?php echo $pri['id']; ?>"><?php echo $pri['name']; ?></option>
                   <?php } ?>

                 <?php } ?>
              </select>
            </div>
          </div>
         
         
         
        
         
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
// Sort the custom fields
$('.form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('.form-group').length) {
		$('.form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('.form-group').length) {
		$('.form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('.form-group').length) {
		$('.form-group:first').before(this);
	}
});
//--></script>
<script type="text/javascript"><!--
$('button[id^=\'button-custom-field\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});
//--></script>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
			  		}

			  		html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
    $('select[name=\'country_id\']').trigger('change');
$(document).ready(function() {

    select_sup = false;
    // Image Manager
    $('#button-department').on('click', function(e) {
        select_sup = false;
        $('#modal-image').remove();

        $.ajax({
            url: 'index.php?route=common/department',
            dataType: 'html',
            beforeSend: function() {
                $('#button-department i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#button-department').prop('disabled', true);
            },
            complete: function() {
                $('#button-department i').replaceWith('<i class="fa fa-pencil"></i>');
                $('#button-department').prop('disabled', false);
            },
            success: function(html) {
                $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                $('#modal-image').modal('show');
            }
        });
    });

    $('#button-select-supervise').on('click', function(e) {
        select_sup = true;
        $('#modal-image').remove();

        $.ajax({
            url: 'index.php?route=common/department',
            dataType: 'html',
            beforeSend: function() {
                $('#button-department i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#button-department').prop('disabled', true);
            },
            complete: function() {
                $('#button-department i').replaceWith('<i class="fa fa-pencil"></i>');
                $('#button-department').prop('disabled', false);
            },
            success: function(html) {
                $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                $('#modal-image').modal('show');
            }
        });


    });

})

//--></script>
<?php echo $footer; ?>
