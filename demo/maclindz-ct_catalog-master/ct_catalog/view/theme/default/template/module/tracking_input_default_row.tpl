<div id="tracking-input-div" class="alert alert-info">	
	<form class="form-inline" onsubmit="javascript:return false;">	
<?php if($show_close_button) { ?>
		<button type="button" class="close" id="tracking-input-close">&times;</button>
<?php } ?>		
		<div id="tracking-input-div-success" class="text-success" style="display:none;"></div>				
		<div id="tracking-input-div-content">
			<div class="form-group">
				<label for="tracking-input-code"><?php echo $text_message;?></label>
				<input type="text" id="tracking-input-code" class="form-control" /> 
			</div>	
			<button class="btn btn-primary" type="button" value="<?php echo $send_button;?>" id="tracking-input-send-button" disabled="disabled" data-loading-text="<?php echo $text_loading; ?>" /><?php echo $send_button;?></button>
		</div>		
	</form>	
	<div class="text-danger" id="tracking-input-div-error" style="display:none;"></div>	
</div>

<!-- ---------------- -->

<script type="text/javascript">$('#tracking-input-div').data('d', <?php echo $json;?>);</script>