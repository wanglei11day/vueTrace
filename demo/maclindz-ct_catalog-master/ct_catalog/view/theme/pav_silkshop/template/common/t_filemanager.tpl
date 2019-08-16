<style type="text/css">

    .directory img{
        width:60px;
    }
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
		top: 0px;
		left: 15px;
	}

  .modal-dialog .filemanager-images .row .view{
      position: absolute;
      top: 4px;
      right: 15px;
  }

    .modal-dialog .filemanager-images .row .name{
    width:162px;
      float:left;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        text-align:center;
        margin-bottom: 10px;
    }

  .edit-name
  {
      margin-left:15px;
      float: right;
      margin-top: 2px;
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

        .directory img{
            width:45px;
        }
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
      width:60px;
        float:left;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            text-align:center;
            margin-top: 5px;
            margin-bottom: 10px;
        }
    .edit-name
    {
        margin-left:3px;
        float: right;
        margin-top: 7px;
    }
}

  .detail-item{
      padding:10px 0px;
  }
    #image_modal .modal-dialog {
        margin-top: 10%;
    }
    .folder-btns{
        border: none;
        background: none;
    }
    .folder-btns img{
        width:45px;
    }
</style>

<div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close close-image" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $text_image_detail;?></h4>
            </div>
            <div class="modal-body">


            <div>
                <div class="detail-item">
                    <span><?php echo $text_image_name;?></span>
                    <span id="detail-image-name"></span>
                </div>
                <div class="detail-item">
                    <span><?php echo $text_image_dir;?></span>
                    <span id="detail-image-dir"></span>
                </div>
                <div class="detail-item">
                    <span><?php echo $text_size;?></span>
                    <span id="detail-image-size"></span>
                </div>
                <div class="detail-item">
                    <span><?php echo $text_with_height;?></span>
                    <span id="detail-image-width-height"></span>
                </div>
                <div class="detail-item">
                    <span><?php echo $text_image_date;?></span>
                    <span id="detail-image-date-added"></span>
                </div>
            </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<div class="modal-dialog modal-lg" id="filemanager-modal">
  <div class="modal-content" style="height: 700px;overflow: scroll;">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title"><?php echo $heading_title; ?></h4>
    </div>
    <div class="modal-body">
        <div class="progress" style="display: none;">
            <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="loading-bar"></div>
        </div>
		<ul class="breadcrumb">
			<?php foreach ($file_breadcrumbs as $breadcrumb) { ?>
			<li class="file_breadcrumbs"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
		<hr class="hidden-xs" >
        <?php if(!$init) { ?>
      <div class="row">
<?php if(!$share) { ?>
          <div class="col-sm-5"> <a href="<?php echo $refresh; ?>"  title="<?php echo $button_refresh; ?>" id="button-refresh" class="folder-btns"><i class=""><img src="/image/data/folder/refresh.png"/></i></a>
              <button type="button"  title="<?php echo $button_upload; ?>" id="button-upload" class="folder-btns"><i class=""><img src="/image/data/folder/upload.png"/></i></button>
              <button type="button"  title="<?php echo $button_folder; ?>" id="button-folder" class="folder-btns"><i class=""><img src="/image/data/folder/folder.png"/></i></button>
              <button type="button"  title="<?php echo $button_delete; ?>" id="button-delete" class="folder-btns"><i class=""><img src="/image/data/folder/trash.png"/></i></button>
          </div>
          <?php } ?>
        <div class="col-sm-7">
          <div class="input-group">
            <input type="text" name="search" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_search; ?>" class="form-control">
            <span class="input-group-btn">
            <button type="button" data-toggle="tooltip" title="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </span></div>
        </div>
      </div>
      <hr />
        <?php } ?>

      <div class="filemanager-images">
      <?php foreach (array_chunk($images, 4) as $image) { ?>
      <div class="row">
        <?php foreach ($image as $image) { ?>
        <div class="col-xs-3 col-sm-4 col-md-3 text-center">
          <?php if ($image['type'] == 'directory') { ?>
          <div class="text-center directory"><div class="directory-content"><a href="<?php echo $image['href']; ?>" class="directory" style="vertical-align: middle;">
                      <?php if(!$share&&!$image['share']) { ?>
                      <img src="/image/data/folder.png"/>
                      <?php } else { ?>
                      <img src="/image/data/folder_share.png"/>
                      <?php } ?>
                  </a></div></div>
            <?php if(!$share) { ?>
          <div class="ck">
            <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
            </div>
            <?php } ?>
			<div class="name" >
                <span id="span_<?php echo $image['path']; ?>"><?php echo $image['name']?$image['name']:$default_name; ?></span>
                <input class="design_name_input" style="display:none" id="input_<?php echo $image['path']; ?>" value="<?php echo $image['name']?$image['name']:$default_name; ?>"/>
            </div>
            <span class="edit-name"><?php if(!$share) echo $image['modify_action']; ?></span>
          <?php } ?>
          <?php if ($image['type'] == 'image') { ?>
          <div class="image-content">
            <div class="image" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" name="<?php echo $image['name']; ?>"><img origin="<?php echo $image['href']; ?>" src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" /></div>
          </div>

            <?php if(!$share) { ?>
		  <div class="ck">
              <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
            </div>
            <?php } ?>
<div class="view">
                <i image-name="<?php echo $image['name'];?>" image-dir="<?php echo $image['dir_name'];?>" image-size="<?php echo $image['size'];?>" image-width-height="<?php echo $image['width'].'X'.$image['height'];?>" image-date-added="<?php echo $image['date_added'];?>" class="show-detail"><img style="width:20px;" src="/image/data/i.png" /></i>
            </div>
			<div class="name" >             
                <span id="span_<?php echo $image['path']; ?>"><?php echo $image['name']?$image['name']:$default_name; ?></span>
                <input class="design_name_input" style="display:none" id="input_<?php echo $image['path']; ?>" name="pathv" value="<?php echo $image['name']?$image['name']:$default_name; ?>"/>
            </div>
            <span class="edit-name"><?php if(!$share) echo $image['modify_action']; ?></span>

          <?php } ?>
        </div>
        <?php } ?>
      </div>

      <?php } ?>
      </div>
    </div>
    <div class="modal-footer"><?php echo $pagination; ?></div>
  </div>
</div>

<script type="text/javascript"><!--
jQuery(".filemanager-images .image-content .image").bind('click', function(){

    var maxwidth  = <?php echo $max_width; ?>;
    var maxheight  = <?php echo $max_height; ?>;
    var minwidth  = <?php echo $min_width; ?>;
    var minheight  = <?php echo $min_height; ?>;
    var pic_width = jQuery(this).attr('width');
	
    var pic_height = jQuery(this).attr('height');
    if(pic_width<minwidth|| pic_height < minheight)
    {
    	alert("<?php echo $error_size_limit; ?>");
    	jQuery('#modal-image').hide();
		jQuery('.modal-backdrop').hide();
    	return false;
    }
    newwidth = pic_width;
    newheight = pic_height;
    var resizewidth_tag = false;
    var resizeheight_tag = false;
    if((maxwidth && pic_width > maxwidth) && (maxheight && pic_height > maxheight))
    {
        ratio = pic_width/pic_height;
        if(pic_width>pic_height)
        {
            pic_height = maxheight;
            pic_width = pic_height*ratio;
        }
        else
        {
            pic_width = maxwidth;
            pic_height = pic_width/ratio;
        }
        /*
        if(maxwidth && pic_width>maxwidth)
        {
            $widthratio = maxwidth/pic_width;
            resizewidth_tag = true;
        }
        if(maxheight && pic_height>maxheight)
        {
            $heightratio = maxheight/pic_height;
            resizeheight_tag = true;
        }
        if(resizewidth_tag && resizeheight_tag)
        {
            if($widthratio<$heightratio)
                ratio = $widthratio;
            else
                ratio = $heightratio;
        }
        if(resizewidth_tag && !resizeheight_tag)
            ratio = $widthratio;
        if(resizeheight_tag && !resizewidth_tag)
            ratio = $heightratio;
        newwidth = Math.floor(pic_width * ratio);
        newheight = Math.floor(pic_height * ratio);
        */
        newwidth = pic_width;
        newheight = pic_height;
    }
	item = new Object();
	item.file_name= jQuery(this).attr('name');
    if(resizewidth_tag||resizeheight_tag){
        item.file_name= jQuery(this).attr('name');
    }
	item.file_type= 'image';
	item.title= 'title';
    var origin = jQuery(this).find('img').attr('origin');
    if(newwidth>0&&newheight>0){
        var originarr = origin.split('/');
        var f_name = originarr.pop();
        var origin_link = originarr.join('/');
        origin = origin_link+'/'+newwidth+'x'+newheight+'/'+f_name;
    }

	item.url = origin;
	item.thumb = origin;
	var span = document.createElement("span");
	span.className = 'view-thumb col-xs-4 col-md-4';
	span.item= item;
	design.myart.create(span);
	
	setTimeout(function(){
		var elm = design.item.get();
		jQuery(elm).addClass('drag-item-upload');
		jQuery(elm).data('upload', 1);
	}, 100);

    
	jQuery('#modal-image').hide();
	jQuery('.modal-backdrop').hide();
	
});
    var foucus_path_id = 0;
    var design_name = '';
    jQuery(".design_name_input").blur(function(){

        var new_name = jQuery("#input_"+foucus_path_id).val();
        jQuery("#span_"+foucus_path_id).text(new_name);
        jQuery("#input_"+foucus_path_id).hide();
        jQuery("#span_"+foucus_path_id).show();
        if(design_name != new_name){
            jQuery.ajax({
                url: '<?php echo $base_url;?>/index.php?route=common/filemanager/modify_name',
                type: 'post',
                data: 'path_id=' + foucus_path_id+'&name='+jQuery("#input_"+foucus_path_id).val(),
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        alert("<?php echo $text_modify_success;?>");
                    }else{
                        alert("<?php echo $error_modify_failed;?>");
                    }
                }

            });
        }
    });
