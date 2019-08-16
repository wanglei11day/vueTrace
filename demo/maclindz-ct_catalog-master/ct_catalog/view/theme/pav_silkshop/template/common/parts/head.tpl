<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/******************************************************
* @package Pav Opencart Theme Framework for coo-too
* @version 1.1
* @author http://www.pavothemes.com
* @copyright Copyright (C) Augus 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
* @license   GNU General Public License version 2
*******************************************************/
$config = $this->registry->get('config');
// $this->language->load('module/themecontrol');
$themeName =  $config->get('config_template');
$themeConfig = array_merge( array('header' => ''), $config->get('themecontrol') );
 

/* Add scripts files */
$helper->addScript( 'catalog/view/javascript/jquery/jquery-2.1.1.min.js' );
$helper->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
$helper->addScript( 'catalog/view/javascript/bootstrap/js/bootstrap.min.js' );
$helper->addScript( 'catalog/view/javascript/common.js' );
$helper->addScript( 'catalog/view/theme/'.$themeName.'/javascript/common.js' );
$helper->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
$helper->addScript('catalog/view/javascript/pavdeals/countdown.js');
$helper->addScript('catalog/view/javascript/jquery/timer.jquery.js');

if( isset($themeConfig['catalog_mode']) && $themeConfig['catalog_mode'] ){
	$cart = null;
}
$logoType = $helper->getConfig('logo_type','logo-theme');
$headerlayout = $themeConfig['header_layout'];

$template_layout = $helper->getConfig('template_layout');
$skin = $helper->getConfig('skin');

// enable panel tool
if( $helper->getConfig('enable_paneltool') ){
    if( $helper->getParam('headerlayout') ){
        $headerlayout = $helper->getParam('headerlayout');
    }
    if($helper->getParam('layout')){
        $template_layout = $helper->getParam('layout');
    }
    $helper->addCss( 'catalog/view/theme/'.$themeName.'/stylesheet/paneltool.css' );
    $helper->addScript( 'catalog/view/javascript/jquery/colorpicker/js/colorpicker.js' );
    $helper->addCss( 'catalog/view/javascript/jquery/colorpicker/css/colorpicker.css' );
}

$helper->addScriptList( $scripts );
$ctheme=$helper->getConfig('customize_theme');
if( file_exists(DIR_TEMPLATE.$themeName.'/stylesheet/customize/'.$ctheme.'.css') ) {
    $helper->addCss( 'catalog/view/theme/'.$themeName.'/stylesheet/customize/'.$ctheme.'.css'  );
}
$helper->addCss( 'catalog/view/javascript/font-awesome/css/font-awesome.min.css' );

if( file_exists(DIR_TEMPLATE.$themeName.'/stylesheet/animate.css') ) {
    $helper->addCss( 'catalog/view/theme/'.$themeName.'/stylesheet/animate.css'  );
}

$helper->addCss('catalog/view/javascript/jquery/magnific/magnific-popup.css');
$helper->addCss('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
$helper->addCss( 'catalog/view/theme/'.$themeName.'/stylesheet/fonts.css'  );

$helper->addCssList( $styles );
$logoType = $helper->getConfig('logo_type','logo-theme'); ?>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ( isset($icon) && $icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <?php foreach ($helper->getCssLinks() as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <?php foreach( $helper->getScriptFiles() as $script )  { ?>
    <script type="text/javascript" src="<?php echo $script; ?>"></script>
    <?php } ?>

    <!-- FONT -->
    <?php 
      if( isset($themeConfig['enable_customfont']) && $themeConfig['enable_customfont'] ){
      $css=array();
      $link = array();
      for( $i=1; $i<=3; $i++ ){
        if( trim($themeConfig['google_url'.$i]) && $themeConfig['type_fonts'.$i] == 'google' ){
          $link[] = '<link rel="stylesheet" type="text/css" href="'.trim($themeConfig['google_url'.$i]) .'"/>';
          $themeConfig['normal_fonts'.$i] = $themeConfig['google_family'.$i];
        }
      }
      echo implode( "\r\n",$link );
    ?>
    <style>
      body {font-family: <?php echo $themeConfig['normal_fonts1']; ?>; font-size: <?php echo $themeConfig['fontsize1']; ?>}
      #header-main {font-family: <?php echo $themeConfig['normal_fonts2']; ?>; font-size: <?php echo $themeConfig['fontsize2']; ?>}
      #module-container {font-family: <?php echo $themeConfig['normal_fonts3']; ?>; font-size: <?php echo $themeConfig['fontsize3']; ?>}

      <?php 
        if( trim($themeConfig['body_selector4']) && trim($themeConfig['normal_fonts4']) ){
          $css[]= trim($themeConfig['body_selector4'])." {font-family:".str_replace("'",'"',htmlspecialchars_decode(trim($themeConfig['normal_fonts4'])))."}\r\n" ;
        }
         echo implode("\r\n",$css);
      ?>
    </style>
    <?php } ?>
    <!-- FONT -->
    <?php if( isset($google_analytics) ){ ?>
    <?php echo $google_analytics; ?>
    <?php } ?>

    <?php if( isset($themeConfig['theme_width']) && $themeConfig['theme_width'] &&  $themeConfig['theme_width'] != 'auto' ) { ?>
    <style>.container {max-width:<?php echo $themeConfig['theme_width'];?>; width:auto;} </style>
    <?php } ?>

    <script>
      var _hmt = _hmt || [];
      (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?91e232366b70a2d5b4daafb3b3e50bf8";
        var s = document.getElementsByTagName("script")[0]; 
        s.parentNode.insertBefore(hm, s);
      })();
    </script>

  </head>