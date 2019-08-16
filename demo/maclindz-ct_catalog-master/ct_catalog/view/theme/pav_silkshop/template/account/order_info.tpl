<?php echo $header; ?>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> /</li>
            <?php } ?>
        </ul>
        
    </div>
</div>
<div class="container">

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
     

      <div class="mobile-order-info">
        <div class="mobile-order-base">
          <div class="row">
            <div class="item-left col-xs-6 col-md-3"><b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?></div>
            <div class="item-left col-xs-6 col-md-3"> <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></div>
            <div class="item-right col-xs-6 col-md-3"><?php if ($payment_method) { ?>
              <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
              <?php } ?></div>

            <div class="item-right col-xs-6 col-md-3"><?php if ($shipping_method) { ?>
              <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
              <?php } ?></div>
          </div>
          
          <div class="row">
            <div class="item-left col-xs-6"><b><?php echo $text_status; ?></b> <?php echo $status; ?></div>
          </div>
        </div>
        <div class="mobile-order-address">
            <div class="row">
              <div class="item-left col-md-12"><b><?php echo $text_address_customer; ?></b> <?php echo $address_customer; ?>, <?php echo $address_telephone; ?>, <?php echo $shipping_address; ?></div>
              
            </div>
            <div class="row">
              <div class="item-left col-xs-12"><b><?php echo $text_shipping_address; ?></b> <?php echo $shipping_address; ?></div>
            </div>


        </div>
        <div class="mobile-order-product">
            



        <div class="mobile-cart">
        <?php foreach ($products as $product) { ?>
        <div class="cart-item">
        <div class="row">
          <div class="pc-product-name" >
              <span><?php echo $product['name']; ?></span>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3">
              <div class="cart-item-image">
              <?php if ($product['thumb']) { ?>
                  <?php if( isset($product['design']) && $product['design'] != false && isset($product['design']->rowid)) {

                  $design = $product['design'];
                  /* images */
                  if (isset($design->images))
                  {
                  $images = json_decode(str_replace('&quot;', '"', $design->images), true);
                  if (count($images) > 0)
                  {
                  $img = '';
                  foreach($images as $view => $src)
                  {
                  $img .= '<div class="img-thumbnail" style="margin:2px;">'
                      .'<a target="_blank" href="'.$base_url.'index.php?route=tshirtecommerce/designer&product_id='.$design->design_product_id.'&parent_id='.$design->product_id."&cart_id=".$design->rowid.'" class="pull-left" title="Click to view design"><img  src="'.$base_url.'/tshirtecommerce/'.$src.'" alt="" title="'.$tshirtecommerce_link_title_edit.'"></a>';

                      if(isset($tshirtecommerce_downloadable) && $tshirtecommerce_downloadable == 1 && isset($product['store_temp']) && count($product['store_temp']) == 0)
                      {
                      $img .= '<br /><center><a target="_blank" href="'.$base_url.'/tshirtecommerce/design.php?key='.$design->rowid.'&view='.$view.'" title="'.$tshirtecommerce_link_title_download.'" alt="'.$tshirtecommerce_link_text_download.'">'.$tshirtecommerce_link_text_download.'</a></center>';
                      }
                      $img .=	'</div>';
                  }
                  echo $img;
                  }
                  }
                  ?>
                  <?php }else{ ?>
                  <a href="<?php echo $product['edituri']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?>

                        <?php } ?>
              </div>

              
          </div>
          <div class="col-xs-4 col-sm-4 col-md-4">
              <!--r_start-->
              <div class="cart-item-name" style="display:<?php if( isset($product['design']) && $product['design'] != false && isset($product['design']->rowid)) echo 'block';?>">


                  <?php
                  if( isset($product['design']) && $product['design'] != false && isset($product['design']->rowid)) {

                      echo '<a href="'.$base_url.'index.php?route=tshirtecommerce/designer&product_id='.$design->design_product_id.'&parent_id='.$design->product_id.'&cart_id='.$design->rowid.'" >'.$tshirtecommerce_designer_cart_edit.'</a>(<a href="'.$product['href'].'">'. $product['name'].'</a>)';
                  }else{
                      if(!empty($product['diy_action']['href'])){
                        echo '<a href="'.$product['diy_action']['copy'].'" >'.$product['diy_action']['diydesign_name'].'</a>(<a href="'.$product['href'].'">'. $product['name'].'</a>)';
                      }
                  }
                  ?>

            
             
                      <?php if ($product['option']) { ?>
                      <?php foreach ($product['option'] as $option) { ?>
                      <br />
                      <small><span class="design-item-bold"> <?php echo $option['name']; ?></span>: <?php echo $option['lvalue']; ?></small>
                      <?php } ?>
                      <?php } ?>
                     
              </div>
              <!--r_end-->
              <!--t_design-->

          </div>


            <div class="col-xs-3 col-sm-3 col-md-3 mobile-order-price">
                <div>
                  <span><?php echo $column_price; ?>  <?php echo $product['price']; ?></span>
                </div>
               
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 mobile-order-qty">
                <div>
                  <span>x <?php echo $product['quantity']; ?></span>
                </div>
               
            </div>
              </div>
            <div class="row">

            <div class="col-xs-8 col-sm-8 col-md-8 mobile-order-total">
              <div class="order-info-action" >
               <div class="pull-left" style="display:<?php if( isset($product['design']) && $product['design'] != false && isset($product['design']->rowid)) echo 'none';?>">
               <a href="<?php echo $product['diy_action']['copy']; ?>" data-toggle="tooltip" title="<?php echo $text_copy_edit; ?>" class="btn btn-primary"><img src="image/data/copy.png" /><div><span><?php echo $text_copy_edit;?></span></div></a>
               </div>
               <div class="pull-left actions">

                   <?php
                    $design = $product['design'];
                   if(isset($design->images) && !empty($design->images))
                   {


                   }else{
                      echo '<a href="'.$product['reorder'].'" data-toggle="tooltip" title="'.$button_reorder.'" class="btn btn-primary"><img src="image/data/rebuy.png" /><div><span>'.$button_reorder.'</span></div></a>';
                   }
                   ?>
                </div>
                <div class="cart-item-del pull-left actions">
                <a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-primary"><img src="image/data/return.png" /><div><span><?php echo $button_return;?></span></div></a>
                </div>
              </div>
            </div>

            <div class="col-xs-4 col-sm-4 col-md-4 mobile-order-total">
              <div class="">
              <?php echo $column_total; ?>:<?php echo $product['total']; ?>
              </div>
              <div class="">
               <?php echo $column_one_time_fee; ?>: <?php echo $product['one_time_fee']; ?>
              </div>
            </div>
            

        </div>
          
      
        </div>
        <?php } ?>
        <?php foreach ($vouchers as $vouchers) { ?>
        <div class="cart-item">
            <div class="row row-first">
                <div class="col-xs-8 col-sm-3 col-md-3"><?php echo $vouchers['description']; ?></div>
                <div class="col-xs-4 col-sm-3 col-md-3"><?php echo $vouchers['code']; ?></div>
                <div class="col-xs-6 col-sm-3 col-md-3"><?php echo $vouchers['amount']; ?></div>
                <div class="col-xs-6 col-sm-3 col-md-3"><div class="input-group btn-block" style="text-align: right;">
                        <span>x 1</span>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        </div>





        </div>

        <div class="order-info-total">
              <?php foreach ($totals as $total) { ?>
               <div class="text-right order-info-total-item"><b><?php echo $total['title']; ?></b>: <?php echo $total['text']; ?></div>
              <?php } ?>
       </div>

      </div>




        <?php if ($comment) { ?>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left"><?php echo $text_comment; ?></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-left"><?php echo $comment; ?></td>
            </tr>
            </tbody>
        </table>
        <?php } ?>
        <?php if ($histories) { ?>
        <h3><?php echo $text_history; ?></h3>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left"><?php echo $column_date_added; ?></td>
                <td class="text-left"><?php echo $column_status; ?></td>
                <td class="text-left"><?php echo $column_comment; ?></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($histories as $history) { ?>
            <tr>
                <td class="text-left"><?php echo $history['date_added']; ?></td>
                <td class="text-left"><?php echo $history['status']; ?></td>
                <td class="text-left"><?php echo $history['comment']; ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } ?>
      
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>