<?php
  $objlang = $this->registry->get('language');
  $ourl = $this->registry->get('url');
  $button_cart = $objlang->get("button_cart");
?>
<div class="product-block">

    <?php if ($product['thumb']) {    ?>
      <div class="image">
        <?php if( $product['special'] ) {   ?>
          <span class="product-label sale-exist"><span class="product-label-special"><?php echo $objlang->get('text_sale'); ?></span></span>
        <?php } ?>

        <div class="product-img">
          <a class="img" title="<?php echo $product['name']; ?>" href="<?php echo $product['href']; ?>">
            <img class="img-responsive" src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
          </a>

        </div>
          
      </div>
    <?php } ?>
  <div class="product-meta">
      <div class="top">
        <h6 class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h6>
        

         <?php if ($product['other']) { ?>
          <div>
          <h6 class="other"><?php echo $product['other']; ?></h6>
          </div>
        <?php } ?>

        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) {  ?>
            <span class="price-new"><?php echo $product['price']; ?></span>
            <?php if( preg_match( '#(\d+).?(\d+)#',  $product['price'], $p ) ) { ?>
            <?php } ?>
          <?php } else { ?>
            <span class="price-new"><?php echo $product['special']; ?></span>
            <span class="price-old"><?php echo $product['price']; ?></span>
            <?php if( preg_match( '#(\d+).?(\d+)#',  $product['special'], $p ) ) { ?>
            <?php } ?>

          <?php } ?>
            <a style="float: right;font-size: 18px;padding: 0px;color:#666;background: none;border:none" href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
        </div>
        <?php } ?>

        <?php if ($product['tag']) { ?>
        <!--
          <div>
          <h6 class="tag"><?php echo $text_tag; ?><?php echo $product['tag']; ?></h6>
          </div>
          -->
        <?php } ?>
        
        <?php if ($product['rating']) { ?>
          <div class="rating">
            <?php for ($is = 1; $is <= 5; $is++) { ?>
            <?php if ($product['rating'] < $is) { ?>
            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
            <?php } else { ?>
            <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i>
            </span>
            <?php } ?>
            <?php } ?>
          </div>
        <?php } ?>


        
       
        <?php if( isset($product['description']) ){ ?>
          <p class="description"><?php echo utf8_substr( strip_tags($product['description']),0,1000);?>...</p>
        <?php } ?>

       
      </div>
        
  </div>
</div>





