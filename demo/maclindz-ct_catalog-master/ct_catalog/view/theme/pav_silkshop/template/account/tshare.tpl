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
      <div class="content-inner">
    <?php echo $content_top; ?>
      <h3 class="page-title"><?php echo $heading_title; ?></h3>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
       
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-to-email"><?php echo $entry_share_to_email; ?></label>
          <div class="col-sm-10">
            <input type="text" name="share_to_email" value="<?php echo $share_to_email; ?>" id="input-to-email" class="form-control" />
            <?php if ($error_share_to_email) { ?>
            <div class="text-danger"><?php echo $error_share_to_email; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-from-name"><?php echo $entry_from_name; ?></label>
          <div class="col-sm-10">
            <input type="text" name="from_name" value="<?php echo $from_name; ?>" id="input-from-name" class="form-control" />
            <?php if ($error_from_name) { ?>
            <div class="text-danger"><?php echo $error_from_name; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-from-email"><?php echo $entry_from_email; ?></label>
          <div class="col-sm-10">
            <input type="text" name="from_email" value="<?php echo $from_email; ?>" id="input-from-email" class="form-control" />
            <?php if ($error_from_email) { ?>
            <div class="text-danger"><?php echo $error_from_email; ?></div>
            <?php } ?>
          </div>
        </div>
       
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-message"><span data-toggle="tooltip" title="<?php echo $help_message; ?>"><?php echo $entry_message; ?></span></label>
          <div class="col-sm-10">
            <textarea name="message" cols="40" rows="5" id="input-message" class="form-control"><?php echo $message; ?></textarea>
          </div>
        </div>
       
        <div class="buttons clearfix">
          <div class="pull-right">
            
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>"  />
      </form>
     <?php echo $content_bottom; ?>
   </div>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>