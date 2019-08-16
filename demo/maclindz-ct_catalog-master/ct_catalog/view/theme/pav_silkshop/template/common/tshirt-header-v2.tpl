<?php
if(isset($_COOKIE["customer_id_cookie"]) && $_COOKIE["customer_id_cookie"]!=''){
  $_SESSION['default']['customer_id']=$_COOKIE["customer_id_cookie"];
?>
<script>
if (sessionStorage.reloadHeader) {
} else {
    sessionStorage.reloadHeader = 1;
    location.reload();
}

</script>
<script>
    function leave(){
        return "我在这写点东西...";
    }
</script>
<?php
}
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" class="<?php echo $helper->getDirection(); ?>" lang="<?php echo $lang; ?>">
  <!--<![endif]-->
  <?php  require( ThemeControlHelper::getLayoutPath( 'common/parts/head.tpl' ) );   ?>
  <body class="row-offcanvas row-offcanvas-left <?php echo $class; ?> layout-<?php echo $template_layout; ?> ">
    <div id="page">
    <nav class="navbar navbar-default navbar-fixed-bottom home_bottom_menu hidden-md hidden-lg" role="navigation">
     
      <div>
          <ul class="nav navbar-nav">
              <li class="<?php if($menu_route=='common/home') echo 'active';?>"><a href="<?php echo $menu_home;?>"><div class="menu-icon"><i class="fa fa-home"></i></div><div><span><?php echo $text_menu_home;?></span></div></a></li>
              <li class="<?php if($menu_route=='product/latest') echo 'active';?>"><a href="<?php echo $menu_newest;?>"><div class="menu-icon"><i class="fa fa-star-o"></i></div><div><span><?php echo $text_menu_newest;?></span></div></a></li>
              <li class="<?php if($menu_route=='product/category') echo 'active';?>"><a href="<?php echo $menu_category;?>"><div class="menu-icon"><i class="fa fa-list"></i></div><div><span><?php echo $text_menu_category;?></span></div></a></li>
              <li class="<?php if($menu_route=='checkout/cart') echo 'active';?>"><a href="<?php echo $menu_cart;?>"><div class="menu-icon"><i class="fa fa-shopping-cart"></i></div><div><span><?php echo $text_menu_cart;?></span></div></a></li>
              <li class="<?php if($menu_route=='account/account') echo 'active';?>"><a href="<?php echo $menu_my;?>"><div class="menu-icon"><i class="fa fa-user-o"></i></div><div><span><?php echo $text_menu_my;?></span></div></a></li>
          </ul>
      </div>
  </nav>
      <div class="toggle-overlay-container">
        <div class="dropdown-toggle-button" data-target=".toggle-overlay-container">x</div>
      </div>
      <nav id="top" class="topbar topbar-v2">
        <div class="container">
          
          <div class="pull-left welcome">
              <div class="hidden-xs hidden-sm">
                <?php echo $welcome;?>
              </div>
              <div class="hidden-md hidden-lg header-top-log">
               <?php if ($logo) { ?>
                  <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>"
                  alt="<?php echo $name; ?>" class="img-responsive"/></a>
                  <?php } else { ?>
                  <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
                  <?php } ?>
              </div>
            </div>
          <div class="show-mobile hidden-lg hidden-md pull-right">
            
            <div class="quick-access pull-left">
              <div class="quickaccess-toggle">
                <i class="fa fa-list"></i>
              </div>
              
              <div class="inner-toggle">
                <ul class="links pull-left">
                  <?php if ($logged) { ?>

                    <li><a class="account" href="<?php echo $account; ?>"><i class="fa fa-user"></i><?php echo $text_account; ?></a></li>
                    <li class="top-links-li"><a  href="<?php echo $design; ?>" ><i class="fa fa-book"></i><?php echo $text_my_design; ?></a></li>
                    <li class="top-links-li"><a  href="<?php echo $order; ?>" ><i class="fa fa-history header-icon"></i><?php echo $text_my_order; ?></a></li>
                  <?php } else { ?>
                    <?php if($is_weixin) { ?>
                    <li><a href="<?php echo $weixinlogin;?>"><i class="fa fa-user header-icon"></i> <?php echo $signin_or_register; ?></span></a></li>
                    <?php } else { ?>
                    <li><a class="quick_signup"><i class="fa fa-user header-icon"></i> <?php echo $signin_or_register; ?></span></a></li>
                    <?php }  ?>

                  <?php }  ?>
                  <li><a class="wishlist" href="<?php echo $wishlist; ?>" id="mobile-wishlist-total"><i class="fa fa-heart header-icon"></i><?php echo $text_wishlist; ?></a></li>
                  <li><a class="shoppingcart" href="<?php echo $shopping_cart; ?>"><i class="fa fa-shopping-cart"></i><?php echo $text_shopping_cart; ?></a></li>
                  
                  <?php if ($logged) { ?>
                    <li class="top-links-li"><a href="<?php echo $logout; ?>"><i class="fa fa-sign-out"></i><?php echo $text_logout; ?></a></li>
                  <?php }  ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="current-lang pull-right">
            <div class="btn-group box-language">
              <?php echo $language; ?>
            </div>
            <!-- currency -->
            <div class="btn-group box-currency">
              <?php echo $currency; ?>
            </div>
          </div>
        </div>
      </nav>
      <?php echo $quicksignup; ?>
      <header class="header header-v2">
        <div class="part-1 hidden-xs hidden-sm">
          <div class="container">
            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12 pull-left">
                <div class="logo">
                  <?php if ($logo) { ?>
                  <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>"
                  alt="<?php echo $name; ?>" class="img-responsive"/></a>
                  <?php } else { ?>
                  <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
                  <?php } ?>
                </div>
              </div>
              <div class="col-md-9 col-sm-9 col-xs-12 pull-right hidden-xs hidden-sm ">
                <ul class="list-inline pull-right header-right">
                  <?php if ($logged) { ?>
                  <li class="top-links-li"><a  href="<?php echo $account; ?>"><i class="fa fa-user-circle  header-icon"></i><?php echo $text_account; ?></a></li>
                  <li class="top-links-li"><a  href="<?php echo $design; ?>" ><i class="fa fa-book header-icon"></i><?php echo $text_my_design; ?></a></li>

                  <?php } else { ?>
                  <li class="quick-login top-links-li"><a class="quick_signup"><i class="fa fa-user header-icon"></i> <?php echo $signin_or_register; ?></span></a></li>
                  <?php } ?>
                  
                  <li class="top-links-li"><a class="wishlist" href="<?php echo $order; ?>" ><i class="fa fa-history header-icon"></i><span><?php echo $text_my_order; ?></span></a></li>
                  
                  <li class="top-links-li"><a class="shoppingcart" href="<?php echo $shopping_cart; ?>"><i class="fa fa-shopping-cart header-icon "></i><span><?php echo $text_shopping_cart; ?></span> (<span id="cart-total-qty"><?php echo $shopping_cart_total; ?></span>)</a></li>
                
                  
                  <?php if ($logged) { ?>
                  <li class="top-links-li">  <a href="<?php echo $logout; ?>"><i class="fa fa-sign-out  header-icon"></i><?php echo $text_logout; ?></a></li>
                  <?php }?>
                  
                </ul>
              </div>
              <div class="header-middle-text col-md-4 col-sm-4 col-xs-12">
                <?php echo $text_header_text; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="part-2">
          <div class="container">
            <div class="row" style="position: relative;">
              <div class="bo-mainmenu">
                <?php  require( ThemeControlHelper::getLayoutPath( 'common/parts/mainmenu.tpl' ) );   ?>
              </div>
              <div  class="header-v2-search">
                     <?php echo $search; ?>
              </div>
              
            </div>
          </div>
        </div>
      </header>
      <!-- sys-notification -->
      <div id="sys-notification">
        <div class="container">
          <div id="notification"></div>
        </div>
      </div>
      <!-- /sys-notification -->
      <?php
      /**
      * Showcase modules
      * $ospans allow overrides width of columns base on thiers indexs. format array( column-index=>span number ), example array( 1=> 3 )[value from 1->12]
      */
      //$modules = $helper->getCloneModulesInLayout( $blockid, $layoutID );
      $blockid = 'slideshow';
      $blockcls = "hidden-xs hidden-sm";
      $ospans = array(1=>12);
      require( ThemeControlHelper::getLayoutPath( 'common/block-cols.tpl' ) );
      ?>
      <?php
      /**
      * Showcase modules
      * $ospans allow overrides width of columns base on thiers indexs. format array( column-index=>span number ), example array( 1=> 3 )[value from 1->12]
      */
      $blockid = 'showcase';
      $blockcls = 'hidden-xs hidden-sm';
      $ospans = array(1=>12);
      require( ThemeControlHelper::getLayoutPath( 'common/block-cols.tpl' ) );
      ?>
      <?php
      /**
      * promotion modules
      * $ospans allow overrides width of columns base on thiers indexs. format array( column-index=>span number ), example array( 1=> 3 )[value from 1->12]
      */
      $blockid = 'promotion';
      $blockcls = "hidden-xs hidden-sm";
      $ospans = array(1=>12, 2=>12);
      require( ThemeControlHelper::getLayoutPath( 'common/block-cols.tpl' ) );
      ?>
      <div class="maincols">