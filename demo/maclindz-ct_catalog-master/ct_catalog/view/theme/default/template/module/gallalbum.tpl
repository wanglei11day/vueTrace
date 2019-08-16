<h3><?php echo $headtitle; ?></h3>
<?php if ($showimg == 1) { ?>
<div class="row">
<?php foreach ($gallalbums as $gallalbum) { ?>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
<div class="box-album transition gallalbum"> 
<div class="image"><a href="<?php echo $gallalbum['href']; ?>"><img src="<?php echo $gallalbum['thumb']; ?>" alt="<?php echo $gallalbum['name']; ?>" title="<?php echo $gallalbum['name']; ?>" class="img-responsive" /></a></div>
<div class="caption alb<?php echo $module; ?>">
<h4 class="gallalbum"><a href="<?php echo $gallalbum['href']; ?>"><?php echo $gallalbum['name']; ?></a></h4>
<?php if ($descstat == 1) { ?>
<p><?php echo $gallalbum['description']; ?></p>
<?php } ?>
</div>
</div>
</div>
<?php } ?>
</div>
<?php } else { ?>
<div class="list-group">
<?php foreach ($gallalbums as $gallalbum) { ?>
<a href="<?php echo $gallalbum['href']; ?>" class="list-group-item"><?php echo $gallalbum['name']; ?></a>
<?php } ?>
</div>
<?php } ?>
<script type="text/javascript"><!--
function setEqualHeight(columns) { 
		var tallestcolumn = 0;
 			columns.each( function() {
 				currentHeight = $(this).height();
 				if(currentHeight > tallestcolumn)  {
 					tallestcolumn  = currentHeight; } 
 				});
 				columns.height(tallestcolumn);  }
	$(document).ready(function() {
 		setEqualHeight($(".alb<?php echo $module; ?>"));
	});	
--></script>