jQuery(".close-image").on('click',function() {
    jQuery("#image_modal").modal('hide');
})
function modifyName(path_id){
    getEvent().preventDefault();

    if(jQuery("#input_"+path_id).is(':hidden')){
        jQuery("#input_"+path_id).show();
        jQuery("#input_"+path_id).focus();
        jQuery("#span_"+path_id).hide();
        foucus_path_id = path_id;
        design_name = jQuery("#input_"+path_id).val();
        return false
    }


    return false;
}

    function getEvent(){
        if(window.event){
            return window.event;
        }
        var f = arguments.callee.caller;
        do{
            var e = f.arguments[0];
            if(e && (e.constructor === Event || e.constructor===MouseEvent || e.constructor===KeyboardEvent)){
                return e;
            }
        }while(f=f.caller);
    }
jQuery(".show-detail").on('click',function(e) {
    jQuery("#detail-image-name").text(jQuery(this).attr('image-name'));
    jQuery("#detail-image-dir").text(jQuery(this).attr('image-dir'));
    jQuery("#detail-image-date-added").text(jQuery(this).attr('image-date-added'));
    jQuery("#detail-image-size").text(jQuery(this).attr('image-size'));
    jQuery("#detail-image-width-height").text(jQuery(this).attr('image-width-height'));
    jQuery('#image_modal').modal({backdrop: 'static', keyboard: false});
})
jQuery('a.directory').on('click', function(e) {
	e.preventDefault();
	
	jQuery('#modal-image').load(jQuery(this).attr('href'));
});

