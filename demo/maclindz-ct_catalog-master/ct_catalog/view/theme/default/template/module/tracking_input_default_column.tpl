<div id="tracking-input-div">	
<?php if($show_close_button) { ?>
	<button type="button" class="close" id="tracking-input-close">&times;</button>
<?php } ?>
	<div class="panel panel-info">
	<?php if ($text_heading) { ?>
		<div class="panel-heading"><?php echo $text_heading; ?></div>
	<?php } ?>
		<div class="panel-body">
			<div id="tracking-input-div-success" class="text-success" style="display:none;"></div>						
			<form onsubmit="javascript:return false;" id="tracking-input-div-content">
				<div class="form-group">
					<label for="tracking-input-code"><?php echo $text_message;?></label>
					<input type="text" id="tracking-input-code" class="form-control" /> 
					<div class="text-danger" id="tracking-input-div-error" style="display:none;"></div>	
				</div>	
				<button class="btn btn-primary" type="button" value="<?php echo $send_button;?>" id="tracking-input-send-button" disabled="disabled" data-loading-text="<?php echo $text_loading; ?>" /><?php echo $send_button;?></button>			
			</form>	
		</div>
	</div>
</div>

<!-- ---------------- -->

<script type="text/javascript">$('#tracking-input-div').data('d', <?php echo $json;?>);</script>