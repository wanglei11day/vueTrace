<template>
    <div class="main">
			<div class="workbench">
				<div id="navleft">
					<div class="left">
					    <span class="www">
			             <i><svg t="1566438048482" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="994" width="48" height="48"><path d="M198.4 473.6l332.8-332.8c6.4-6.4 25.6 0 25.6 12.8l0 256 262.4-262.4c6.4-6.4 25.6 0 25.6 12.8l0 729.6c0 12.8-12.8 19.2-25.6 12.8L556.8 627.2l0 256c0 12.8-12.8 19.2-25.6 12.8L198.4 550.4C172.8 531.2 172.8 492.8 198.4 473.6z" p-id="995" fill="#999999"></path></svg></i>
					    	工作台</span>
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
				
				<div class="title">申请提醒</div>
				<ul class="titleName">
					<li>
						<input type="checkbox" />
						序号</li>
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
					<li style=" color: #009688;">客户更改时间  </li>
					<li>  <button>同意</button><button>重排</button><button>拒绝</button> </li>
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
					<li></li>
					<li> </li>
				</ul>
			</div>
			<!--快捷键-->
			<div class="shortcutKeys">
			 <div class="sctitle">
				<p>快捷键</p>
				<p>添加快捷键</p>
			</div>
			<div >
				<ul class="list">
					<li>
						<img src="@/assets/js.png"/>
						<p>讲师</p> 
					</li>
					<li>
						<img src="@/assets/kh.png"/>
						<p>客户管理</p> 
					</li>
					<li>
						<img src="@/assets/ph.png"/>
						<p>客户管理</p> 
					</li>
					<li>
						<img src="@/assets/zb.png"/>
						<p>客户管理</p> 
					</li>
				</ul>
			</div>
			</div>
		</div>
</template>
<script>
export default {
    
}
</script>

<style  scoped>
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
	color: #009688;
	background: #f2f3f5;
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
	margin: auto;
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
.titleName li input{
	vertical-align: middle;
	margin-right: 15px;
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
.perCancel{
	width: 97%;
	height: 300px;
	margin: 20px auto;
	background-color: #ffffff;
	border: solid 1px #dbdbdb;
}
.perCancel .title{
	width: 100%;
	height: 50px;
	margin-bottom: 30px;
	padding-left: 15px;
	text-align: left;
	line-height: 50px;
	color: #666666;
	background-color: #ffffff;
	box-shadow: 0px 1px 0px 0px 
		#dbdbdb;
}
.titleContent li button{
	width: 43px;
	height: 20px;
	background-color: #ffffff;
	border: solid 1px #ff3333;
	border: solid 1px #009688;
		font-size: 12px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #009688;
}
.titleCancel{
	width: 97%;
	height: 44px;
	margin: auto;
	background: #f5f5f5;
}


.shortcutKeys{
	width: 97%;
	height: 277px;
	margin: auto;
	background: #fff;
}
.sctitle{
	width: 100%;
	height: 40px;
	background-color: #ffffff;
	border: solid 1px #dbdbdb;
	display: flex;
	line-height: 40px;
	padding-left: 10px;
	box-sizing: border-box;
	padding-right: 10px;
	justify-content: space-between;
}
.list{
	width: 100%;
	display: flex;
	margin-top: 69px;
	margin-left: 56px;
	justify-content: flex-start;
	
	}
.list li{
	margin-right: 99px;
	text-align: center;
}
.list li img{
	display: block;
	margin-bottom: 10px;
}	
</style>