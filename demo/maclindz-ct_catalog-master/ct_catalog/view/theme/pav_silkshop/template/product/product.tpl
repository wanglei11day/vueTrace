<?php

  $config = $this->registry->get('config');

  $themeConfig = (array)$config->get('themecontrol');
  $productConfig = array(
      'product_enablezoom'         => 1,
      'product_zoommode'           => 'basic',
      'product_zoomeasing'         => 1,
      'product_zoomlensshape'      => "round",
      'product_zoomlenssize'       => "150",
      'product_zoomgallery'        => 0,
      'enable_product_customtab'   => 0,
      'product_customtab_name'     => '',
      'product_customtab_content'  => '',
      'product_related_column'     => 0,
    );
    $listingConfig = array(
      'category_pzoom'                    => 1,
      'quickview'                                 => 0,
      'show_swap_image'                         => 0,
      'catalog_mode'                => 1
    );
    $listingConfig          = array_merge($listingConfig, $themeConfig );
    $categoryPzoom            = $listingConfig['category_pzoom'];
    $quickview                = $listingConfig['quickview'];
    $swapimg                  = ($listingConfig['show_swap_image'])?'swap':'';
    $productConfig                = array_merge( $productConfig, $themeConfig );
    $languageID               = $config->get('config_language_id');

?>
<?php echo $header; ?>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> / </li>
            <?php } ?>
        </ul>

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
      <div class="row">
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } ?>

        <?php require( ThemeControlHelper::getLayoutPath( 'product/preview/horizontal.tpl' ) );  ?>

        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
            <div class="product-view">
              <div style="overflow: auto;       line-height: 25px;">
                <div class="pull-left"><h1 class="heading"><?php echo $heading_title; ?></h1>
                  <?php if($sub_title) {?>
                    <div style="font-size: 12px;    max-width: 201px; margin-top:5px;"> <span><?php echo $sub_title; ?></span></div>
                  <?php } ?>
                </div>

                <div class="pull-left" style="margin-left: 15px;"><?php echo $text_stock; ?> <span><?php echo $stock; ?></span></div>
              </div>

              <hr>
              

              
              <ul class="list-unstyled">
                <?php if ($manufacturer) { ?>
                <li><?php echo $text_manufacturer; ?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
                <?php } ?>

                <?php if ($reward) { ?>
                <li><?php echo $text_reward; ?> <span><?php echo $reward; ?></span></li>
                <?php } ?>

              </ul>
              <div class="price-action">
                <div class="price-div">
                  <div>
                    <?php if ($price) { ?>
                    <ul class="list-unstyled">
                      <?php if (!$special) { ?>
                      <li>
                        <h2><?php echo $price; ?></h2>
                      </li>
                      <?php } else { ?>
                      <li><span style="text-decoration: line-through;"><?php echo $price; ?></span></li>
                      <li>
                        <h2><?php echo $special; ?></h2>
                      </li>
                      <?php } ?>
                      <?php if ($tax) { ?>
                      <li><?php echo $text_tax; ?> <span><?php echo $tax; ?></span></li>
                      <?php } ?>
                      <?php if ($points) { ?>
                      <li><?php echo $text_points; ?> <span><?php echo $points; ?></span></li>
                      <?php } ?>
                      <?php if ($discounts) { ?>
                      <li>
                        <hr>
                      </li>
                      <?php foreach ($discounts as $discount) { ?>
                      <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
                      <?php } ?>
                      <?php } ?>
                    </ul>
                    <?php } ?>
                  </div>

                </div>
                <div class="action">
                  <div class="cart pull-left">
                    <?php if (isset($designable)&&$designable) { ?>
                    <button type="button" id="button-diy" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_diy; ?></button>

                    <?php } else { ?>
                    <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg"><?php echo $button_cart; ?></button>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div id="product">

                <h3><?php echo $text_option; ?></h3>
                <?php if ($options) { ?>
                <ul class="list-unstyled" style="margin:5px 0px;">
            
                  <?php $r = true;foreach($groups as $group){ ?>
                  <li>
                  
                  <?php if ($r) { ?>
                  <?php $group_trigger_id = "group_option_".$group['group_id']; ?>
                  
                    <input type="radio" id="<?php echo $group_trigger_id; ?>" checked name="group_id" value="<?php echo $group['group_id']; ?>" />
                  <?php }else{?>
                    <input type="radio"  name="group_id" value="<?php echo $group['group_id']; ?>" />
                  <?php }?>
                  <?php echo $group['name']; ?>
                  
                  </li>
                <?php $r = false;}?>
                  
                </ul>
          
        
          
        
                <div id="option"></div>

                <hr>





                <?php } ?>
                <?php if ($custom_field) { ?>
                <div>
                  <p><?php echo $custom_field; ?></p>
                </div>
                <?php } ?>
                <?php if ($recurrings) { ?>
                <hr>
                <h3><?php echo $text_payment_recurring ?></h3>
                <div class="form-group required">
                  <select name="recurring_id" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($recurrings as $recurring) { ?>
                    <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                    <?php } ?>
                  </select>
                  <div class="help-block" id="recurring-description"></div>
                </div>

                <?php } ?>
                

                <div class="product-buttons-wrap">
                  <div style="overflow: auto;">
                  <?php if (!$designable) { ?>
                      <div class="product-qyt-action pull-left space-20">
                        <label class="control-label qty hide"><?php echo $entry_qty; ?>:</label>
                        <div class="quantity-adder">
                             
                            <span class="add-down add-action pull-left">
                                <i class="fa fa-minus"></i>
                            </span>
                            <div class="quantity-number pull-left">
                                <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
                            </div>
                            <span class="add-up add-action pull-left">
                                <i class="fa fa-plus"></i>
                            </span>

                        </div>
                      </div>
                  <?php }?>
                  


                  </div>
                  

                  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                  
                  
                  


                  
                </div>
                <?php if ($minimum > 1) { ?>
                <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
                <?php } ?>
              </div>

            </div>
        </div>
      </div>


              <div class="tab-v88 tabs-group">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
            <?php if ($attribute_groups) { ?>
            <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <?php } ?>
            <?php if ($review_status) { ?>
            <li style="display:none"><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
            <?php if ($attribute_groups) { ?>
            <div class="tab-pane" id="tab-specification">
              <table class="table table-bordered">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                  <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <?php } ?>
              </table>
            </div>
            <?php } ?>
            <?php if ($review_status) { ?>
            <div class="tab-pane" id="tab-review">
              <form class="form-horizontal" id="form-review">
                <div id="review"></div>
                <h2><?php echo $text_write; ?></h2>
                <?php if ($review_guest) { ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <input type="text" name="name" value="" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="help-block"><?php echo $text_note; ?></div>
                  </div>
                </div>
                <div class="form-group form-group-v2 required">
                  <div class="col-sm-12">
                    <label class="control-label"><?php echo $entry_rating; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="rating" value="1" />
                    &nbsp;
                    <input type="radio" name="rating" value="2" />
                    &nbsp;
                    <input type="radio" name="rating" value="3" />
                    &nbsp;
                    <input type="radio" name="rating" value="4" />
                    &nbsp;
                    <input type="radio" name="rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
                </div>
                <?php echo $captcha; ?>
                <div class="buttons clearfix">
                  <div class="pull-right">
                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                  </div>
                </div>
                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>
              </form>
            </div>
            <?php } ?>
          </div>
        </div>

      <?php if ($products) {  $heading_title = $text_related; $customcols = 4; ?>
      <div class="panel panel-default product-related"> <?php require( ThemeControlHelper::getLayoutPath( 'common/products_carousel.tpl' ) );  ?>   </div>
      <?php } ?>
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
function goToByScroll(id){
    $('html,body').animate({scrollTop: $("#"+id).offset().top},'slow');   
}
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
				$('.breadcrumb').after('<div class="container"><div class="alert alert-success success-cart">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');

          $('#cart-total-qty').html(json['total_qty']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});

$('#button-diy').on('click', function() {
	$.ajax({
		//url: 'index.php?route=checkout/cart/add',
		//url: '<?php echo $design_box; ?>',
		url: 'index.php?route=product/design/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));
						
						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}
				
				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}
				
				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}
			
			if (json['success']) {
				//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		          
				//$('#cart > button').html('<i class="fa fa-shopping-cart"></i> ' + json['total']);
		
				//$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				//$('#cart > ul').load('index.php?route=common/cart/info ul li');
        var option = json['json_option'];  
				if(opttion = ''){

        }
				
				location.href='<?php echo $design_box .'&product_id='.$product_id;?>';
			}
		}
	});
});

