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

<div class="modal fade"  id="mysplit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel1"><?php echo $text_split; ?></h4>
			</div>
			<div class="modal-body" style=" overflow: auto;">
				<p class="control-label" for="input-order-status"><?php echo $text_split_label; ?></p>
				<div  id="input-order-status" class="form-control" style="border: none;">

					<input style="width:100%;"  id="split-input" placeholder="<?php echo $text_split_tip;?>"/>
					<input type="hidden" style="width:100%;"  id="customer_creation_id" />
				</div>

				<div class="pull-right" style="margin-top: 10px;">
					<button class="btn" id="do-split-btn" data-clipboard-target="#mysplit" style="text-transform:none;"><?php echo $text_split;?></button>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
	<!-- Target -->

</div>

<div class="modal fade"  id="myshare" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $text_share; ?></h4>
			</div>
			<div class="modal-body" style=" overflow: auto;">
				<p class="control-label" for="input-order-status"><?php echo $text_share_tip; ?></p>
				<div  id="input-order-status" class="form-control" style="border: none;">
					<input style="border:none;width:100%;" readonly id="share_link" />

				</div>

				<div class="pull-right" style="margin-top: 10px;">
					<button class="btn" data-clipboard-target="#share_link" style="text-transform:none;"><?php echo $text_copy;?></button>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
	<!-- Target -->

