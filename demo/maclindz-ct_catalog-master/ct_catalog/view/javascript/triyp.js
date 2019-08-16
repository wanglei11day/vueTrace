(function($){$(document).ready(function() {
	var _ti = 'tracking-input';
	var _d = $('#'+_ti+'-div').data('d');	
	$('#'+_ti+'-send-button').attr('disabled', false);
	$('#'+_ti+'-div-error').html(_d.error_message);
	$('#'+_ti+'-div-success').html(_d.text_thankyou);
	var _tf_close = function(elem) {
		$('#'+_ti+'-div').fadeOut('slow', function() {
			$('#'+_ti+'-div').remove();
		});
	}
	if($('#'+_ti+'-close').length > 0) $(document).on('click', '#'+_ti+'-close', function() {
		$.get(_d.close_link);
		_tf_close(this);
	});

	$(document).on('click', '#'+_ti+'-send-button', function() {
		$('#'+_ti+'-code').val($('#'+_ti+'-code').val().replace(/^[\n\r\s]+/, '').replace(/[\n\r\s]+$/, ''));
		if($('#'+_ti+'-code').val().length < 1) return;
		$('#'+_ti+'-send-button').attr('disabled', true);
		
		$.ajax({
			url : _d.send_link, 
			data: {t : $('#'+_ti+'-code').val()}, 
			type : 'post',
			cache : false,
			beforeSend : function() {
				$('#'+_ti+'-send-button').button('loading');
				$('#'+_ti+'-div-error').hide();
			}, 
			complete : function() {
				$('#'+_ti+'-send-button').button('reset');
				$('#'+_ti+'-send-button').attr('disabled', false);
			}, 			
			success: function(_r) {
			if(_r == 'error') {
				$('#'+_ti+'-code').val('');
				$('#'+_ti+'-div-error').show();
				return;
			}
			if(_d.text_thankyou.length > 0) {
				$('#'+_ti+'-div-error').hide();
				$('#'+_ti+'-div-content').hide();
				$('#'+_ti+'-div-success').show();
			}			
			
			if(_r > 0) {
				_tf_close($('#'+_ti+'-close'));
				if(/[\?\&]tracking=/.test(document.location.href)) {
					var _loc = document.location.replace(/tracking=[^\&]*/, 'tracking='+$('#'+_ti+'-code').val());
				} else {
					var _loc = document.location.href;
					_loc += (_loc.indexOf('?') != -1 ? '&' : '?') + 'tracking='+$('#'+_ti+'-code').val();
				}
				setTimeout(function() { document.location.href = _loc;}, 2000);
			}
		}
		});	
	});
});
})(jQuery&&jQuery.ready?jQuery:$);