jQuery('.pagination a').on('click', function(e) {
	e.preventDefault();
	
	jQuery('#modal-image').load(jQuery(this).attr('href'));
});

jQuery('#button-parent').on('click', function(e) {
	e.preventDefault();
	
	jQuery('#modal-image').load(jQuery(this).attr('href'));
});

jQuery('.file_breadcrumbs a').on('click', function(e) {
	e.preventDefault();
	
	jQuery('#modal-image').load(jQuery(this).attr('href'));
});

jQuery('#button-refresh').on('click', function(e) {
	e.preventDefault();
	
	jQuery('#modal-image').load(jQuery(this).attr('href'));
});

jQuery('input[name=\'search\']').on('keydown', function(e) {
	if (e.which == 13) {
		jQuery('#button-search').trigger('click');
	}
});

jQuery('#button-search').on('click', function(e) {
	var url = 'http://www.icootoo.com/index.php?route=common/t_filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>';
		
	var filter_name = jQuery('#modal-image input[name=\'search\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
							
	<?php if ($thumb) { ?>
	url += '&thumb=' + '<?php echo $thumb; ?>';
	<?php } ?>
	
	<?php if ($target) { ?>
	url += '&target=' + '<?php echo $target; ?>';
	<?php } ?>
			
	jQuery('#modal-image').load(url);
});
//--></script> 
<script type="text/javascript"><!--
jQuery('#button-upload').on('click', function() {
	jQuery('#form-upload').remove();
	
	jQuery('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');
	
	jQuery('#form-upload input[name=\'file\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
		
	timer = setInterval(function() {
		if (jQuery('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			
			jQuery.ajax({
				url: 'http://www.icootoo.com/index.php?route=common/t_filemanager/upload&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
				type: 'post',		
				dataType: 'json',
				data: new FormData(jQuery('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
                xhr: function(){ //获取ajaxSettings中的xhr对象，为它的upload属性绑定progress事件的处理函数

                    myXhr = jQuery.ajaxSettings.xhr();
                    if(myXhr.upload){ //检查upload属性是否存在
                        //绑定progress事件的回调函数
                        myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
                    }
                    return myXhr; //xhr对象返回给jQuery使用
                },
                beforeSend: function() {
                    jQuery('.progress').show();
                    jQuery('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					jQuery('#button-upload').prop('disabled', true);
				},
				complete: function() {
					jQuery('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
					jQuery('#button-upload').prop('disabled', false);
                    jQuery('.progress').hide();
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					
					if (json['success']) {
						alert(json['success']);
						jQuery('#button-refresh').trigger('click');
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});	
		}
	}, 500);
});

    // ：
    function progressHandlingFunction(e) {
        if (e.lengthComputable) {
           // jQuery('progress').attr({value : e.loaded, max : e.total}); //更新数据到进度条
            var percent = e.loaded/e.total*100;
            jQuery('.progress .progress-bar').css('width', percent+'%');
           // jQuery('#progress').html(e.loaded + "/" + e.total+" bytes. " + percent.toFixed(2) + "%");
        }

    }

    jQuery('#button-folder').popover({
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

jQuery('#button-folder').on('shown.bs.popover', function() {
	jQuery('#button-create').on('click', function() {
		jQuery.ajax({
			url: 'http://www.icootoo.com/index.php?route=common/t_filemanager/folder&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
			type: 'post',		
			dataType: 'json',
			data: 'folder=' + encodeURIComponent(jQuery('input[name=\'folder\']').val()),
			beforeSend: function() {
				jQuery('#button-create').prop('disabled', true);
			},
			complete: function() {
				jQuery('#button-create').prop('disabled', false);
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
										
					jQuery('#button-refresh').trigger('click');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});	
});

jQuery('#modal-image #button-delete').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		jQuery.ajax({
			url: 'http://www.icootoo.com/index.php?route=common/t_filemanager/delete&token=<?php echo $token; ?>',
			type: 'post',		
			dataType: 'json',
			data: jQuery('input[name^=\'path\']:checked'),
			beforeSend: function() {
				jQuery('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				jQuery('#button-delete').prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
					
					jQuery('#button-refresh').trigger('click');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>