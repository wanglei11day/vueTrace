<!DOCTYPE html>
<html lang="en">
<link href="http://www.icootoo.com/image/catalog/logo/cootoo-logo-web-header.jpg" rel="icon">

<?php 
	$product_id = $_GET['product_id'];

	$debug_video = isset($_GET['debug_video'])?$_GET['debug_video']:'';

	/** 
	* @method 打印多维数组 
	* @param type $array 
	*/
	function arr_foreach($arr){
		if(!is_array($arr)){
			return false;
		}
		
		foreach($arr as $key => $val){
			if(is_array($val)){
				arr_foreach($val);
			}else{
				echo $val.'<br/>';
			}
		}
	}
	
	/** 
	* @method 多维数组转字符串 
	* @param type $array 
	* @return type $srting 
	*/ 
	function arrayToString($arr){  
		if (is_array($arr)){  
			return implode(',', array_map('arrayToString', $arr));  
		}  
		return $arr;  
	}  
	
    $page_spec_width = 200;
    $page_spec_height = 295;
	$page_spec_border = 0;
    $page_spec_dpi = 300;
	$page_spec_cropmarks = "true";
	$page_spec_bleed_1 = 0.0;
	$page_spec_bleed_2 = 0.0;
	$page_spec_bleed_3 = 0.0;
	$page_spec_bleed_4 = 0.0;
	$page_spec_bleed = "0.0, 0.0, 0.0, 0.0";
	$page_spec_margin_1 = 0.0;
	$page_spec_margin_2 = 0.0;
	$page_spec_margin_3 = 0.0;
	$page_spec_margin_4 = 0.0;
	$page_spec_margin = "0.0, 0.0, 0.0, 0.0";
    $page_spec_format = "pdf";
    $page_spec_min_pages = 20;
    $page_spec_num_pages = 20;
    $page_spec_max_pages = 200;
    $page_spec_inc_pages = 2;
    $page_spec_filename = "superbook-pages.pdf";

    $cover_spec_width = 420;
    $cover_spec_height = 300;
    $cover_spec_dpi = 300;
	$cover_spec_cropmarks = "true";
	$cover_spec_bleed_1 = 0.0;
	$cover_spec_bleed_2 = 0.0;
	$cover_spec_bleed_3 = 0.0;
	$cover_spec_bleed_4 = 0.0;
	$cover_spec_bleed = "0.0, 0.0, 0.0, 0.0";
	$cover_spec_margin_1 = 0.0;
	$cover_spec_margin_2 = 0.0;
	$cover_spec_margin_3 = 0.0;
	$cover_spec_margin_4 = 0.0;
	$cover_spec_margin = "0.0, 0.0, 0.0, 0.0";
	$cover_spec_spine_width = "36,10";
    $cover_spec_format = "pdf";
    $cover_spec_filename = "superbook-cover.pdf";
    
	//000 商品种类
    $themes_type = "portrait-book";
	$book_type = "hard_cover_book";
	
	//001 主题分类
	$selected_themes = "";
	

	//006 
	$conf_forced_frame_category = "";
	$frame_category_chooser = "true";
	$textframe_category_chooser = "true";
	$export_double_pages = "true";
	//008 
	$conf_forced_textframe_category = "";
	

	//128 增加单页价格
	$p_product_onepage_price = 0;
	
	//129 增加中共价格
	$p_product_allpage_price = 0;
	
	//999 模板设计（0为否，1为是）默认为0
	$templet_design = 0;
	
	
	
	
	// 801
	$static_serv = "none";
	// 802
	$client = "cootoo";
	// 803
	$uploadedThumbSize = 120;
	// 804
	$lowres_warning_dpi_ratio = 0.25;
	// 805
	$lowres_warning_pixelsize = 3000000;
	// 806
	$page_flip_time = 1000;
	// 807
	$searchEnabled = "true";
	// 808
	$autosave = $hide_edit?"false":"true";
	// 809
	$autosave_delay = 10;
	// 810
	$save_warning = "false";
	// 811
	$regularFontList = "";
	// 812
	$googleFontList = "";
	
	// 002
	$clipartsSelected = "";
	// 202
	$backgroundsSelected = "";
	
	// 201
	$hideClipartsTagSearch = "false";
	// 203
	$hideBackgroundsTagSearch = "false";
	//103
	$conf_filters = "brightnesscontrast,hdr,sepia,blackwhite,saturation,blur,noise,popart,snapshot,comics,genericframe,printsnap";
	// 101
	$productType = "Book";
	
	$inner_cover_editable = "false";
	$page_spec_shading = "undefined";

	$hide_Themes_TagSearch = "no";//011
    $hide_Layouts_TagSearch = "no";//012
    $load_as_theme = "no";   //030
    $theme_id = "";   //031
    $editor_backgroud = "//www.icootoo.com/kwresource/css/skulls.png";   //031
    
    
    $autofill = "no";  //032
	// 100
	$card_type = "cardsimple";
	
	$mpix_photo_export = 8;

	$max_upload_retries = 30;
    $showlayout = "";
	$product_info = $data['product_info'];
	$product_info_json = json_encode($product_info);
	//echo '  && product_info_json = '.$product_info_json;

	
	foreach ($editorarg as $key =>$pa_text){
		switch($key){
				case '000'://主题样式
					$themes_type = $pa_text;
					break;
				case '001'://主题分类
					$selected_themes = $pa_text;
					break;
			    case '011'://主题分类
					$hide_Themes_TagSearch = $pa_text;
					break;
				case '012'://主题分类
					$hide_Layouts_TagSearch = $pa_text;
					break;
				case '030'://主题分类
					$load_as_theme = $pa_text;
					break;
				case '031'://主题分类
					$theme_id = $pa_text;
					break;
				case '032'://主题分类
					$autofill = $pa_text;
					break;
				case '050'://主题分类
					$editor_backgroud = $pa_text;
					break;
					
				case '201'://宽度 封面（mm）
					$cover_spec_width = $pa_text;
					break;
				case '202'://宽度 内页（mm）
					$page_spec_width = $pa_text;
					break;
				case '203'://高度 封面（mm）
					$cover_spec_height = $pa_text;
					break;
				case '204'://高度 内页（mm）
					$page_spec_height = $pa_text;
					break;
				case '151'://DPI 封面
					$cover_spec_dpi = $pa_text;
					break;
				case '152'://DPI 内页
					$page_spec_dpi = $pa_text;
					break;

				case '209'://打印裁切线 封面
					$cover_spec_cropmarks = $pa_text;
					break;
				case '210'://打印裁切线 内页
					$page_spec_cropmarks = $pa_text;					
					break;
				case '211'://出血 封面（mm）
					$cover_spec_bleed = $pa_text;
					break;
				case '212'://出血 内页（mm）
					$page_spec_bleed = $pa_text;
					break;
				case '213'://白边 封面（mm）
					$cover_spec_margin = $pa_text;
					break;
				case '214'://白边 内页（mm）
					$page_spec_margin = $pa_text;
					break;
				case '205'://书脊宽度（mm）
					$cover_spec_spine_width = $pa_text;
					break;
					
				case '120'://相册装订
					$book_type = $pa_text;
					break;
				case '122'://最低页数
					$page_spec_min_pages = $pa_text;
					break;
				case '123'://最多页数
					$page_spec_max_pages = $pa_text;
					break;
				case '124'://每次增加页
					if($pa_text ==0) $pa_text = 2;
					$page_spec_inc_pages = $pa_text;
					break;
				case '121'://默认页数
					$page_spec_num_pages = $pa_text;
					break;
					
				case '701'://输出格式 封面
					$cover_spec_format = $pa_text;
					break;
				case '702'://输出格式 内页
					$page_spec_format = $pa_text;
					break;	
				case '703'://文件名 封面
					$cover_spec_filename = $pa_text;
					break;
				case '704'://文件名 内页
					$page_spec_filename = $pa_text;
					break;
					
				case '110'://产品价格
					$page_spec_border = $pa_text;
					break;
				
				case '128'://增加单页价格
					$p_product_onepage_price = $pa_text;
					break;
				case '129'://增加单页价格
					$p_product_allpage_price = $pa_text;
					break;
				case '999'://模板设计（0为否，1为是）
					$templet_design = $pa_text;
					break;
					
					
				case '801':
					$static_serv = $pa_text;
					break;
				case '802':
					
					$client = $pa_text;
					break;
				case '803':
					$uploadedThumbSize = $pa_text;
					break;
				case '804':
					$lowres_warning_dpi_ratio = $pa_text;
					break;
				case '805':
					$lowres_warning_pixelsize = $pa_text;
					break;
				case '806':
					$page_flip_time = $pa_text;
					break;
				case '807':
					$searchEnabled = $pa_text;
					break;
				case '808':
					$autosave = $hide_edit?"false":$pa_text;
					break;
				case '809':
					$autosave_delay = $pa_text;
					break;
				case '810':
					$save_warning = $pa_text;
					break;
				case '811':
					$regularFontList = $pa_text;
					break;
				case '812':
					$googleFontList = $pa_text;
					break;
				
				case '002':
					$clipartsSelected = $pa_text;
					break;
				case '004':
					$backgroundsSelected = $pa_text;
					break;
				case '003':
					$hideClipartsTagSearch = $pa_text;
					break;
				case '005':
					$hideBackgroundsTagSearch = $pa_text;
					break;
				case '006':
					$conf_forced_frame_category = $pa_text;
					break;
				case '007':
					$frame_category_chooser = $pa_text;
					break;
				case '008':
					$conf_forced_textframe_category = $pa_text;
					break;
				case '009':
					$textframe_category_chooser = $pa_text;
					break;
				case '100':
					$productType = $pa_text;
					break;
				case '103'://增加单页价格
					$conf_filters = $pa_text;
					break;	
				case '105'://增加单页价格
					$inner_cover_editable = $pa_text;
					break;
				case '106'://增加单页价格
					$page_spec_shading = $pa_text;
					break;
				case '101':
					$card_type = $pa_text;
					break;
				case '813':
					$mpix_photo_export  = $pa_text;
					break;
				case '680':
					$export_double_pages  = $pa_text;
					break;
				case '911':
					$max_upload_retries  = $pa_text;
					break;
				case '912':
				$showlayout  = $pa_layout;
				break;
			}
	}
	
			
			
	
	
	$product_option_values = array();
	$option_data = isset($data['option_data'])?$data['option_data']:array();
	$option_data_json = json_encode($option_data);
	//echo '  && option_data_json = '.$option_data_json;
	$obj = json_decode($option_data_json);
	$count_obj = count($obj);
	for($i=0; $i<$count_obj; $i++){
		$product_option_values[$i] = $obj[$i]->product_option_value_id;
		$name = $obj[$i]->name;
		$value = $obj[$i]->value;
		//echo  '    name = '.$name.'    value = '.$value;			
		switch($name){
			case '主题模板':
				$themes_type = $value;
				break;
		}					
	}


