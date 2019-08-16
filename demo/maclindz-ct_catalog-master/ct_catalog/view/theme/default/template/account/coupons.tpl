<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a style="color: #333;" href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
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
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      
      <style type="text/css">
      table.list-coupons thead td{
        padding:5px;
      }
      table.list-coupons tr td{
        padding:5px;
      }
      </style>
      <?php if(isset($coupons) && $coupons) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                  <tr>
                    <?php if($show_coupon_code): ?>
                    <td class="text-left"><?php echo $text_coupon_code; ?></td>
                    <?php endif; ?>
                    <td class="text-left"><?php echo $text_coupon_name; ?></td>
                    <td class="text-left"><?php echo $text_date_start; ?></td>
                    <td class="text-left"><?php echo $text_date_end; ?></td>
                  </tr>
                </thead>
            <tbody>
              <?php foreach ($coupons as $coupon) { ?>
                  <tr>
                    <?php if($show_coupon_code): ?>
                    <td><strong><?php echo $coupon['code']; ?></strong></td>
                    <?php endif; ?>
                    <td><?php echo $coupon['name']; ?></td>
                    <td><?php echo $coupon['date_start']; ?></td>
                    <td><?php echo $coupon['date_end']; ?></td>
                  </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <?php } ?>
        <div class="buttons clearfix">
          <div class="pull-right"><a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $button_back; ?></a></div>
        </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>