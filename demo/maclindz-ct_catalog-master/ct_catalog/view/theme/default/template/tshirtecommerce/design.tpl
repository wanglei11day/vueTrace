<?php @ini_set("zlib.output_compression", "Off"); ?>
<?php echo $header; ?>
<div class="container">
	<?php echo $content; ?>
</div>
<link type="text/css" href="tshirtecommerce/themes/default/fonts/flaticon.css" rel="stylesheet" media="all">
<link href="<?php echo $url . 'tshirtecommerce/opencart/css/mobile.css'; ?>" rel="stylesheet">
<script type="text/javascript">
	var urlBack = '<?php echo $url .'index.php?route=product/product&product_id='.$parent_id ; ?>';	
	var urlDesign = '<?php echo $url .'index.php?route=tshirtecommerce/designer'; ?>';
	var _product_id_oc = '<?php echo $parent_id; ?>';
    var urlDesignload = "<?php echo $order_product_id?$urlDesignload.'&order_product_id='.$order_product_id:$urlDesignload; ?>";
	var ocedit = '<?php echo $edit; ?>';
</script>
<script src="<?php echo $url . 'tshirtecommerce/assets/js/app.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $url . 'tshirtecommerce/opencart/js/ocapp.js'; ?>" type="text/javascript"></script>
<?php echo $footer; ?>