?>
<!-- -->
<head>
    <meta charset="utf-8">
    <title>Cootoo</title>
    <meta name="description" content="">
    <meta name="author" content="Koffeeware">
	
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <link href="//s3.cn-north-1.amazonaws.com.cn/upload.creator5.permanent/files/css/jquery-ui.css" rel="stylesheet">
    <link href="//s3.cn-north-1.amazonaws.com.cn/upload.creator5.permanent/files/css/styles.css" rel="stylesheet">
    <link href="catalog/view/theme/<?php echo $config_template; ?>/stylesheet/editor_<?php echo $language_id;?>.css" rel="stylesheet"/>
    <link href="catalog/view/javascript/videojs/video-js.min.css" rel="stylesheet"/>
	<link href="catalog/view/javascript/videojs/video-js.min.css" rel="stylesheet"/>
    <style> 
        body { overflow:hidden; } #koffeecreator { position:absolute;  top:35px;bottom:0; left:0; right:0; }
        .options { height:30px; text-align:right }
        
        <?php if($hide_edit) { ?>
        	
        	#save_project{
        		display: none;
        	}
        	#order_btn{
        		display: none;
        	}
        <?php }  ?>
        
        .koffeeware-creator #main_application { background:url(<?php echo $editor_backgroud;?>); }

		<?php foreach ($fontfaces as $fontface){?>
			@font-face{
				<?php echo "font-family:"."'".$fontface['name']."';\r";
				echo "src: url('".$fontface['eot_file']."');\r";
				echo "src: url('".$fontface['eot_file']."?#iefix') format('embedded-opentype'),\r";
				echo "url('".$fontface['woff_file']."') format('woff'),\r";
				echo "url('".$fontface['ttf_file']."') format('truetype'),\r";
				echo "url('".$fontface['svg_file']."#webfont') format('svg');\r";
				?>
			}

		<?php  } ?>


	
		.right-buttons{
			float:right;
			margin:0px 10px
		}


	@media (min-width: 768px){
		.col-sm-6 {
		    width: 50%;
		}
		 .loginbox .modal-dialog {
		    width: 600px;
		    margin: 30px auto;
		  }
		 
	}

	

	@media (min-width: 992px){
		.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
		    float: left;
			/* padding: 10px; */
			position: relative;
			min-height: 1px;
			padding-right: 15px;
			padding-left: 15px;
		}
		.col-md-4 {
		    width: 33.3333%;
		}
		.col-md-6 {
		    width: 50%;
		}
		 .loginbox .modal-lg {
		    width: 900px;
		  }
	}


	@media (min-width: 1200px) {
		.col-lg-3 {
		    width: 25%;
		}
	}


	







		#mobile-tip{
		  	display: none;
		  }

		.loginbox .modal-open {
		  overflow: hidden;
		}

		.modal {
		  display: none;
		  overflow: hidden;
		  position: fixed;
		  -webkit-overflow-scrolling: touch;
		  outline: 0;
		}
		

		.loginbox .modal-open .modal {
		  overflow-x: hidden;
		  overflow-y: auto;
		}

		.loginbox .modal-dialog {
		  position: relative;
		  width: auto;
		  margin: 10px;
		}

		

		.loginbox .modal-header {
		  padding: 15px;
		  border-bottom: 1px solid #e5e5e5;
		  min-height: 16.625px;
		}

		.loginbox .btn-primary{
		}

		.loginbox .modal-header .close {
		  margin-top: -2px;
		}

		.loginbox .modal-title {
		  margin: 0;
		  line-height: 1.625;
		}

		.loginbox .modal-body {
		  position: relative;
		  padding: 15px;
		}

		.loginbox .modal-footer {
		  padding: 15px;
		  text-align: right;
		  border-top: 1px solid #e5e5e5;
		}
		

		.loginbox .modal-scrollbar-measure {
		  position: absolute;
		  top: -9999px;
		  width: 50px;
		  height: 50px;
		  overflow: scroll;
		}

		.loginbox .modal-dialog {
		    width: auto;
		    margin: 30px auto;
		  }

		

		
	

		.guide{
			display: none;
		    position: fixed;
		        left: 0;
		        top: 0;
		    /* height: 600px; */
		    z-index: 1000;
		    background: rgba(0,0,0, 0.2);
    		width: 100%;
   			 height: 100%;

		}

		.guide .guide-content{
			background: #fff;
    		margin: auto;
    		padding: 10px;
		    max-height: 70%;
		    width: 50%;
		    overflow: auto;
		    margin: auto;
		    margin-top: 8%;
		    box-shadow: 2px 5px 5px #6b6b6b;
		}

		    
		.top-design-name{
		  	float: left;
		  	padding: 7px;
		  }
		
		.guide .guide-list{
			width: 100%;
		    /* margin: auto; */
		    /* position: absolute; */
		    z-index: 999;
		    background: #fff;
		}

		.guide .video-div .title-list{
			
		}

		.guide .video-div .title-list .title-item {
			padding:5px 0px;
		}

		.guide .video-div .title-list .title-item a{
			margin-left: 5px;
		}

		.guide .guide-list .guide-title{
			overflow: auto;
			width: 100%;
			margin-bottom: 15px;
			border-bottom: 1px solid #ddd;
		}

		.guide .guide-list .guide-title .title{
			float: left;
		}
		.guide .guide-list .guide-title .title h2{
		    font-size: 25px;
		}

		.guide .guide-list .guide-title .close-guide-div{
			float: right;
		}

		.guide .guide-list .guide-title .close-guide-btn{
			margin: 15px 0px;
		}
		.guide .video-div{
			    /* width: 100%; */
		    position: relative;
		    margin: auto;
		    z-index: 1000;
		}

		.video-player .video-js{
			margin:auto;
			width: 100%;
			height: 100%;
		}

		.video-player{
			display: none;
			position: absolute;
		    width: 100%;
		    z-index: 10000;
		    height: 100%;
		    top: 0;
		    left: 0;
		}
		.close-video-btn {
			overflow: auto;
		    color: #fff;
		    width: 100%;
		    background: #fff;
		    z-index: 1000000;
		}

		.close-video-btn a{
			float: right;
		}

		.guide-btn{
			margin-right: 5px;
		}
		#modal-save-design-name{
			top:30%;
		}

	



		@media (max-width: 768px) {
		  	.col-xs-12{
		  		width: 100%;
		  	}
		  	.right-buttons{
				float:left;
				margin:0px 0px
			}

			

			.koffeeware-creator #header .order-zone #order_btn{
				font-size: 14px;
			    
			    width: 90px;
			}
		  }

		@media (max-width: 568px) {
		  .top-design-name{
		  	float: none;
		  	text-align: center;
		  	padding-bottom: 0px;
		  }
		  .save-design-name-div{
		  	margin:0 -15px;
		  }
		   #mobile-tip{
		  	display: block;
		  	width: 90%;		    
		  	 position: absolute; 
		    margin: auto;
		    top: 5%;
		  }

		  .mobile-tip-div:after{
		  	content:"";
			position:absolute;
			left:0;
			top:0;
			width:100%;
			height:100%;
			z-index: 999;
			background:rgba(0,0,0, 0.7);
			transition: all 0.3s ease 0s;
			-webkit-transition: all 0.3s ease 0s;
			-moz-transition: all 0.3s ease 0s;

		  }
		  #modal-quicksignup{
		  	position: fixed;
		  	top:0;
		  	bottom: 0;
		  	left: 0;
		  	right: 0;
		  	overflow-x: hidden;
		  	overflow-y: auto;
		  }
		  .guide .guide-content{
		  	margin-top: 25%;
		  	width: 80%;
		  }

		  #sidebar{
		  	margin-top: 40px;
		  }
		  #koffeecreator {
		    
		   	top: 57px;
		    
		  }
		}

		@media (max-width: 360px) {
		  
		  .save-design-name-div .btn{
		  	padding: 2px 5px;
		  }
		  .koffeeware-creator #header .order-zone #order_btn {
			    
			    width: 81px;
			}
		}


    </style>

    <script src="//s3.cn-north-1.amazonaws.com.cn/upload.creator5.permanent/files/js/jquery.min.js"></script>
    <script src="//s3.cn-north-1.amazonaws.com.cn/upload.creator5.permanent/files/js/jquery-ui.min.js"></script>
	<script src="catalog/view/javascript/editor/webfont.js"></script>
	<script src="catalog/view/javascript/editor/application.js"></script>
	<script src="<?php echo $data['kwclocaleurl'];?>"></script>
	<script src="catalog/view/javascript/videojs//ie8/videojs-ie8.min.js"></script>
	<script src="catalog/view/javascript/videojs/video.js"></script>
	<script src="catalog/view/javascript/jquery/timer.jquery.js"></script>
	
