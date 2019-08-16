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
    <?php if($show_content) { ?>
<div class="toggle-overlay-container">
    <div class="search-box"> <?php echo $search; ?> </div>
    <div class="dropdown-toggle-button" data-target=".toggle-overlay-container">x </div>
</div>
<nav id="top" class="topbar topbar-v1">
    <div class="container">
        
        <div id="top-links" class="nav top-link pull-left">
            <ul class="list-inline">

                <?php if ($logged) { ?>
                <li class="top-links-li"><a  href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                <li class="top-links-li"><a  href="<?php echo $design; ?>" ><?php echo $text_my_design; ?></a></li>
                  <?php } else { ?>
                <li class="quick-login top-links-li"><a class="quick_signup"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $signin_or_register; ?></span></a></li>
                <?php } ?>
                
                <li class="top-links-li"><a class="wishlist" href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a></li>
    
                <li class="top-links-li"><a class="shoppingcart" href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a></li>
                <li class="top-links-li"><a class="last" href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li>
                <?php if ($logged) { ?>
                  <li class="top-links-li">  <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                  <?php }?> 
                
            </ul>
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
<header class="header-v1 header">
    <div class="container">
        <div class="row">
            <div class="logo pull-left">

                    <?php if ($logo) { ?>
                    <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>"
                                                        alt="<?php echo $name; ?>" class="img-responsive"/></a>
                    <?php } else { ?>
                    <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
                    <?php } ?>

            </div>

                <div class="bo-mainmenu">
                    <?php  require( ThemeControlHelper::getLayoutPath( 'common/parts/mainmenu.tpl' ) );   ?>
                </div>



                <div class="search-cart">
                    <?php echo $cart; ?>
                    <div id="search-container" class="search-box-wrapper pull-right">
                        <div class="pbr-dropdow-search dropdown">
                            <button class="btn btn-search radius-x dropdown-toggle-overlay" type="button"
                                    data-target=".toggle-overlay-container">
                                <span class="fa fa-search"></span>
                            </button>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</header>
    <?php } ?>
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