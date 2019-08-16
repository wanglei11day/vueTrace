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
<div class="main-columns container">
	<div class="design-cat">
		<div class="design-cat-item"><a href="<?php echo $kdesign;?>"><?php echo $text_k_design;?></a> </div>
		<div class="design-cat-item" style="border-bottom: 3px solid #29d9c2;"><a href="<?php echo $tdesign;?>"><?php echo $text_t_design;?></a> </div>
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
			<div style="overflow:inherit;padding: 10px;height: 250px;" class="product-block">
				<div class="design-item-top">
					<div class="design-item-top-left">
						<div class="design-item-top-left-title">
							<input class="diycheckbox pull-left" type="checkbox" name="selected[]" value="<?php echo $diydesign['customer_creation_id'];?>" /><div class="pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $diydesign['info'];?>"><i class="fa fa-info-circle" style="color: #29d9c2;margin:0px 0px 0px 8px;font-size: 20px;"></i></div>
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
					<div><span id="span_<?php echo $diydesign['customer_creation_id']; ?>"><?php echo $diydesign['name']?$diydesign['name']:$default_name; ?></span><input class="design_name_input" style="display:none" id="input_<?php echo $diydesign['customer_creation_id']; ?>" value="<?php echo $diydesign['name']?$diydesign['name']:$default_name; ?>"/></div>
					
					
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
				
				
			</div>
			
		</div>
		
	</div>

	<?php } ?>

      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
  <div class="pagination" style="width:100%; text-align: center;"><?php echo $pagination; ?></div>


	
</div>
<script>
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
function doDelDesign(alert_text,del_id){
	var event = getEvent();
    event.preventDefault();
	var cret = confirm(alert_text);
	if(cret) {
		href = "<?php echo $tshirt_uri.'&id='?>"+del_id;
		$.ajax({
			url: href,
			type: 'get',
			dataType: 'json',
			data: '',
			beforeSend: function () {
				//$('#button-transaction').button('loading');
			},
			complete: function () {
				//$('#button-transaction').button('reset');
                location.reload();
			},
			success: function (json) {
				location.reload();
			}
		});
		return true;
	}
	else{

		return false;
	}
}





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