</head>

<body>
	<div id="modal-save-design-name" class="modal" style="">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title main-heading"><?php echo $text_save_design_name ?></h4>
				</div>
				<div class="modal-body design-info">
				
					<div class="clearfix box-design-infomation">
						
						
						<div class="form-group required">
							<label class="control-label" for="input-password"><?php echo $text_design_name; ?></label>
							<input type="text" name="design_name"  style="width:250px;height:17px;" id="input_design_name" value="<?php echo $design_name?$design_name:$default_name; ?>"  class="form-control fix-input" />
						</div>
						
					
						
					</div>
				</div>
				<div class="modal-footer">
					<div class="buttons">
						<div class="pull-right">
							<button type="button" class="btn btn-primary save-design-name" style="background: #8ab914;" ><?php echo $button_confirm; ?></button><button  style="background: #8ab914;" type="button" class="btn btn-primary cancel-save-design-name" ><?php echo $button_cancel; ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="video-player">	
	    <div class="close-video-btn">
	    	<a class="btn btn-small" href="#"><?php echo $txt_close_guide; ?></a>
	    </div>
		<video id="guide-video" class="video-js vjs-default-skin" controls preload="none" width="100%" height="300px"
		      poster=""
		      data-setup="{}">
		    <source src="" type='video/mp4' />
		    <source src="" type='video/webm' />
		    <source src="" type='video/ogg' />
		    <track kind="captions" src="demo.captions.vtt" srclang="en" label="English"></track><!-- Tracks need an ending tag thanks to IE9 -->
		    <track kind="subtitles" src="demo.captions.vtt" srclang="en" label="English"></track><!-- Tracks need an ending tag thanks to IE9 -->
		</video>
	</div>

	<div class="guide">
		<div class="guide-content">

			<div class="guide-list">
				<div class="guide-title">
					<div class="title">
						<h2><?php echo $txt_guide_title; ?></h2>
					</div>
					<div class="close-guide-div">
						<a class="btn btn-small close-guide-btn" href="#"><?php echo $txt_close_guide; ?></a>
					</div>
					
				</div>
			</div>
			<div class="video-div">
				<div class="title-list">
					<div class="row" style="margin-left: 0px;">

					<?php foreach ($guides as $video) {?>


					<div class="col-xs-12 col-md-6 title-item"><a  class="video-play-btn" href="<?php echo $design_url.$video['video'].'.mp4';?>"><?php echo $video['title'];?></a></div>
					
					<?php } ?>

					</div>
				</div>
			</div>
			
				

		</div>
	</div>

	
	<div class="visible-xs mobile-tip-div">
        <div id="mobile-tip" class="modal fade in" aria-hidden="false" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
							<button type="button" class="close closs-tip" aria-hidden="true">×</button>
							<h3><img src="image/data/info.png" style="width: 24px;margin-right: 5px;" /><?php echo $text_tip_title;?></h3> 
						</div>
					<div class="modal-body"> 
					<?php echo $text_tip_info;?>
		           
		        	</div> 
			        <div class="modal-footer"> 
			       		<a href="#" class="btn closs-tip"><?php echo $text_tip_continue;?></a> 
			       		<a href="<?php echo $cancel;?>" class="btn btn-success"><?php echo $text_tip_cancel;?></a> 
			            
			        </div> 
				</div>
			</div>
		</div>
	</div>
	<div class="loginbox">
		<?php echo $editorquicksignup; ?>
	</div>
	<div class="top-design-name">
	<?php  echo $design_name?$design_name:$default_name; ?>
	</div>
	<div class="save-design-name-div" style="padding:7px 0px; float: right;overflow: auto;">
	
	<div  class="pull-right" >
			<a class="btn btn-small" id="cancel_back" href="<?php echo $cancel;?>"><?php echo $txt_cancel; ?></a>
		</div>
		<div  class="pull-right" >
			<a class="btn btn-small guide-btn" style="margin-left: 8px;background: #fa4600;color:#fefefe"  href="#" ><?php echo $txt_guide; ?></a>
		</div>

		<?php if($page_spec_inc_pages>0){?>
			<div style="float:right;" class="pull-right"><button class="btn btn-small " onclick="reducePage()"><?php echo $data['txt_reduce']; ?>
			<?php echo ' '.$page_spec_inc_pages.' '; ?>
			<?php 
			if($page_spec_inc_pages>1){
				if(strcasecmp($data['txt_page'],'page')==0){
					echo $data['txt_page'].'s'; 
				}else{
					echo $data['txt_page']; 
				}
				
			}else{
				echo $data['txt_page']; 
			}?>
			
			</button></div>
			<div style="float:right;margin:0px 8px"  class="pull-right"><button class="btn btn-small" onclick="addPage()"><?php echo $data['txt_add']; ?>
			<?php echo ' '.$page_spec_inc_pages.' '; ?>
			<?php 
			if($page_spec_inc_pages>1){
				if(strcasecmp($data['txt_page'],'page')==0){
					echo $data['txt_page'].'s'; 
				}else{
					echo $data['txt_page']; 
				}
			}else{
				echo $data['txt_page']; 
			}?>
			
			</button></div>



		<?php } ?>
			
			
		
		
	</div>
	

	<!-- -->
