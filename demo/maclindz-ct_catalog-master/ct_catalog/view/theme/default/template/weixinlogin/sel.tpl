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
		width: 60%;
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
	
  
	display: block;
    width: 100%;
    padding: 22px;
   
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
.btn-old{
  margin: 3em auto 0 auto;
  border: 1px solid #29d9c2;
}
.btn-new{
  margin: 1em auto 0 auto;
  border: 1px solid #000;
}
</style>
<div class="container1">
    <div id="content" class="col-xs-12"><?php echo $content_top; ?>
          <div class="well1">
         		<br/><br/><br/><br/>
          	<a class="btn btn-primary btn-old col-xs-12" href="<?php echo $action_old; ?>"><?php echo $button_old_customer; ?></a>
          	<br/><br/><br/><br/>
          	<a class="btn btn-primary btn-new col-xs-12" href="<?php echo $action_new; ?>"><?php echo $button_new_customer; ?></a>
         		<br/><br/><br/><br/>
         	</div>
      <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>