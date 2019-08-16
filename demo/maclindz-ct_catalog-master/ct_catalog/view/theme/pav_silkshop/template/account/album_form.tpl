<?php echo $header; ?>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><h1 class="heading-title"><?php echo $heading_title; ?></h1><?php echo $content_top; ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2 class="secondary-title"><?php echo $text_edit_address; ?>sss</h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $text_album_name; ?></td>
          <td><input id="name" type="text" name="albumName"  value="<?php echo $album; ?>" />
            <?php if ($error_album) { ?>
            <span class="error"><?php echo $error_album; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_album_description; ?></td>
          <td><textarea id="description" type="text" name="albumDescription"  size="30" class="required" title="<?php echo $this->language->get('error_album'); ?>" /><?php echo $album_description; ?></textarea>
            <?php if ($error_album) { ?>
            <span class="error"><?php echo $error_album; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right">
          <button  onclick="createAlbum()" class="button"><?php echo $button_continue; ?></button>
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<script>
	function createAlbum(){
		var name = $('#name').val();
		var description=$('#description').val();
		if(name==null||name==""){
			alert("<?php echo $error_album_emptyname; ?>");
		}else{
			sendRequest(name,description);
		}
		return false;
	}
	
	function sendRequest(name,description){
		var queryuri = "<?php echo $queryuri?>"+ "?callback=?";	
		var handleURL = queryuri;
		var arg = {
			"name":name,
			"description":description,
			"token":"<?php echo $querytoken; ?>"
		};
		$.getJSON(handleURL,arg,function(data) {
			if(data.isSuccess){
				alert("<?php echo $text_album_add_success; ?>");
				 window.location.href="<?php echo $back; ?>"; 
			
			}else{
				alert("<?php echo $text_album_add_failed; ?>")
			}
		},"json");
	}
  </script>
<?php echo $footer; ?>