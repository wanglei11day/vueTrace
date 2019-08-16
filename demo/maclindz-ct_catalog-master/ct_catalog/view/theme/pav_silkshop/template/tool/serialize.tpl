
<?php echo $header; ?>
<div class="breadcrumb" style="">
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

	<div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-md-9 col-sm-12'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<!--<h1><?php echo $heading_title; ?></h1>-->
			<form action="" method="POST" id="mainform"  enctype="multipart/form-data">
				<div class="row">

					<div class="col-sm-12 category-description">
						<textarea name="str" style="width: 500px;height: 400px;"><?php echo $str;?></textarea>
					</div>
				<input name="type" type="hidden" value="0" id="tag">
				</div>
				<div class="buttons">
					<div class="pull-right"><a href="#" class="btn btn-primary" id="xuliehua">序列化</a></div>
					<div class="pull-right"><a href="#" class="btn btn-primary" id="fanxuliehua">反序列化</a></div>
				</div>
			</form>

			<?php echo $content_bottom; ?></div>
		<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

<script>
	$("#xuliehua").on('click',function(e){
		e.preventDefault();
		$("#tag").val(1);
		$("#mainform").submit();
    });

    $("#fanxuliehua").on('click',function(e){
        e.preventDefault();
        $("#tag").val(0);
        $("#mainform").submit();
    });
</script>
