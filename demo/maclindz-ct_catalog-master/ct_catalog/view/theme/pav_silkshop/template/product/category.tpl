
<?php echo $header; ?>
<div class="breadcrumb" style="">
    <div class="container">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> / </li>
            <?php } ?>
        </ul>
        <h1 class="breadcrumb-heading"><?php echo $heading_title; ?></h1>
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
      <!--<h1><?php echo $heading_title; ?></h1>-->
      <?php if ($thumb || $description) { ?>
      <div class="row">
        
        <?php if ($description) { ?>
        <div class="col-sm-12 category-description"><?php echo $description; ?></div>
        <?php } ?>
      </div>
      <?php } ?>
      <?php if ($categories) { ?>
      <div class="row">
        <?php foreach ($categories as $category) { ?>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
          <div class="category-thumb">
              <a href="<?php echo $category['href']; ?>">
                <div class="cat-image">
                  <img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>">
                </div>
                <div class="cat-name">
                  <?php echo $category['name']; ?>
                </div>
              
              </a>
          </div>
        </div>
        <?php } ?>
      </div>
     
      <?php } ?>
      <?php if ($products) { ?>
      <?php   require( ThemeControlHelper::getLayoutPath( 'product/product_filter.tpl' ) );   ?>
      <br />
      
        <?php require( ThemeControlHelper::getLayoutPath( 'common/product_collection.tpl' ) );  ?>

        <div class="row">
            <div class=" pagination col-sm-12">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
        </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