</div>
<div class="main-columns container">

	<div class="design-cat">
		<div class="design-cat-item" style="border-bottom: 3px solid #29d9c2;"><a href="<?php echo $kdesign;?>"><?php echo $text_k_design;?></a> </div>
		<div class="design-cat-item" ><a href="<?php echo $tdesign;?>"><?php echo $text_t_design;?></a> </div>
		<div class="pull-right" style="margin-right: 30%;">
			<input type="checkbox" id="combinedesign_box"  value=""><span><?php echo $text_combine_design;?></span> <button class="btn btn-primary combineop" style="display:none;" id="batcombinebtn"   style="padding:4px;"><?php echo $text_bat_combine;?></button>
		</div>
	</div>
	<?php if(isset($text_add_cart_success)){ ?>
	<div class="text-success" style="padding:10px 0px;"><?php echo $text_add_cart_success;?></div>
	<?php } ?>



	<div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-12 col-md-9 col-lg-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        <?php echo $content_top; ?>
      
      
      <?php foreach ($diydesigns as $i => $diydesign) { ?>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 product-col border">
			<div style="overflow:inherit;padding: 10px;height: 350px;" class="product-block">
				<div class="row-tr combineop" style="display:none;text-align: left;margin-left: 20px">
					<span style="margin-right:3px;"><?php echo $text_cover;?></span><input type="radio" style=" height: 12px;margin: 0px;vertical-align: middle; "name="cover" value="<?php echo $diydesign['customer_creation_id'];?>"><span style="margin:0 5px;">
						<?php echo $text_sort; ?></span>
					<span class="input-sort"><input type="text" style="width:50px;text-align:right;color:#000;font-weight: 600;"  id="<?php echo $diydesign['customer_creation_id'];?>-sort" class="row-sort" name="I[<?php echo $i;?>][sort]" placeholder="<?php echo $hint_sort_text;?>" value="0"> </span>
				</div>
				<div class="design-item-top">
					<div class="design-item-top-left">
						<div class="design-item-top-left-title">
							<input class="diycheckbox pull-left" design_id="<?php echo $diydesign['customer_creation_id'];?>" type="checkbox" name="selected[]" value="<?php echo $diydesign['customer_creation_id'];?>" /><div class="pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $diydesign['info'];?>"><i class="fa fa-info-circle" style="color: #29d9c2;margin:0px 0px 0px 8px;font-size: 20px;"></i></div>
							<div style="padding-right: 40px;">  <a  href="<?php echo $diydesign['product_url'];?>" ><?php echo isset($diydesign['product_name'])?$diydesign['product_name']:$default_name; ?></a>
							
							</div>
					</div>
					<div class="image">
						
						
						<div class="product-img img">
							<img style="width:<?php echo $image_width;?>px;height:<?php echo $image_height;?>px;" src="<?php echo $diydesign['image']; ?>" title="<?php echo $diydesign['name']; ?>" alt="<?php echo $diydesign['name']; ?>" />
							
						</div>
						
					</div>
					<div class="design-item-top-right">
						<?php foreach ($diydesign['actions'] as $action) { ?>
						<div class="design-item-action-icon"><?php echo $action['a']; ?></div>
						<?php } ?>
						
						<?php foreach ($diydesign['moreactions'] as $action) { ?>
						<div class="design-item-action-icon"><?php echo $action['a']; ?></div>
						<?php } ?>

					</div>
				</div>
				
			</div>
			<div class="product-meta design-item-bottom">
				
				
				<div style="text-align:left;padding-right: 40px;">
					<div><span id="span_<?php echo $diydesign['customer_creation_id']; ?>"><?php echo $diydesign['name']?$diydesign['name']:$default_name; ?></span><input class="design_name_input" style="display:none" id="input_<?php echo $diydesign['customer_creation_id']; ?>" value="<?php echo $diydesign['name']?$diydesign['name']:$default_name; ?>"/><span style="margin-left: 15px"><?php echo $diydesign['modify_action']; ?></span></div>
					
					
				</div>


				
				<?php if ($diydesign['option']) { ?>
				<div class="options design-item-option">
					<ul class="list-unstyled">
						
						<?php foreach ($diydesign['option'] as $option) { ?>
						
						<li><div class="pull-left design-item-bold"><?php echo $option['name']; ?>:</div><div style="margin-left:68px"> <?php echo $option['value']; ?></div></li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
				
				<div style="text-align:left" class="caption">
					<ul class="list-unstyled">
						
						
						<li>
							<span class="design-item-bold" ><?php echo $text_date_modified; ?></span>
							<span><?php echo $diydesign['date_modified']; ?></span>
						</li>
						
						
					</ul>
					
				</div>

				<div class="history" style="text-align:left">
					<?php if(!empty($diydesign['historyprojects'])) { ?>
					<div class="btn-group">
						<button style="padding:5px 0px;" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
							<span><?php echo $text_history; ?></span> <i class="fa fa-caret-down"></i></button>
						<ul class="dropdown-menu">
							<?php foreach ($diydesign['historyprojects'] as $project) { ?>
							<li><a class="<?php echo $project['class'];?>" href="<?php echo $project['href']; ?>"><?php echo $project['name']; ?></a></li>
							<?php } ?>

						</ul>

					</div>
					<?php } ?>
				</div>
			</div>
			
		</div>
		
	</div>

	<?php } ?>

      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
	<div>
		<div>

		</div>
		<div>
			<div class="pagination" style="width:100%; text-align: center;">
				<button class="btn btn-primary" id="batdeletebtn" style="float: left;margin-top: 20px;"><?php echo $button_delete;?></button>
				<?php echo $pagination; ?></div>
		</div>
	</div>



	
</div>
<script>

    $("#combinedesign_box").on('change',function () {
        if($(".combineop").is(":hidden")){
            $(".combineop").show();    //如果元素为隐藏,则将它显现
            conbine = true;
        }else{
            $(".combineop").hide();     //如果元素为显现,则将其隐藏
            conbine = false;
            $(".row-tr").css('background-color', '#ffffff');
        }
    });
    conbine = false;
    $(".row-tr .input-sort").on('click',function() {
        if(!conbine) return;
        var click_row = $(this).find('.row-sort');
        var val = click_row.val();
        if(val>0)
        {
            console.log('>0');
            $('.row-tr').each(function(index,ele){

                var row_sort = $(ele).find('.row-sort');
                rowval = row_sort.val();
                console.log(rowval);
                if(rowval>val)
                {
                    row_sort.val(rowval-1)
                }
            });
            click_row.val('');
        }
        else
        {
            console.log('==');
            max = 0;
            $('.row-tr').each(function(index,ele){

                var row_sort = $(ele).find('.row-sort');
                rowval = row_sort.val();
                max = rowval>max?rowval:max;
            });
            console.log(max);
            max = Number(max)+1;
            click_row.val(max);
        }

    })

	$(".getmore").on('click',function(e){
	    e.preventDefault();
		getmore(this);
		return false;
    });
	function getmore(ele) {
		var href = ele.href;
        $.ajax({
            url: href,
            type: 'get',
            data: [],
            dataType: 'html',
            success: function(data) {
                var ul = $(ele).parent().parent();
                $(ele).parent().remove();
                ul.append(data);
                $("body").delegate('.getmore',"click",function (e) {
                    e.preventDefault();
                    getmore(this);
                    return false;
                });
            }
        });
    }
    new ClipboardJS('.btn');
	$(".share_btn").on('click',function(e) {
	    e.preventDefault();
	    sharelink = $(this).attr('share_link');
	    $("#share_link").val(sharelink);
	    $("#myshare").modal();
	    return false;
    });

    $(".split_btn").on('click',function(e) {
        e.preventDefault();
        customer_creation_id = $(this).attr('customer_creation_id');
        $("#customer_creation_id").val(customer_creation_id);
        $("#mysplit").modal();
        return false;
    });


    $("#do-split-btn").on('click',function(e) {
        e.preventDefault();
        var customer_creation_id = $("#customer_creation_id").val();
        var pages = $("#split-input").val();
        $.ajax({
            url: 'index.php?route=account/diydesign/split_design',
            type: 'post',
            data: 'customer_creation_id=' + customer_creation_id+'&pages='+pages,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    location.reload();
                }else{
                    alert("<?php echo $error_split_failed;?>");
                }
            }

        });
    })
	var foucus_creation_id = 0;
	var design_name = '';
	$(".design_name_input").blur(function(){
		
		var new_name = $("#input_"+foucus_creation_id).val();
		$("#span_"+foucus_creation_id).text(new_name);
		$("#input_"+foucus_creation_id).hide();
		$("#span_"+foucus_creation_id).show();
		if(design_name != new_name){
			$.ajax({
				url: 'index.php?route=account/diydesign/modify_design_name',
				type: 'post',
				data: 'customer_creation_id=' + foucus_creation_id+'&name='+$("#input_"+foucus_creation_id).val(),
				dataType: 'json',
				success: function(json) {
					if (json['success']) {
						location.reload();
					}else{
						alert("<?php echo $error_modify_failed;?>");
					}
				}

			});
	}
});
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
function doDelDesign(alert_text){
var cret = confirm(alert_text);
if(cret)
return true;
else{
var event = getEvent();
event.preventDefault();
return false;
}
}


    $("#batcombinebtn").on('click',function(){
        var cret = confirm("<?php echo $text_comfirm_bat_combine;?>");
        if(!cret)
            return false;

        post_data = new Array();
        $('input[name^=\'selected[]\']:checked').each(function(i,ele) {
            var design_id = $(ele).attr('design_id');
            var sort = $("#"+design_id+"-sort").val();
            post_data.push({id:design_id,sort:sort});
        })

		var cover = $('input[name=\'cover\']:checked').val();
        $.ajax({
            url: 'index.php?route=account/diydesign/combine_design',
            type: 'post',
            dataType: 'json',
            data: {data:JSON.stringify(post_data),'cover':cover},
            beforeSend: function() {
                $('#button-transaction').button('loading');
            },
            complete: function() {
                $('#button-transaction').button('reset');
            },
            success: function(json) {
                if (json['success']) {
                    location.reload();
                }else{
                    alert("<?php echo $error_bat_combine_failed;?>");
                }
            }
        });
    });


