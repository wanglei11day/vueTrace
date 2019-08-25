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
			<div class="perSetting">
				<div class="title">权限设置</div>
				<div class="tip">
					<p>提示&nbsp;&nbsp;1、角色代表操作权限和数据权限，操作权限是指具有该角色的用户是否能查看、新增、编辑、删除某些数据，数据权限是指具有该角色的用户能操作的数据的范围；<br />
                        &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;2、超级管理员的权限是系统默认的最大权限的角色，不能修改。</p>
				</div>
			</div>
			<!--角色和超级管理员-->
			<div class="super">
				<div class="role">
					<div class="roleTitle">角色</div>
					<div class="roleAdmin">
						<span class="text">管理员</span>
						<span class="text">系统默认</span>
					</div>
					<div class="roleCount">
						<span class="text">员工</span>
						<span class="text">系统默认</span>
					</div>
					<div class="roleCount">
						<span class="text">中台</span>
						<span class="text">系统默认</span>
					</div>
				</div>
				<div class="admin">
					<div class="adminTitle">超级管理员 设置</div>
					<!--工作台权限-->
					<div class="workPermiss">
						<span>工作台权限</span>
						<p>展开
							<i><svg t="1566445372587" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13498" width="14" height="14"><path d="M944.083879 289.536302c14.264888 18.286482 15.462157 41.208541 2.670829 51.196009l-417.191704 325.421604c-2.885723 2.261507-12.156878 7.828292-13.732769 9.578146-15.544021 17.181311-37.893029 22.338774-49.947576 11.461029l-392.908648-354.309538c-12.054547-10.887978-9.271154-33.666774 6.272867-50.889017 15.544021-17.21201 37.893029-22.349007 49.988508-11.471262l357.584118 322.443783 408.258241-318.463122C907.910005 264.516466 929.84969 271.260054 944.083879 289.536302z" p-id="13499" fill="#7f7f7f"></path></svg></i>
						</p>
					</div>
					<div class="select">
						<input type="checkbox"  />&nbsp;查看
						<input type="checkbox"  />&nbsp;修改人员
						<input type="checkbox"  />&nbsp;导出
						<input type="checkbox"  />&nbsp;打印
					</div>
					<!--工作台权限-->
					<div class="workPermiss">
						<span>排行榜权限</span>
						<p>展开
							<i><svg t="1566445372587" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13498" width="14" height="14"><path d="M944.083879 289.536302c14.264888 18.286482 15.462157 41.208541 2.670829 51.196009l-417.191704 325.421604c-2.885723 2.261507-12.156878 7.828292-13.732769 9.578146-15.544021 17.181311-37.893029 22.338774-49.947576 11.461029l-392.908648-354.309538c-12.054547-10.887978-9.271154-33.666774 6.272867-50.889017 15.544021-17.21201 37.893029-22.349007 49.988508-11.471262l357.584118 322.443783 408.258241-318.463122C907.910005 264.516466 929.84969 271.260054 944.083879 289.536302z" p-id="13499" fill="#7f7f7f"></path></svg></i>
						</p>
					</div>
					<div class="select">
						<input type="checkbox"  />&nbsp;查看
						<input type="checkbox"  />&nbsp;修改人员
						<input type="checkbox"  />&nbsp;导出
						<input type="checkbox"  />&nbsp;打印
					</div>
				</div>
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
.perSetting{
	width: 97%;
	height: 185px;
	margin: 20px auto;
	background-color: #ffffff;
	border: solid 1px #dbdbdb;
}
.perSetting .title{
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
.perSetting .tip{
		width: 97%;
	height: 86px;
	margin: 20px auto;
	background-color: #f5f5f5;
	box-shadow: -4px 0px 0px 0px 
		#009688;
	border: solid 1px #dbdbdb;
}
.perSetting .tip p{
	padding: 15px;
	
	font-size: 15px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #666666;
}
.super{
	display: flex;
	width: 97%;
	margin: auto;
	height: 500px;
	}
.super .role{
	flex: 1;
	background-color: #ffffff;
	border: solid 1px #dbdbdb;
	margin-right: 19px;
}
.super .admin{
	flex: 2;
	background-color: #ffffff;
	border: solid 1px #dbdbdb;
}
.super .role .roleTitle{
	width: 95%;
	height: 50px;
	background-color: #ffffff;
	box-shadow: 0px 1px 0px 0px 
		#dbdbdb;
		line-height: 50px;
		margin: auto;
		color: #666;
}
.roleAdmin{
	width: 95%;
	height: 48px;
	margin: auto;
	margin-top: 13px;
	background-color: #009688;
	display: flex;
	justify-content: space-between;
	box-sizing: border-box;
	padding: 0 5px;
}
.roleAdmin span{
	line-height: 48px;
	color: #fff;
	font-size: 14px;
}

.roleCount{
	width: 95%;
	height: 48px;
	margin: auto;
	margin-top: 13px;
	
	display: flex;
	justify-content: space-between;
	box-sizing: border-box;
	padding: 0 5px;
}
.roleCount span{
	line-height: 48px;
	color: #666;
	font-size: 14px;
}
.adminTitle{
	width: 97%;
	height: 50px;
	background-color: #ffffff;
	box-shadow: 0px 1px 0px 0px 
		#dbdbdb;
	line-height: 50px;
	margin: auto;
	font-size: 16px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #666666;
}
.workPermiss{
	width: 97%;
	height: 40px;
	background-color: #f5f5f5;
	border: solid 1px #dbdbdb;
	margin: 25px auto;
	line-height: 40px;
	display: flex;
	justify-content: space-between;
	padding: 0 8px;
	box-sizing: border-box;
}
.workPermiss svg{
	vertical-align: middle;
}
.workPermiss span, .workPermiss p{
	font-size: 16px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #4d4d4d;
}
.select{
	width: 90%;
	height: 80px;
	
}
.select input{
	width: 16px;
	height: 16px;
	vertical-align: middle;
	margin-left: 20px;
}
</style>