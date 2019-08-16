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
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      
      <?php if ($orders) { ?>
      <div class="">
        
        <?php foreach ($orders as $order) { ?>
        
        
        <div class="mobile-order-list">
          <div class="mobile-order-base">
            <div class="row">
              <div class="order-top-base col-xs-6"> <b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?></div>
              <div class="order-top-base col-xs-6"><div class="text-right-order-top"><b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?></div></div>
              
            </div>
          </div>
          
          <div class="mobile-order-product">
            
            <div class="mobile-cart">
              <?php foreach ($order['products'] as $product) { ?>
              <div class="cart-item">
                <div class="row">
                  <div class="product-top-name hidden-xs">
                            <span><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></span>
                  </div>
                  <div class="col-xs-4 col-sm-4 col-md-4">
                      
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
                        <div class="product-name visible-xs">
                          <span><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></span>
                        </div>
                  </div>
                  <div class="col-xs-8 col-sm-8 col-md-8">
                  <div class="row order-list-p-name">         
                       <div class="col-xs-12 col-sm-9 col-md-9">
                          <div class="row">
                             <div class="col-xs-9 col-sm-9 col-md-9">
                                 <div class="cart-item-name">


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
                                    <small><span class="design-item-bold"> <?php echo $option['name']; ?></span>: <?php echo $option['value']; ?></small>
                                    <?php } ?>
                                    <?php } ?>
                                  
                                </div>
                             </div>
                             <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="text-right-10">
                                  <span>x <?php echo $product['quantity']; ?></span>
                                </div>

                                <div class="text-right-10 visible-xs mobile-view-eye">
                                 <a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i>
                                  <div style="color:#000;">
                                 <span><?php echo $button_view;?></span>
                                 </div></a>
                                </div>
                             </div>

                          </div>
                       </div>
                       <div class="col-xs-12 col-sm-3 col-md-3">
                       <div class="text-right-10 text-right-total">
                         <div class="">
                            <?php echo $column_total; ?>:<?php echo $product['total']; ?>
                          </div>
                          <div class="">
                            <?php echo $column_one_time_fee; ?>: <?php echo $product['one_time_fee']; ?>
                          </div>
                          <div class="text-right-10 hidden-xs view-eye">
                                  <a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i>
                                  <div style="color:#000;">
                                 <span><?php echo $button_view;?></span>
                                 </div></a>
                          </div>
                       </div>
                          
                       </div>
                  </div>
                  </div>
                </div>
                <div class="row">
                  <div class="mobile-total">
                   
                   
                  </div>
                  
                </div>
                
              </div>
              <?php } ?>
            <?php foreach ($order['vouchers'] as $vouchers) { ?>
            <div class="cart-item">
                <div class="row">
                    <div class="col-xs-8 col-sm-3 col-md-3"><?php echo $vouchers['description']; ?></div>
                    <div class="col-xs-4 col-sm-3 col-md-3"><?php echo $vouchers['code']; ?></div>
                    <div class="col-xs-6 col-sm-3 col-md-3"><?php echo $vouchers['amount']; ?></div>
                    <div class="col-xs-6 col-sm-3 col-md-3" style="text-align: right;">
                        <div class="input-group btn-block" >
                            <span>x 1</span>
                        </div>
                        <div class="text-right-10 visible-xs mobile-view-eye" style="margin-right: 0px;">
                            <a href="<?php echo $order['href']; ?>" data-toggle="tooltip" style="padding:10px 0px;" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i>
                                <div style="color:#000;">
                                    <span><?php echo $button_view;?></span>
                                </div></a>
                        </div>
                        <div class="text-right-10 hidden-xs view-eye">
                            <a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i>
                                <div style="color:#000;">
                                    <span><?php echo $button_view;?></span>
                                </div></a>
                        </div>
                    </div>

                </div>
            </div>
            <?php } ?>
            </div>
          </div>
        </div>
        <?php }?>
        
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>