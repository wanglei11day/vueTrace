<template>
    <div class="main">
			<div class="workbench">
				<div id="navleft">
					<div class="left">
						<i><svg t="1566438048482" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="994" width="48" height="48"><path d="M198.4 473.6l332.8-332.8c6.4-6.4 25.6 0 25.6 12.8l0 256 262.4-262.4c6.4-6.4 25.6 0 25.6 12.8l0 729.6c0 12.8-12.8 19.2-25.6 12.8L556.8 627.2l0 256c0 12.8-12.8 19.2-25.6 12.8L198.4 550.4C172.8 531.2 172.8 492.8 198.4 473.6z" p-id="995" fill="#999999"></path></svg></i>
					    <span class="www">工作台</span>
					</div>
					<div class="leftMargin">
						<span >权限设置</span>
						<i><svg t="1566439812853" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1879" width="16" height="16"><path d="M499.2 64c-249.6 0-448 198.4-448 448s198.4 448 448 448 448-198.4 448-448-198.4-448-448-448z m179.2 582.4c12.8 12.8 12.8 32 0 44.8-12.8 12.8-32 12.8-44.8 0L499.2 556.8l-134.4 134.4c-12.8 12.8-32 12.8-44.8 0s-12.8-32 0-44.8L454.4 512 320 377.6c-12.8-12.8-12.8-32 0-44.8s32-12.8 44.8 0l134.4 134.4 134.4-134.4c12.8-12.8 32-12.8 44.8 0 12.8 12.8 12.8 32 0 44.8L544 512l134.4 134.4z" fill="#b2b2b2" p-id="1880"></path></svg></i>				    
					</div>
				</div>
				<div class="navright">
					<i><svg t="1566441304237" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="12511" width="20" height="20"><path d="M838.4 473.6 505.6 134.4C499.2 128 480 134.4 480 147.2l0 256L217.6 134.4C211.2 128 192 134.4 192 147.2l0 729.6c0 12.8 12.8 19.2 25.6 12.8l262.4-262.4 0 256c0 12.8 12.8 19.2 25.6 12.8l339.2-339.2C864 531.2 864 492.8 838.4 473.6z" p-id="12512" fill="#999999"></path></svg></i>
					<span >关闭操作</span>
					<i><svg t="1566441552431" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13305" width="20" height="20"><path d="M23.87932707 267.93966356h976.24134586l-488.12067293 610.15084114z" p-id="13306" fill="#999999"></path></svg></i>
					<span >	刷新</span>	
				</div>
			</div>
			<!--权限设置-->
			<div class="perCancel">
				
				<div class="title">权限设置</div>
				<ul class="titleName">
					<li>序号</li>
					<li>姓名</li>
					<li>组长</li>
					<li>所在组</li>
					<li>企业名称</li>
					<li>上下午</li>
					<li>预约时间</li>
					<li>所在地</li>
					<li>申请原因  </li>
					<li> 操作</li>
				</ul>
				<ul class="titleContent">
					<li>01</li>
					<li>姓名</li>
					<li>组长</li>
					<li>所在组</li>
					<li>企业名称</li>
					<li>上下午</li>
					<li>预约时间</li>
					<li>所在地</li>
					<li>申请原因  </li>
					<li> 操作</li>
				</ul>
				<ul class="titleCancel">
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li>  </li>
					<li> 操作</li>
				</ul>
			</div>
		</div>
</template>
<script>
export default {
    
}
</script>
<style scoped>
/*中间内容*/
.main{
	padding-top: 60px;
	padding-left: 220px;
	background: #f2f2f2;
	width: 100%;
	height: 500px;
	box-sizing: border-box;
}
.main .workbench{
	width: 100%;
	height: 34px;
	background: rgb(221,221,221);
	margin-top: 10px;
	
}
.main .workbench{
	display: flex;
	justify-content: space-between;
}
.main .workbench .left i svg{
	width: 18px;
	height: 13px;
	color: #333;
	display: inline-block;
	vertical-align: middle;
	margin-left: 15px;
	
}
.main .workbench .left{
	display: inline-block;
	margin-right: 3px;
}

.main .workbench .left span{
	line-height: 34px;
	letter-spacing: 0px;
	color: #808080;
	display: inline-block;
}

.main .workbench .leftMargin svg{
	height: 13px;
	color: #333;
	display: inline-block;
	vertical-align: middle;
	
}
.main .workbench .leftMargin span{
	margin-left: 15px;
}
.main .workbench .leftMargin span{
	line-height: 34px;
	letter-spacing: 0px;
	color: #009688;
	display: inline-block;
	
}
.main .workbench .leftMargin{
	display: inline-block;
    width: 94px;
	height: 34px;
	background-color: #f2f3f5;
}
.main .workbench .navright svg{
	vertical-align: middle;
}
.main .workbench .navright span{
	line-height: 34px;
}
.main .workbench .navright{
	margin-right: 30px;
}




/*取消申请*/
.titleName{
	width: 97%;
	height: 44px;
	background: #f5f5f5;
	margin: 5px auto;
	display: flex;
}
.titleName li{
	flex: 1;
	text-align: center;
	line-height: -44px;
	height: 24px;
	display: inline-block;
	vertical-align: middle;
	margin-top: 10px;
	border-right: solid #ddd 1px;
	
}
.titleName li:last-child{
	border-right: none;
}
.titleContent{
	width: 97%;
	height: 44px;
	background: #fff;
	margin: 5px auto;
	display: flex;
}
.titleContent li{
	flex: 1;
	text-align: center;
	line-height: 44px;
	height: 24px;
	display: inline-block;
	vertical-align: middle;
}





/*取消权限*/
.titleCancel{
	width: 97%;
	height: 44px;
	background: #f5f5f5;
	margin: 5px auto;
	display: flex;
}
.titleCancel li{
	flex: 1;
	text-align: center;
	line-height: 44px;
	height: 24px;
	display: inline-block;
	vertical-align: middle;
}
.perCancel{
	width: 97%;
	height: 255px;
	margin: 20px auto;
	background-color: #ffffff;
	border: solid 1px #dbdbdb;
}
.perCancel .title{
	width: 97%;
	height: 50px;
	margin: auto;
	text-align: left;
	line-height: 50px;
	color: #666666;
	background-color: #ffffff;
	box-shadow: 0px 1px 0px 0px 
		#dbdbdb;
}




</style>