<?php echo $header; ?>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><h1 class="heading-title"><?php echo $heading_title; ?></h1><h3><a href="<?php echo $add_album; ?>"><?php echo $text_add_album; ?></a></h3><?php echo $content_top; ?>
		<div class="s_orders_listing clearfix" id="tablecontent">
       
      </div>
    <div class="pagination"><?php echo $pagination; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
  <!-- end of content -->
<script>
	sendQuery();
	Date.prototype.format = function(format){
		var o = {
			 "M+" :  this.getMonth()+1,  //month
			 "d+" :  this.getDate(),     //day
			 "h+" :  this.getHours(),    //hour
			 "m+" :  this.getMinutes(),  //minute
			 "s+" :  this.getSeconds(), //second
			 "q+" :  Math.floor((this.getMonth()+3)/3),  //quarter
			 "S"  :  this.getMilliseconds() //millisecond
		  }
		 
		  if(/(y+)/.test(format)) {
			format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
		  }
		
		  for(var k in o) {
		   if(new RegExp("("+ k +")").test(format)) {
			 format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
		   }
		  }
		return format;
	}
	function sendQuery(){
		var queryfields = "[]";
		var query = "{queryFields:"+queryfields+",request_offset:<?php echo isset($page)?$page-1:0; ?>,request_length:<?php echo $pagelimit; ?>"+"}";
		var queryuri = "<?php echo $queryuri?>"+ "?callback=?";	
		var handleURL = queryuri;
		var arg = {
			"token":"<?php echo $querytoken; ?>",
			"query":query
		};
		$.getJSON(handleURL,arg,function(data) {
			var divs = "";
			$.each(data.data,function(n,value) { 
				$("#tablecontent").empty();
				var formatTime = "unknow";
				if(value.addedTimetick!=null&&value.addedTimetick!=0){
					var d = new Date();
					d.setTime(value.addedTimetick);
					formatTime = d.format("yyyy-MM-dd hh:mm:ss");
				}
				var albumname = value.name;
				var albumimageurl = "<?php echo $albumcoveruri?>"+'/'+value.image;
				var image = "<img  src=\""+albumimageurl+"\" />"
				var id = value.resourceId;
				var description = value.description;
				divs+="<div class=\"s_col_album\">";
				divs+=	" <div class=\"s_order clearfix\">";
				divs+=	"<p class=\"s_id\"><span class=\"s_999\"> <a href=\"<?php echo $album_photo; ?>&albumId="+id+"&name="+albumname+"\"><span class=\"s_main_color\">#<?php echo $text_album; ?>:"+n+"</a></span></p>";		
				divs+=	"<span class=\"clear\"></span>";		 
				divs+=	"<dl class=\"clearfix\">";
				divs+=	"<dt></dt>";
				divs+=	"<dd>"+image+"</dd>";				
				divs+=	"<dt><?php echo $text_album_name; ?>:</dt>";
				divs+=	"<dd>"+albumname+"</dd>";
				divs+=	"<dt><?php echo $text_album_description; ?>:</dt>";
				divs+=	"<dd>"+description+"</dd>";
				divs+=	"<dt><?php echo $text_date_added; ?>:</dt>";
				divs+=	"<dd>"+formatTime+"</dd>";
				divs+=	"<dt><a class=\"delalbum\" value=\""+id+"\"  href=\"#del\"><?php echo $text_delete; ?></a></dt>";
				divs+=	"</dl>";
				divs+=	"<span class=\"clear border_eee\"></span>";
				divs+=	"</div></div>";  
			   });
			$("#tablecontent").append(divs);
			 $(".delalbum").click(delalbum);
		},"json");
	}
	
	function delalbum(){
			var albumId =this.getAttribute('value');
			var handleURL = "<?php echo $platformuri?>"+ "/pubaccess/user/nav/album/del/"+albumId+"?callback=?";	
			var arg = {
				"token":"<?php echo $querytoken; ?>",
			};
			var cret=confirm("確認刪除相簿嗎？");
			if(cret){
				$.getJSON(handleURL,arg,function(data) {
					if(data.isSuccess){
						sendQuery();
					}
				},"json")
				.success(function(e) {
					
					})
				.error(function(e) { 
					alert("error"); 
				})
				.complete(function(e) {
				});
			}
			return false;
		}
</script>
<?php echo $footer; ?>