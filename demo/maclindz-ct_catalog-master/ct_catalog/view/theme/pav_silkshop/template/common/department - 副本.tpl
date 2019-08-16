<style type="text/css">

  .filemanager-images .image-content .image{
    display: block;
    padding: 8px;
    margin-bottom: 17px;
    line-height: 150px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
  }

  .filemanager-images .fa-5x{
    font-size:6em;
  }


  .filemanager-images .image-content img{
    /* max-width: 100%; */
    height: auto;
    margin-left: auto;
    margin-right: auto;
    max-height: 150px;
  }

  .modal-dialog .filemanager-images .row .ck{
    position: absolute;
    top: -4px;
    left: 15px;
  }

  .modal-dialog .filemanager-images .row .name{
    width:190px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    text-align:center;
    margin-bottom: 10px;
  }

  .filemanager-images .directory .directory-content {
    display: block;
    padding: 8px;
    margin-bottom: 17px;
    line-height: 150px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
  }

  @media (max-width: 480px) {

    .modal-body {
      font-size: 12px;
      padding: 5px 12px;
      position: relative;
    }

    .modal-dialog .filemanager-images .row .ck{
      position: absolute;
      top: -4px;
      left: 5px;
    }

    .col-xs-3{
      padding:0px 5px;
    }

    .filemanager-images .image-content{
      margin-bottom:0px;
    }
    .filemanager-images .image-content .image{
      display: block;
      padding: 0px;
      margin-bottom: 0px;
      line-height: 60px;
      background-color: #fff;
      border: none;
      border-radius: 3px;
    }



    .filemanager-images .image-content img{
      max-width: 100%;
      height: auto;
      margin-left: auto;
      margin-right: auto;
      max-height: 100%;
    }

    .filemanager-images .directory .directory-content {
      display: block;
      padding: 0px;
      margin-bottom: 5px;
      line-height: 60px;
      height: 80px;
      background-color: #fff;
      border: none;
      border-radius: 3px;
    }

    .modal-dialog .breadcrumb{
      margin-bottom:4px;
    }

    .modal-dialog .row .col-sm-7{
      margin-top:4px;
    }



    .modal-dialog .filemanager-images .row .name{
      width:80px;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      text-align:center;
      margin-top: 5px;
      margin-bottom: 10px;
    }
  }
</style>
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div class="file_breadcrumbs breadcrumb">
            <div class="container">

                <h1 class="breadcrumb-heading"><?php echo $heading_title; ?></h1>
                <ul>
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> /</li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="modal-body">



        <div class="line"></div>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="media">
                    <div class="media-left"><a href="<?php echo $invite; ?>" target="_blank"><img src="image/data/1.png" /></a></div>
                    <div class="media-body">
                        <div class="media-heading"><a href="<?php echo $invite; ?>" target="_blank"><?php echo $text_invite; ?></a></div>
                    </div>
                </div>
            </div>

            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

                <div class="media" id="button-folder">
                    <div class="media-left"><img src="image/data/2.png" /></div>
                    <div class="media-body">
                        <div class="media-heading"><?php echo $text_balance; ?> </div>

                    </div>
                </div>
            </div>

            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

                <div class="media">
                    <div class="media-left"><a href="<?php echo $password; ?>"><img src="image/data/3.png" /></a></div>
                    <div class="media-body">
                        <div class="media-heading"><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></div>

                    </div>
                </div>
            </div>


        </div>
        <div class="line"></div>
        <div class="row">

            <?php foreach ($departments as $department) { ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="media pull-left">
                    <div class="text-center directory"><div class="directory-content"><a href="<?php echo $department['href']; ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-5x"></i></a></div></div>

                </div>
                <div class="pull-right"><a href="<?php echo $department['href'];?>"> > </a></div>
            </div>
            <?php } ?>

        </div>
        <div class="line"></div>
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="media">
                    <?php foreach ($members as $member) { ?>

                    <div class="media ">
                        <span><?php echo $member['name'];?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>

        </div>




    </div>
    <div class="modal-footer"><?php echo $pagination; ?></div>
  </div>
</div>
<link href="catalog/view/javascript/jquery/magnific/magnific-popup.css?v=1.4" rel="stylesheet">
<script type="text/javascript" src="catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js"></script>
<script type="text/javascript"><!--

$(document).ready(function() {
    $('.filemanager-images .image').magnificPopup({
        type:'image',
        delegate: 'a',
        image: {
            verticalFit: true
        },
        gallery: {
            enabled:true
        }
    });
});
$('a.directory').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('.pagination a').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#button-parent').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('.file_breadcrumbs a').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#button-refresh').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('input[name=\'search\']').on('keydown', function(e) {
	if (e.which == 13) {
		$('#button-search').trigger('click');
	}
});

$('#button-search').on('click', function(e) {
	var url = 'index.php?route=common/filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>';
		
	var filter_name = $('#modal-image input[name=\'search\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
							
	<?php if ($thumb) { ?>
	url += '&thumb=' + '<?php echo $thumb; ?>';
	<?php } ?>
	
	<?php if ($target) { ?>
	url += '&target=' + '<?php echo $target; ?>';
	<?php } ?>
			
	$('#modal-image').load(url);
});
//--></script> 
<script type="text/javascript"><!--
$('#button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');
	
	$('#form-upload input[name=\'file\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
		
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			
			$.ajax({
				url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
				type: 'post',		
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-upload').prop('disabled', true);
				},
				complete: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
					$('#button-upload').prop('disabled', false);
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					
					if (json['success']) {
						alert(json['success']);
						
						$('#button-refresh').trigger('click');
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});	
		}
	}, 500);
});

$('#button-folder').popover({
	html: true,
	placement: 'bottom',
	trigger: 'click',
	title: '<?php echo $entry_folder; ?>',
	content: function() {
		html  = '<div class="input-group">';
		html += '  <input type="text" name="folder" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
		html += '  <span class="input-group-btn"><button type="button" title="<?php echo $button_folder; ?>" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
		html += '</div>';

		return html;
	}
});

$('#button-folder').on('shown.bs.popover', function() {
	$('#button-create').on('click', function() {
		$.ajax({
			url: 'index.php?route=common/filemanager/folder&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
			type: 'post',		
			dataType: 'json',
			data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
			beforeSend: function() {
				$('#button-create').prop('disabled', true);
			},
			complete: function() {
				$('#button-create').prop('disabled', false);
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
										
					$('#button-refresh').trigger('click');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});	
});

$('#modal-image #button-delete').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		$.ajax({
			url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
			type: 'post',		
			dataType: 'json',
			data: $('input[name^=\'path\']:checked'),
			beforeSend: function() {
				$('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#button-delete').prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
					
					$('#button-refresh').trigger('click');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>