$("#batdeletebtn").on('click',function(){
var cret = confirm("<?php echo $text_bat_delete;?>");
if(!cret)
return false;

$.ajax({
url: 'index.php?route=account/diydesign/batdelete',
type: 'post',
dataType: 'json',
data: $('input[name^=\'selected[]\']:checked'),
beforeSend: function() {
$('#button-transaction').button('loading');
},
complete: function() {
$('#button-transaction').button('reset');
},
success: function(json) {
if (json['success']) {
location.reload();
}else{
alert("<?php echo $error_bat_delete_failed;?>");
}
}
});
});

function modifyName(customer_creation_id){
getEvent().preventDefault();

if($("#input_"+customer_creation_id).is(':hidden')){
$("#input_"+customer_creation_id).show();
$("#input_"+customer_creation_id).focus();
$("#span_"+customer_creation_id).hide();
foucus_creation_id = customer_creation_id;
design_name = $("#input_"+customer_creation_id).val();
return false
}


return fale;
}


function doSendOrder(product_id,creation_id){
var cret = confirm("<?php echo $send_order_tip; ?>");

if (cret){
quantity = typeof(quantity) != 'undefined' ? quantity : 1;
$.ajax({
url: 'index.php?route=account/diydesign/addOrder',
type: 'post',
data: 'product_id=' + product_id+'&creation_id='+creation_id,
dataType: 'json',
success: function(json) {
if (json['success']) {
alert("確認送出成功！");
}else{
alert("送出失敗！");
}
}

});
}
}

</script>
<?php echo $footer; ?>