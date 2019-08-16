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
<div class="container">
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>
      <div  id="short-cut" style="margin-top: 30px;">
      <div class="line"></div>
         <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
          <div class="media">
            <div class="media-left"><a href="<?php echo $edit; ?>"><img src="image/data/1.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></div>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

         <div class="media">
            <div class="media-left"><a href="<?php echo $transaction; ?>"><img src="image/data/2.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $transaction; ?>"><?php echo $text_balance; ?>  &nbsp;&nbsp;<?php echo $balance; ?></a></div>

            </div>
          </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

         <div class="media">
            <div class="media-left"><a href="<?php echo $password; ?>"><img src="image/data/3.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></div>

            </div>
          </div>
        </div>


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

         <div class="media">
            <div class="media-left"><a href="<?php echo $address; ?>"><img src="image/data/11.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></div>

            </div>
          </div>
        </div>


        </div>
        <div class="line"></div>
        <div class="row">

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $project; ?>"><img src="image/data/4.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $project; ?>"><?php echo $text_project; ?></a></div>

            </div>
          </div>
        </div>



        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $order; ?>"><img src="image/data/6.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></div>

            </div>
          </div>
        </div>


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $wishlist; ?>"><img src="image/data/12.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></div>

            </div>
          </div>
        </div>



        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $return; ?>"><img src="image/data/7.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></div>

            </div>
          </div>
        </div>


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $return; ?>"><img src="image/data/5.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></div>

            </div>
          </div>
        </div>


         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $coupon; ?>"><img src="image/data/10.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></div>

            </div>
          </div>
        </div>






         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $newsletter; ?>"><img src="image/data/13.png" /></a></div>
            <div class="media-body">
              <div class="media-heading"><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></div>

            </div>
          </div>
        </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="">

            <div class="media" id="button-image">
              <div class="media-left"><a href="javascript:void(0);return false;"><img src="image/data/18.png" /></a></div>
              <div class="media-body">
                <button class="media-heading"  style="border: none;background: none;padding: 0;"><?php echo $text_myphoto; ?></button>

              </div>
            </div>
          </div>

        </div>
        <div class="line"></div>
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

            <div class="media">
              <div class="media-left"><a href="<?php echo $task; ?>"><img src="image/data/9.png" /></a></div>
              <div class="media-body">
                <div class="media-heading"><a href="<?php echo $task; ?>"><?php echo $text_my_task; ?></a></div>

              </div>
            </div>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

            <div class="media">
              <div class="media-left"><a href="<?php echo $department; ?>"><img src="image/data/8.png" /></a></div>
              <div class="media-body">
                <div class="media-heading"><a href="<?php echo $department; ?>"><?php echo $text_my_department; ?></a></div>

              </div>
            </div>
          </div>
        </div>
        <div class="line"></div>
        <div class="row">
        <?php //+mod by yp start
          if ($text_link_to_affiliate) { ?>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

          <div class="media">
            <div class="media-left"><a href="<?php echo $link_to_affiliate; ?>"><img src="image/data/8.png" /></a></div>
            <div class="media-body">
              <div class="media-heading">

                  <ul class="list-unstyled">
                    <li><a href="<?php echo $link_to_affiliate; ?>"><?php echo $text_link_to_affiliate; ?></a></li>
              <?php if ($affiliate_logged) { ?>
                    <li><a href="<?php echo $affiliate_info; ?>"><?php echo $text_affiliate_info; ?></a></li>
                    <li><a href="<?php echo $affiliate_tracking; ?>"><?php echo $text_affiliate_tracking; ?></a></li>
                    <li><a href="<?php echo $affiliate_transaction; ?>"><?php echo $text_affiliate_transaction; ?></a></li>
                <?php } ?>
                  </ul>


              </div>

            </div>

          </div>
        </div>
        <?php //+mod by yp end
        } ?>
        </div>


        </div>
      </div>
     
      
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>