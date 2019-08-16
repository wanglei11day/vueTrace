<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?> </a> </li>
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
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
  <h1><?php echo $heading_title; ?></h1>
<div class="clearfix">

<?php if ($show['earnings']) { ?>
<div style="float:right;border:1px solid #aba;width:30%;padding-left:10px;padding-right:20px">
<h3><u><?php echo $text_legend; ?></u></h3>
<strong><?php echo $text_abbr_te; ?></strong> - <?php echo $text_te; ?><br />
<strong><?php echo $text_abbr_elm; ?></strong> - <?php echo $text_elm; ?><br />
</div>
<?php }?>
<ul class="tree">
	<li>(<a href="javascript:;" rel="<?php echo $affiliate_id; ?>"<?php if ($top_count > 0) { ?> class="load_subs" title="<?php echo $text_expand; ?>"<?php } ?>><?php echo $top_count; ?></a>)  <strong><?php echo $text_self; ?></strong><img src="<?php echo $image_loading; ?>" class="loading" style="display:none;" /></li>
</ul>

</div>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>

  <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$(document).on('click', '.load_subs', function() {
		$(this).attr('title', ($(this).attr('title') == '<?php echo $text_expand; ?>' ? '<?php echo $text_collapse; ?>' : '<?php echo $text_expand; ?>'));
		var _p = $(this).parent();
		var _this = $(this);
		if($(_p).find('ul').length < 1) {
			$(_p).find('.loading').show();
			$.ajax({
				url: document.location.href.replace('affiliate/downline', 'affiliate/downline/l')+(document.location.href.indexOf('?') == -1 ? '?' : '&')+'affiliate_id='+$(_this).attr('rel'),
				dataType: 'json',
				success: function(_r) {
					$(_p).append('<ul></ul>');
					for(var i = 0; i < _r.length; i++) {
						var _li = '<li>(';
						if(_r[i].c > 0) _li += '<a href="javascript:;" class="load_subs" rel="'+_r[i].affiliate_id+'" title="<?php echo $text_expand; ?>">';
						_li += _r[i].c;
						if(_r[i].c > 0) _li += '</a>'
						_li += ') <span class="aff_name">'+_r[i].name+'</span> ';
						
						<?php if ($show['email']) { ?>
						_li += '<a href="mailto:'+_r[i].email+'" style="color:#121;">'+_r[i].email+'</a> ';
						<?php } ?>
						
						<?php if ($show['phone']) { ?>
						_li += ' '+_r[i].telephone+' ';
						<?php } ?>
						
						_li += ' <img src="<?php echo $image_loading; ?>" class="loading" style="display:none;" />';
						
						<?php if($show['earnings']) { ?>
						_li += '<span class="help">';
						_li += '<?php echo $text_abbr_te; ?>: ' + _r[i].e_te + ' / ' + '<?php echo $text_abbr_elm; ?>: ' + _r[i].e_elm;
						_li += '</span>';
						<?php } ?>
						_li += ' </li>';
						$(_p).find('ul').append(_li);
					}
					$(_p).find('.loading').hide();
				}
				});
		} else {
			$($(_p).find('ul')[0]).toggle();
		}
	});
	if($('.load_subs').length > 0) $('.load_subs').trigger('click');
}
);

//--></script> 
<?php echo $footer; ?>