<div id="koffeecreator" class="koffeeware-creator"></div>
<script>
    var lineheight = <?php echo $_GET['lineheight']?$_GET['lineheight']:1.0;?>;
	$("#cancel_back").on('click',function(e){
		e.preventDefault();

        $.ajax({
            url: 'index.php?route=account/diydesign/unlock&customer_creation_id=<?php echo $customer_creation_id; ?>',
            type: 'get',
            data:'',
            dataType: 'json',
            success: function(json) {
                history.go(-1);
            }
        });
	});
	var myPlayer = videojs("guide-video",{ controls: true });
	
	$(".video-play-btn").on('click',function(e){
		e.preventDefault();
		$(".video-player").show();
		myPlayer.src(this.href);
		//myPlayer.play();
		//myPlayer.requestFullscreen();

	});
	$(".closs-tip").on('click',function(e){
		e.preventDefault();
		$(".mobile-tip-div").hide();
	});

	$(".close-guide-btn").on('click',function(e){
		e.preventDefault();
		myPlayer.pause();
		$(".guide").hide();
	});

	


	$(".guide-btn").on('click',function(e){
		e.preventDefault();
		$(".guide").show();
	});

	$(".close-video-btn a").on('click',function(e){
		e.preventDefault();
		myPlayer.pause();
		$(".video-player").hide();
	});


	function checkLogin(){
   		$.ajax({
			url: 'index.php?route=account/diydesign/checklogin',
			type: 'get',
			data:'',
			dataType: 'json',
			success: function(json) {
				if(json['success']){
					isLogin=1;
                	 $('#modal-quicksignup').modal('hide');
				}else{
					isLogin=0;
                    $('#modal-quicksignup').modal('show');
				}
			}
		});	
	}


	var spec_max_pages = <?php echo $page_spec_max_pages ?>;
	var spec_min_pages = <?php echo $page_spec_min_pages ?>;
	var spec_num_pages = <?php echo $page_spec_num_pages ?>;
	<?php if (!empty($action)) {?>
		var current_page_num = <?php echo $current_pages ?>;
	<?php }else {?>
		var current_page_num = spec_num_pages;
	<?php } ?>
	
	var inc_page_num = <?php echo $page_spec_inc_pages ?>;
	
	function defultPage(){
				//creation.setNumberOfPages(<?php echo $page_spec_num_pages ?>);
		}
		function addPage(){
			if((current_page_num + inc_page_num)<= spec_max_pages){
				current_page_num = current_page_num + inc_page_num;
				creation.setNumberOfPages(current_page_num);
			}else{
                alert("<?php echo $text_max_pages;?>"+spec_max_pages+"<?php echo $text_warning_page;?>");
			}
		}
		function reducePage(){
			if((current_page_num - inc_page_num)>= spec_min_pages){
				current_page_num = current_page_num - inc_page_num;
				creation.setNumberOfPages(current_page_num);
			}else{
                alert("<?php echo $text_min_pages;?>"+spec_min_pages+"<?php echo $text_warning_page;?>");
			}
		}
	
	function modifyName(){
		if($("#input_design_name").is(':hidden')){  
			$("#input_design_name").show();
			$("#div_design_name").hide();
			
			
			return false
		}else{
			$("#span_design_name").text($("#input_design_name").val());
			$("#input_design_name").hide();
			$("#div_design_name").show();
		}
		
	}

	//setInterval(checkLogin,5000);

