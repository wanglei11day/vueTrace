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
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="page-404 <?php echo $class; ?>"><?php echo $content_top; ?>
        <div class="design-cat">
            <div class="design-cat-item" style="border-bottom: 3px solid #29d9c2;"><a href="<?php echo $kdesign;?>"><?php echo $text_k_design;?></a> </div>
            <div class="design-cat-item" ><a href="<?php echo $tdesign;?>"><?php echo $text_t_design;?></a> </div>
        </div>
      <p><?php echo $text_error; ?></p>
      <div class="buttons">
        <?php if(isset($button_continue_shoping)) { ?>
        <div class="pull-left"><a href="<?php echo $continue_shoping; ?>" class="btn btn-primary"><?php echo $button_continue_shoping; ?></a></div>
        <?php } ?>
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>