<?php echo $header; ?>
<div class="breadcrumb">
  <div class="container">
    <ul>
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> /</li>
      <?php } ?>
    </ul>
    <h1 class="breadcrumb-heading"><?php echo $heading_title; ?></h1>
  </div>
</div>
<div class="cart-container container">
  <?php if ($attention) { ?>
  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div style="margin-bottom: 20px;">
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="mobile-cart">
          <?php foreach ($products as $product) { ?>

          <div class="cart-item">

            <div class="row row-first hidden-xs">
                <div class="col-xs-12 col-sm-6 col-md-6 pb0" >
                  <div class="row">
                    <div class="col-xs-3 col-sm-3 col-md-3 pb0">
                      <div class="cart-product-name">
                        <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                      </div>
                    </div>
                </div>
              </div>
            </div>

            <div class="row row-first">
              <div class="col-xs-12 col-sm-7 col-md-7">
                <div class="row">
                  <div class="col-xs-3 col-sm-3 col-md-3">
                    <div calss="cart-item-image">
                      <?php if ($product['thumb']) { ?>
                      <a href="<?php echo $product['diydesign_edituri']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-xs-7 col-sm-6 col-md-6">
                    <div class="cart-item-name">
                      
                      <?php if($product['diydesign_edituri']){ ?>
                      <span style="margin-right:10px"><a  href="<?php echo $product['diydesign_edituri']; ?>">
                      <?php echo $product['diydesign_name']; ?>  </a></span>
                      
                      <?php  } ?>
                      <a class="visible-xs-inline" href="<?php echo $product['href']; ?>">(<?php echo $product['name']; ?>)</a>
                      
                      <?php if (!$product['stock']) { ?>
                      <span class="text-danger">***</span>
                      <?php } ?>
                      <?php if ($product['option']) { ?>
                      <?php foreach ($product['option'] as $option) { ?>
                      <br />
                      <small><span class="design-item-bold"> <?php echo $option['name']; ?></span>: <?php echo $option['lvalue']; ?></small>
                      <?php } ?>
                      <?php } ?>
                      <?php if ($product['reward']) { ?>
                      <br />
                      <small><?php echo $product['reward']; ?></small>
                      <?php } ?>
                      <?php if ($product['recurring']) { ?>
                      <br />
                      <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-xs-2 col-sm-3 col-md-3">
                    <div calss="cart-item-price">
                      <span><?php echo $product['price']?></span>
                    </div>
                    <div class="cart-item-del hidden-sm hidden-md hidden-lg">
                      <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger cart-mobile-trash" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-recycle"></i></button>
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-5 col-md-5">
                <div class="row">
                  <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="input-group btn-block cart-input-qty" style="max-width: 200px;">
                      <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control cart-mobile-qty-input" />
                      <span class="input-group-btn">
                        <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary cart-mobile-refresh"><i class="fa fa-refresh"></i></button>
                      </span></div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                      <div class="sub-total">
                        <?php echo $column_total; ?>:<?php echo $product['total']; ?>
                      </div>
                      <div class="sub-total">
                        <?php echo $column_one_time_fee; ?>: <?php echo $product['one_time_fee']; ?>
                      </div>
                    </div>
                  </div>
                  <div class="row hidden-xs">
                    <div class="cart-pc-del">
                      <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger cart-mobile-trash" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-recycle"></i></button>
                    </div>
                  </div>
                </div>
                
                
                
              </div>
              
              
            </div>
            <?php } ?>
          <?php foreach ($vouchers as $vouchers) { ?>
          <div class="cart-item">
            <div class="row row-first">
              <div class="col-xs-8 col-sm-3 col-md-3"><?php echo $vouchers['description']; ?></div>
              <div class="col-xs-4 col-sm-3 col-md-3"><?php echo $vouchers['amount']; ?></div>
              <div class="col-xs-6 col-sm-3 col-md-3"><div class="input-group btn-block" style="max-width: 200px;">
                  <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                  </div>
              </div>

              <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="cart-del" style="text-align:right;">
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger cart-mobile-trash" onclick="voucher.remove('<?php echo $vouchers['key']; ?>');"><i class="fa fa-recycle"></i></button>
                </div>
              </div>
            </div>

          </div>
          <?php } ?>
          </div>
        </form>
        
        <?php if ($coupon || $voucher || $reward || $shipping) { ?>
       
        <div class="panel-group panel-checkout" id="accordion"><?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?><?php echo $shipping; ?></div>
        <?php } ?>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-8">
            <div class="row">
              <?php foreach ($totals as $total) { ?>
              
                <div class="text-right total-item"><strong><?php echo $total['title']; ?>:</strong> <?php echo $total['text']; ?></div>
                
              
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="buttons">
          <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
        </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
  </div>
  <?php echo $footer; ?>