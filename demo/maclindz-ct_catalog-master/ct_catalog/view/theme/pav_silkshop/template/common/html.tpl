<?php  echo $header; ?> 
<?php require( ThemeControlHelper::getLayoutPath( 'common/config-home.tpl' )  ); ?> 
<?php
	$config = $this->registry->get('config'); 
	$themeConfig = (array)$config->get('themecontrol');
	$fullclass = isset($themeConfig['home_container_full'])&&$themeConfig['home_container_full']?"-full":""; 
?>



<div class="main-columns container">
  	<div class="row">
  		
	  
	   	<div id="sidebar-main" class="col-md-12">
			<?php foreach ($modules as $module) { ?>
				<?php echo $module; ?>
			<?php } ?>
	   	</div> 
		
	</div>
</div>
<?php echo $footer; ?>