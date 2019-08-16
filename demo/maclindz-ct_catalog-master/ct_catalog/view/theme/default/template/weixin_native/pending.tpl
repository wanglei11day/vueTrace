<?php echo $header; ?>
<style>
  .breadcrumb a {
    color:#000;
  }
</style>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <h1><?php echo $heading_title; ?></h1>
      

	<img alt="微信支付二维码" src="<?php echo $my_url2;?>" style="width:250px;height:250px;" />
      
      <br />
      <img alt="请使用微信'扫一扫' 扫描二维码支付" src="image/tip.png" width="260px" height="86px" />

      
      
      
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>


<script type="text/javascript">
function get_status(){
	$.ajax({
		url: 'index.php?route=weixin_native/pending/get_order_status&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: '',
		beforeSend: function() {
			
		},
		complete: function() {
			
		},
		success: function(json) {
			
			console.log(json);

			if (json['success'] == 'ok') {
				
				window.clearInterval(t1);
				
				window.location.href = 'index.php?route=checkout/success';
			}
		}
	});
}
//重复执行某个方法
var t1 = window.setInterval(get_status,1000);
//去掉定时器的方法
//window.clearInterval(t1);
</script> 


<?php echo $footer; ?>