//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});


//--></script>
<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});



$('input[name=\'group_id\']').on('change', function() {
  $('#button-diy').prop('disabled', true);
	$('#option').load('index.php?route=product/product/option&product_id=<?php echo $product_id; ?>'+'&group_id='+$(this).val(),function(){
    $('#button-diy').prop('disabled', false);
     try {
        if (typeof(eval('liveprice_recalc')) == "function") {
            liveprice_recalc();
        }
    } catch(e) {}
    
  });

  $("#option").delegate('button[id^=\'button-upload\']','click', function() {
    var node = this;

    $('#form-upload').remove();

    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

    $('#form-upload input[name=\'file\']').trigger('click');

    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }

    timer = setInterval(function() {
      if ($('#form-upload input[name=\'file\']').val() != '') {
        clearInterval(timer);

        $.ajax({
          url: 'index.php?route=tool/upload',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#form-upload')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $(node).button('loading');
          },
          complete: function() {
            $(node).button('reset');
          },
          success: function(json) {
            $('.text-danger').remove();

            if (json['error']) {
              $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
            }

            if (json['success']) {
              alert(json['success']);

              $(node).parent().find('input').attr('value', json['code']);
              $(node).parent().find('span').text(json['filename']);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    }, 500);
  });
	
});
<?php if(isset($group_trigger_id)){?>
  $('#<?php echo $group_trigger_id;?>').trigger('change');
<?php }?>




$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#review').after('<div class="alert alert-success success-cart"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}
		}
	});
});

//--></script>
<?php if( $productConfig['product_enablezoom'] ) { ?>
<script type="text/javascript" src=" catalog/view/javascript/jquery/elevatezoom/elevatezoom.js"></script>
<script type="text/javascript">
    var zoomCollection = '<?php echo $productConfig["product_zoomgallery"]=="basic"?".product-image-zoom":"#image";?>';
    $( zoomCollection ).elevateZoom({
    <?php if( $productConfig['product_zoommode'] != 'basic' ) { ?>
    zoomType        : "<?php echo $productConfig['product_zoommode'];?>",
    <?php } ?>
    lensShape : "<?php echo $productConfig['product_zoomlensshape'];?>",
    lensSize    : <?php echo (int)$productConfig['product_zoomlenssize'];?>,
    easing:true,
    gallery:'image-additional-carousel',
    cursor: 'pointer',
    responsive:false,
    galleryActiveClass: "active"
  });

</script>
<?php } ?>
<?php echo $footer; ?>