</script>
		
    <script>
		var action = "<?php echo $action; ?>";
		isLogin = <?php echo $isLogin;?>;
         //KWP_CONF.version = "20150729";
		KWP_CONF.version = "files";
        //KWP_CONF.client = "cootoo";
        //KWP_CONF.uploadedThumbSize = 400;
        KWP_CONF.cootoo_serv = "<?php echo $http_server;?>";
		KWP_CONF.static_serv = "//s3.cn-north-1.amazonaws.com.cn/upload.creator5.permanent/";
        //KWP_CONF.autosave = true;
        KWP_CONF.HSLcapable = true;
        KWP_CONF.filters = "<?php echo $conf_filters ?>";
        KWP_CONF.forced_frame_category = "<?php echo $conf_forced_frame_category; ?>";
		KWP_CONF.forced_textframe_category	= "<?php echo $conf_forced_textframe_category; ?>";
		//"brightnesscontrast,hdr,sepia,blackwhite,saturation,blur,noise,popart,snapshot,comics,genericframe,printsnap";
        //KWP_CONF.googleFontList = [];
		KWP_CONF.CORSImg = "//s3.cn-north-1.amazonaws.com.cn/upload.creator5.permanent/thumb.jpg";

        KWP_CONF.regularFontList = ["习俗 in kw server","CN_FZLTH_GB18030","CN_FZXKT_GB18030","CN_FZXSS_GB18030", "CN_FZLTH_GB18030","方正舒体"];
		
		KWP_CONF.mpixPhotoExport	= <?php echo $mpix_photo_export; ?>;

		KWP_CONF.maxUploadRetries	= <?php echo $max_upload_retries; ?>;
		// 801 - 812
		//KWP_CONF.static_serv = "<?php echo $static_serv ?>";
		KWP_CONF.client = "<?php echo $client ?>";
		KWP_CONF.uploadedThumbSize = <?php echo $uploadedThumbSize ?>;
		KWP_CONF.lowres_warning_dpi_ratio = <?php echo $lowres_warning_dpi_ratio ?>;
		KWP_CONF.lowres_warning_pixelsize = <?php echo $lowres_warning_pixelsize ?>;
		KWP_CONF.page_flip_time = <?php echo $page_flip_time ?>;
		KWP_CONF.searchEnabled = <?php echo $searchEnabled ?>;
		KWP_CONF.autosave = <?php echo $autosave ?>;
		KWP_CONF.autosave_delay = <?php echo $autosave_delay ?>;
		KWP_CONF.save_warning = <?php echo $save_warning ?>;
		KWP_CONF.regularFontList = [<?php echo $regularFontList ?>];
		KWP_CONF.googleFontList = [<?php echo $googleFontList ?>];
		KWP_CONF.frame_category_chooser = <?php echo $frame_category_chooser; ?>;
		KWP_CONF.textframe_category_chooser = <?php echo $textframe_category_chooser;?>;
		// 002 202

		clipart_select = mylocale["<?php echo $clipartsSelected ?>"];
		if(clipart_select == undefined){
            clipart_select = "<?php echo $clipartsSelected ?>";
        }
		KWP_CONF.clipartsSelected = clipart_select;

        background_select = mylocale["<?php echo $backgroundsSelected ?>"];
        if(background_select == undefined){
            background_select = "<?php echo $backgroundsSelected ?>";
        }
        KWP_CONF.backgroundsSelected = background_select;
		
		var templet_design = <?php echo $templet_design; ?>;
		console.log("templet_design="+templet_design);
		if(parseInt(templet_design) == 0){
			console.log("add Server Code");
			KWP_CONF.s3bucketShort = "upload.creator5.permanent";
			KWP_CONF.s3bucketShortEU = "upload.creator5.6months";
			KWP_CONF.s3bucket = "//s3.cn-north-1.amazonaws.com.cn/"+KWP_CONF.s3bucketShort+"/";
			KWP_CONF.s3bucketEU = "//s3.cn-north-1.amazonaws.com.cn/"+KWP_CONF.s3bucketShortEU+"/";
			KWP_CONF.authorizationServer = "http://<sub>/authorize/";
			KWP_CONF.authorizationServerSD = ["54.223.189.133"];
		}else{
			//do nothing
			console.log("move Server Code");
		}


        // translation dict
		KWC.locale = mylocale;
		
        // retrieving projectkey if any (this should sent server side)
        if(typeof document.URL.split("#/")[1] !== "undefined") {
            var projectkey = document.URL.match(/\/([0-9a-f]{40})\//)[1];
        }
		
        // for books
        var page_spec = {};
        page_spec.width = <?php echo $page_spec_width ?>;
        page_spec.height = <?php echo $page_spec_height ?>;
		page_spec.border = <?php echo $page_spec_border ?>;
		page_spec.shading = <?php echo $page_spec_shading ?>;
		page_spec.inner_cover_editable = <?php echo $inner_cover_editable ?>;
		
        page_spec.dpi = <?php echo $page_spec_dpi ?>;
        page_spec.cropmarks = <?php echo $page_spec_cropmarks ?>;
        page_spec.bleed = new Margin(<?php echo $page_spec_bleed ?>);
        page_spec.margin = new Margin(<?php echo $page_spec_margin ?>);
        page_spec.format = "<?php echo $page_spec_format ?>";
        page_spec.min_pages = <?php echo $page_spec_min_pages ?>;
        page_spec.num_pages = <?php echo $page_spec_num_pages ?>;
        page_spec.max_pages = <?php echo $page_spec_max_pages ?>;
        page_spec.inc_pages = <?php echo $page_spec_inc_pages ?>;
        page_spec.filename = "<?php echo $page_spec_filename ?>";

      
        var cover_spec = {};
        cover_spec.width = <?php echo $cover_spec_width ?>;
        cover_spec.height = <?php echo $cover_spec_height ?>;
        cover_spec.dpi = <?php echo $cover_spec_dpi ?>;
        cover_spec.cropmarks = <?php echo $cover_spec_cropmarks ?>;
        cover_spec.bleed = new Margin(<?php echo $cover_spec_bleed ?>);
        cover_spec.margin = new Margin(<?php echo $cover_spec_bleed ?>);
        //cover_spec.spine_width = [[36,10]];
		cover_spec.spine_width = <?php echo $cover_spec_spine_width ?>;
        cover_spec.format = "<?php echo $cover_spec_format ?>";
        cover_spec.filename = "<?php echo $cover_spec_filename ?>";

        var product;
		var productType = "<?php echo $productType ?>";
		// 101 100
		if("AgendaProduct" == productType){
			product = new AgendaProduct (
				cover_spec,
				page_spec
			);
		}else if("Book" == productType){
			product = new Book (
				"<?php echo $book_type ?>",
				cover_spec,
				page_spec
			);
		}else if("CanvasProduct" == productType){
			product = new CanvasProduct (
				page_spec
			);
		}else if("CardProduct" == productType){
			product = new CardProduct (
				"<?php echo $card_type ?>",
				page_spec
			);
		}else if("GiftProduct" == productType){
			product = new GiftProduct (
				page_spec
			);
		}



       
       
		var customer_creation_id = "<?php echo $customer_creation_id; ?>";
		var tmp_save_key = '';
		var tmp_thumbnail = '';
		var tmp_creation_id = '';
        var theme_select = mylocale["<?php echo $selected_themes ?>"];
        if(theme_select == undefined){
            theme_select = "<?php echo $selected_themes ?>";
        }
        theme_select = "<?php echo $selected_themes ?>";
		console.log(theme_select);
        $("#koffeecreator").koffeeCreator({
			
            product: product,
            resources_directory: KWP_CONF.s3bucket + "cootoo/",
            themes_type:"<?php echo $themes_type ?>",
			selected_themes:theme_select,
			<?php if(!empty($theme_id)&&empty($action)) { ?>
			load_project: "<?php echo $theme_id;?>",
			load_as_theme:true,
			<?php } else { ?>
			load_project: (typeof projectkey != "undefined" ? projectkey : null),
			<?php } ?>
            
            onload: function() {
                // when application is fully loaded
                // for user's attention
			<?php if (!empty($action)&&$action=='createdesign') { ?>
					$("#clear_creation").trigger('click');
			<?php } ?>
            $("input[type=file]").attr("accept", "image/jpeg, image/png, image/jpg");
            <?php if($autofill=="yes") { ?>
                	$("#autofill_toggle").remove();
                <?php } ?>
               
                <?php if($hide_Themes_TagSearch=="yes") { ?>
                	$("#themes_block .search-container").css("visibility", "hidden");
                <?php } ?>
                <?php if($hide_Layouts_TagSearch=="yes") { ?>
                	$("#themes_block .search-container").css("visibility", "hidden");
                <?php } ?>
              
				// 201 203
				
	
                setTimeout(function(){ $(".placeholder").effect("bounce", { duration:2000 }); }, 2000);
                if(true == <?php echo $hideClipartsTagSearch ?>){
					KWP_utils.hideClipartsTagSearch();
				}
				if(true == <?php echo $hideBackgroundsTagSearch ?>){
					KWP_utils.hideBackgroundsTagSearch();
				}
				<?php if($showlayout){ ?>
                    $('#show_themes').hide();
                    setTimeout(function(){
                        $('#show_layouts').trigger('click');
                        $('.theme-zone span').text(KWC.translate('Layout'));
                    },2000);
				<?php } ?>



				$('#select-upload-way').on('click',function(){

                    if($('#upload-ways').is(':hidden')){　　//如果node是隐藏的则显示node元素，否则隐藏
                        $('#upload-ways').show();
                    }else{
                        $('#upload-ways').hide();
                    }
                });
            },
            
            saveComplete: function(save_key, thumbnail, creation_id, autosaved,fileArray) {
                // when user's save action finished
                console.log("Usersave complete, autosaved: "+autosaved.toString());
                console.log(arguments);
				// console.log('saveComplete - gloBalFileArray',_gloBalCanvasFileArray.join('|'))
				
			/*
				var html='    save_key is '+save_key+'    creation_id is '+creation_id;//声明js变量
				document.getElementById('myId').innerHTML = html;//找到id为'myId'的标签内插入html变量的值
				//document.getElementById('myId').innerText = html;//找到id为'myId'的标签替换它的内容为html的值
				*/
				
                // you should adapt this line to fit your server route for project loading
              
                var creator = "<?php echo $creator;?>";
				var add_price = 0;
				var onepage_price = <?php echo $p_product_onepage_price; ?>;
				if(current_page_num > spec_num_pages){
					add_price = onepage_price*(current_page_num - spec_num_pages);
				}
				var add_allpage_price = 0;
				var allpage_price = <?php echo $p_product_allpage_price; ?>;
				if(current_page_num >= spec_num_pages){
					add_price += allpage_price*onepage_price*current_page_num;
				}
				report = KWP_utils.creationContentsReport();
				empty_page_count = report.empty_page_count;
				low_resolution_count=report.low_resolution_count;
				total_page_count = report.total_page_count;
				picqty = manager.getImageList().length;
				//picqty = 12;
                function gotonextpage(){
					$.ajax({
						url: '<?php echo $savecallback?$savecallback:"index.php?route=account/diydesign/aftersavedesigncallback";?>',
						type: 'post',
						data:'product_id='+'<?php echo $product_id;?>'+'&action='+action+'&history=0&thumbnails='+_gloBalCanvasFileArray.join('|')+'&customer_creation_id='+customer_creation_id+'&add_price='+add_price+'&save_key='+save_key+'&thumbnail='+thumbnail+'&creator='+creator+'&creation_id='+creation_id+'&option=<?php echo base64_encode($option);?>&pages='+current_page_num+'&design_name='+$("#input_design_name").val()+'&picqty='+picqty+'&empty_page_count='+empty_page_count+'&low_resolution_count='+low_resolution_count+'&total_page_count='+total_page_count,
						dataType: 'json',
						success: function(json) {
							if(json['success']){
								customer_creation_id = json['customer_creation_id'];
								action = 'edit';
								isLogin=1;
								//location=json['redirect'];
							}else{
								if(json['code']==10){
									isLogin=0;
								}
							}
						}
					});	
				}
				
				setTimeout(gotonextpage, 1000);		
            },

        doUploadCompleteCallback: function(creation_id,save_key) {
				// when upload is complete, we can post to cart
            var creator = "<?php echo $creator;?>";
			// console.log('doUploadCompleteCallback - gloBalFileArray',_gloBalCanvasFileArray.join('|'))
            var add_price = 0;
            var onepage_price = <?php echo $p_product_onepage_price; ?>;
            if(current_page_num > spec_num_pages){
                add_price = onepage_price*(current_page_num - spec_num_pages);
            }
            var add_allpage_price = 0;
            var allpage_price = <?php echo $p_product_allpage_price; ?>;
            if(current_page_num >= spec_num_pages){
                add_price += allpage_price*onepage_price*current_page_num;
            }
            thumbnail = '';
            report = KWP_utils.creationContentsReport();
            empty_page_count = report.empty_page_count;
            low_resolution_count=report.low_resolution_count;
            total_page_count = report.total_page_count;
            picqty = manager.getImageList().length;
            //picqty = 12;
            function gotonextpage(){
                $.ajax({
                    url: 'index.php?route=account/diydesign/afteruploaddesigncallback',
                    type: 'post',
                    data:'product_id='+'<?php echo $product_id; ?>'+'&action='+action+'&history=0&thumbnails='+_gloBalCanvasFileArray.join('|')+'&customer_creation_id='+customer_creation_id+'&add_price='+add_price+'&save_key='+save_key+'&thumbnail='+thumbnail+'&creator='+creator+'&creation_id='+creation_id+'&option=<?php echo base64_encode($option);?>&pages='+current_page_num+'&picqty='+picqty+'&empty_page_count='+empty_page_count+'&design_name=default_bak'+'&low_resolution_count='+low_resolution_count+'&total_page_count='+total_page_count,
                    dataType: 'json',
                    success: function(json) {
                        if(json['success']){
                            customer_creation_id = json['customer_creation_id'];
                            action = 'edit';
                            isLogin=1;
                            alert('<?php echo $text_save_success;?>')
                            //location=json['redirect'];
                        }else{
                            if(json['code']==10){
                                isLogin=0;
                            }
                        }
                    }
                });


            }

            setTimeout(gotonextpage, 1000);
			},
            uploadComplete: function(save_key, thumbnail, creation_id) {
                // when upload is complete, we can post to cart

                tmp_save_key = save_key;
                tmp_thumbnail = thumbnail;
                tmp_creation_id = creation_id;
                $('#modal-save-design-name').show();	
                $("#order_zone").hide();
            }
        });

		$(".cancel-save-design-name").on('click',function(){
			 	$('#modal-save-design-name').hide();
		})

		$(".save-design-name").on('click',function(){
            console.log("Order complete");
            // console.log('save-design-name gloBalFileArray',_gloBalCanvasFileArray.join('|'))
			var creator = "<?php echo $creator;?>";
        	var add_price = 0;
			var onepage_price = <?php echo $p_product_onepage_price; ?>;
			if(current_page_num > spec_num_pages){
				add_price = onepage_price*(current_page_num - spec_num_pages);
			}
			var add_allpage_price = 0;
			var allpage_price = <?php echo $p_product_allpage_price; ?>;
			if(current_page_num >= spec_num_pages){
				add_price += allpage_price*onepage_price*current_page_num;
			}

			report = KWP_utils.creationContentsReport();
			empty_page_count = report.empty_page_count;
			low_resolution_count=report.low_resolution_count;
			total_page_count = report.total_page_count;
			picqty = manager.getImageList().length;
			//picqty = 12;
			console.log(report);
            function gotonextpage(){
				$.ajax({
					url: '<?php echo $savecallback?$savecallback:"index.php?route=account/diydesign/aftersavedesigncallback";?>',
					type: 'post',
					data:'product_id='+'<?php echo $product_id; ?>'+'&action='+action+'&history=1&thumbnails='+_gloBalCanvasFileArray.join('|')+'&customer_creation_id='+customer_creation_id+'&add_price='+add_price+'&save_key='+tmp_save_key+'&thumbnail='+tmp_thumbnail+'&creator='+creator+'&creation_id='+tmp_creation_id+'&option=<?php echo base64_encode($option);?>&pages='+current_page_num+'&design_name='+$("#input_design_name").val()+'&picqty='+picqty+'&empty_page_count='+empty_page_count+'&low_resolution_count='+low_resolution_count+'&total_page_count='+total_page_count,
					dataType: 'json',
					success: function(json) {
						if(json['success']){
							if(json['code']==10){
								isLogin=0;
							}else{
								location=json['redirect'];
								isLogin=1;
							}

						}else{
							if(json['code']==10){
								isLogin=0;
							}
						}
					}
				});
			}
			setTimeout(gotonextpage, 1000);
			if(!isLogin){
				$('#modal-quicksignup').modal('show');
			}
		});

        $(document).ready(function(){
            $(".fix-input").keydown(function (event) {
                if(event.keyCode==8){
                    var v = $(this).val();
                    if(v!=''){
                        v=v.substring(0,v.length-1);
                        $(this).val(v);
                    }
                }

            });
        });





        $(document).on("click", ".testupload", function() {
            console.log(window.uploader);
            console.log(uploader);
            window.uploader.doUploadComplete();
        });

        setTimeout(function () {
			$('#save_project').trigger('click');
        },300000);
    </script>
	
</body>
<!-- -->

</html>