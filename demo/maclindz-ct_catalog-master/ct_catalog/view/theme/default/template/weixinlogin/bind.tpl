<?php echo $header; ?>
<style type="text/css">
html {
	overflow-y: scroll;
	margin: 0;
	padding: 0;
}
body {
	background-color: #f1f1f1;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
	margin: 0px;
	padding: 0px;
	color: #333;
}
* {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
#container1{
	width: 100%;
}
.well1 {
	margin: 0 auto;
		width: 80%;
	}
h2 {
	text-align: center;
	color: #333;
    display: block;
    font-size: 1.3em;
    font-weight: bold;
}
p {
	text-align: center;
	 font-size: 0.8em;
}
.form-group{
	/*background-color: red;*/
	text-align: center;
	margin-top: 2em;
}
.control-label{
    margin-left: -0.8em;
	font-size: 1.5em;
}
.form-control{
	border: 1px solid #ccc;
	height: 34px;
	width: 100%;
	padding: 0 5px;
}
 
.btn-primary{
	margin: 3em auto 0 auto;
	display: block;
    width: 100%;
    padding: 0.8em;
    border: 1px solid #29d9c2;
    outline: 0;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
    background: none;
    filter: Alpha(Opacity=0);
    opacity: 1;
    color: #333;
    font-size: 0.9em;
}
</style>
<div class="container1">
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
    <div id="content"><?php echo $content_top; ?>
          <div class="well1">
            <h2><?php echo $text_returning_customer; ?></h2>
            <p><?php echo $text_i_am_returning_customer; ?></p>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              </div>
              <div class="form-group">
                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                <br/>
              <input type="submit" value="<?php echo $text_login; ?>" class="btn btn-primary" />
            </form>
          </div>
      <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>