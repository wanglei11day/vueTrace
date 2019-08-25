<template>
    <div class="main" >
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
		
			<div class="contCent">
				<div class="orgStuct">
					<ul class="headTitle">
						<li>组织架构</li>
						<li v-on:click="open()">
							<i><svg t="1566531397951" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2102" width="18" height="18"><path d="M544 128 480 128 480 480 128 480 128 544 480 544 480 896 544 896 544 544 895.936 544 895.936 480 544 480Z" p-id="2103" fill="#33aba0"></path></svg></i>
							增加
						</li>
					</ul>
					<ul class="add">
						<li style="position: relative;">
							<span >企微排班架构</span>
							<svg style="position: absolute;" t="1566531857441" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2369" width="16" height="16"><path d="M904 64c30.9 0 56 25.1 56 56v784c0 30.9-25.1 56-56 56H120c-30.9 0-56-25.1-56-56V120c0-30.9 25.1-56 56-56h784m0-64H120C53.7 0 0 53.7 0 120v784c0 66.3 53.7 120 120 120h784c66.3 0 120-53.7 120-120V120c0-66.3-53.7-120-120-120z" fill="#33aba0" p-id="2370"></path><path d="M736 480H288c-17.7 0-32 14.3-32 32s14.3 32 32 32h448c17.7 0 32-14.3 32-32s-14.3-32-32-32z" fill="#33aba0" p-id="2371"></path><path d="M480 288v448c0 17.7 14.3 32 32 32s32-14.3 32-32V288c0-17.7-14.3-32-32-32s-32 14.3-32 32z" fill="#33aba0" p-id="2372"></path></svg>
						</li>
						<li>添加分组</li>
					</ul>
					<ul class="treeMenu">
						<li>
							<img src="img/aa.png"/>
							<a id="currId" href="javascript:void(0);">成都组</a>
							<ul class="hidden">
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">周杰伦</a></li>
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">谢霆锋</a></li>
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">李建</a></li>
							</ul>
						</li>
						<li>
							<img src="img/aa.png"/>
							<a id="currId" href="javascript:void(0);">成都组</a>
							<ul class="hidden">
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">周杰伦</a></li>
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">谢霆锋</a></li>
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">李建</a></li>
							</ul>
						</li>
						<li>
							<img src="img/aa.png"/>
							<a id="currId" href="javascript:void(0);">成都组</a>
							<ul class="hidden">
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">周杰伦</a></li>
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">谢霆锋</a></li>
								<li><img src="img/ntx.png"/><a href="javascript:void(0);">李建</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="enterWx">
					<ul class="enheadTitle" >
						<li>
							<input type="text" placeholder="这里输入关键词" />
							<select >
							  <option value="volvo" selected = "selected">请选择角色</option>
							  <option value="saab">Saab</option>
							  <option value="opel">Opel</option>
							  <option value="audi">Audi</option>
							</select>
							<select >
							  <option value="volvo" >是否培训师</option>
							  <option value="saab">是</option>
							  <option value="opel">否</option>
							</select>
							<select >
							  <option value="volvo" >在用</option>
							  <option value="saab">是</option>
							  <option value="opel">否</option>
							</select>
							<button>查询</button>
						</li>
						<li>
							<button>同步企业微信</button>
						</li>
					</ul>
					<div class="table">
						<ul class="tableHead">
							<li><input type="checkbox" name="" id="" value="" /></li>
							<li>序号</li>
							<li>姓名</li>
							<li>角色</li>
							<li>部门</li>
							<li>微信绑定</li>
							<li>最近登录</li>
							<li>操作</li>
						</ul>
						<ul class="tableBody">
							<li><input type="checkbox" name="" id="" value="" /></li>
							<li>2</li>
							<li>e</li>
							<li>r</li>
							<li>部门</li>
							<li>微gj信绑定</li>
							<li>hyj</li>
							<li><button>编辑</button><button>操作</button></li>
						</ul>
						<ul class="tableBody">
							<li><input type="checkbox" name="" id="" value="" /></li>
							<li>2</li>
							<li>e</li>
							<li>r</li>
							<li>部门</li>
							<li>微gj信绑定</li>
							<li>hyj</li>
							<li>操kikk作</li>
						</ul>
						<ul class="tableBody">
							<li>11</li>
							<li>2</li>
							<li>e</li>
							<li>r</li>
							<li>部门</li>
							<li>微gj信绑定</li>
							<li>hyj</li>
							<li>操kikk作</li>
						</ul>
					</div>
					<div class="addWorker" v-show="shower">
						<ul class="addHeadTitle">
							<li>组织架构</li>
							<li>
								<i><svg t="1566531397951" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2102" width="18" height="18"><path d="M544 128 480 128 480 480 128 480 128 544 480 544 480 896 544 896 544 544 895.936 544 895.936 480 544 480Z" p-id="2103" fill="#33aba0"></path></svg></i>
								增加
							</li>
					    </ul>
					    
					    <div class="riqi">
					    	<p>日期： </p>
					    	<span>2019-08-21</span>
					    </div>
					    <div class="changeRole">
					    	<p>选择分组： </p>
					    	<input type="text" placeholder="广州组"/>  
					    </div>
					    <div class="changeRole">
					    	<p>选择分组： </p>
					    	<input type="text" placeholder="员工"/>  
					    </div>
					    <div class="changeRole">
					    	<p>添加人员：</p>
					    	<div class="addBox">
					    		<div class="listWorker">
					    			<ul class="add">
										<li style="position: relative;">
											<span >企微排班架构</span>
											<svg style="position: absolute;" t="1566531857441" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2369" width="16" height="16"><path d="M904 64c30.9 0 56 25.1 56 56v784c0 30.9-25.1 56-56 56H120c-30.9 0-56-25.1-56-56V120c0-30.9 25.1-56 56-56h784m0-64H120C53.7 0 0 53.7 0 120v784c0 66.3 53.7 120 120 120h784c66.3 0 120-53.7 120-120V120c0-66.3-53.7-120-120-120z" fill="#33aba0" p-id="2370"></path><path d="M736 480H288c-17.7 0-32 14.3-32 32s14.3 32 32 32h448c17.7 0 32-14.3 32-32s-14.3-32-32-32z" fill="#33aba0" p-id="2371"></path><path d="M480 288v448c0 17.7 14.3 32 32 32s32-14.3 32-32V288c0-17.7-14.3-32-32-32s-32 14.3-32 32z" fill="#33aba0" p-id="2372"></path></svg>
										</li>
										<li></li>
								    </ul>
								    <ul class="treeMenu">
										<li>
											<img src="img/aa.png"/>
											<a id="currId" href="javascript:void(0);">成都组</a>
											<ul class="hidden">
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">周杰伦</a></li>
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">谢霆锋</a></li>
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">李建</a></li>
											</ul>
										</li>
										<li>
											<img src="img/aa.png"/>
											<a id="currId" href="javascript:void(0);">成都组</a>
											<ul class="hidden">
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">周杰伦</a></li>
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">谢霆锋</a></li>
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">李建</a></li>
											</ul>
										</li>
										<li>
											<img src="img/aa.png"/>
											<a id="currId" href="javascript:void(0);">成都组</a>
											<ul class="hidden">
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">周杰伦</a></li>
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">谢霆锋</a></li>
												<li><img src="img/ntx.png"/><a href="javascript:void(0);">李建</a></li>
											</ul>
										</li>
									</ul>
					    		</div>
					    		
					    		<div class="listperson">
					    			<ul class="peopleList">
					    				<li>
					    					<img src="img/ntx.png"/>
					    					<a href="javascript:void(0);">李建</a>
											<img src="img/ntx.png"/>
					    				</li>
					    			</ul>
					    		</div>
					    	</div> 
					    </div>
					</div>
				</div>
			</div>
		</div>
</template>
<script>
export default {
    data(){
		return{
			 shower:false
		}
       
    },
    methods:{
			open(){
				this.shower=!this.shower
			}
		}

    
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


.contCent{
	width: 97%;
	margin: 20px auto;
	display: flex;
	
	
}
.contCent .orgStuct{
	width: 300px;
	flex: 1;
	background: #fff;
	margin-right: 20px;
	height: 500px;
}
.contCent .enterWx{
	height: 500px;
	flex: 3;
	background: #fff;
	position: relative;
	
}
/*标题*/
.headTitle{
	width: 100%;
    height: 38px;
    border-bottom: #dbdbdb solid 1px;
    display: flex;
    justify-content: space-between;
    box-sizing: border-box;
}
.headTitle li{
	line-height: 38px;
    box-sizing: border-box;
    margin-left: 20px;
    margin-right: 20px;
    font-size: 16px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #4d4d4d;
}
.headTitle li svg{
	vertical-align: middle;	
}
.contCent .orgStuct .add{
	width: 100%;
    height: 38px;
    display: flex;
    margin-right: 20px;
    margin-top: 41px;
    justify-content: space-between;
    box-sizing: border-box;
}
.contCent .orgStuct .add li{
	line-height: 38px;
    box-sizing: border-box;
  font-size: 14px;
  margin-right: 20px;
  margin-left: 30px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #4d4d4d;
}
.contCent .orgStuct .add li svg{
	left: 50%;
    top: 50%;
    left: -11px;
    transform: translate(-50%, -50%);
}
.contCent .orgStuct .treeMenu{
	margin-left: 39px;
	margin-top: 22px;
}
.contCent .orgStuct .treeMenu li{
	margin-top: 26px;
	font-size: 13px;
}
.contCent .orgStuct .treeMenu li img{
	margin-right: 10px;
}
/*右边*/
.enheadTitle li button{
	width: 104px;
	height: 35px;
	background-color: #009688;
	border-radius: 2px;
	border: none;
	color: #fff;
}
/*标题*/
.enheadTitle{
	width: 100%;
    height: 70px;
    border-bottom: #dbdbdb solid 1px;
    display: flex;
    justify-content: space-between;
    box-sizing: border-box;
}
.enheadTitle li{
	line-height: 70px;
    box-sizing: border-box;
    margin-left: 20px;
    margin-right: 20px;
    font-size: 16px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #4d4d4d;
}
.enheadTitle li input{
	height: 25px;
	background-color: #ffffff;
	border-radius: 2px;
	border: solid 1px #e5e5e5;
}
.enheadTitle li select{
	width: 122px;
	height: 35px;
	background-color: #f5f5f5;
	border-radius: 2px;
	border: solid 1px #e5e5e5;
}
/*表格的头*/
.table{
	width: 97%;
	height: auto;
	margin: 20px auto;
	border-left: solid 1px #dbdbdb;
	border-top: solid 1px #dbdbdb;
}
.tableHead{
	width: 100%;
	display: flex;
	height: 44px;
}
.tableHead li{
	flex: 3;
	line-height: 44px;
	text-align: center;
	background: #f5f5f5;
	border-right: solid 1px #dbdbdb;
	border-bottom: solid 1px #dbdbdb;
}
.tableHead li:nth-child(1){
	flex: 1;
}
.tableHead li:nth-child(2){
	flex: 2;
}
/*标的身体*/
.tableBody{
	width: 100%;
	display: flex;
	height: 44px;
}
.tableBody:nth-child(2n+1){
	width: 100%;
	background: #F5F5F5;
	display: flex;
	height: 44px;
}
.tableBody li{
	flex: 3;
	line-height: 44px;
	text-align: center;
	border-right: solid 1px #dbdbdb;
	border-bottom: solid 1px #dbdbdb;
}
.tableBody li:nth-child(1){
	flex: 1;
}
.tableBody li:nth-child(2){
	flex: 2;
}
.tableBody li button:nth-child(1){
	width: 49px;
	height: 26px;
	font-size: 14px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #ffffff;;
	border: none;
	margin-right: 3px;
	background-color: #009688;
}
.tableBody li button:nth-child(2){
	width: 49px;
	height: 26px;
	font-size: 14px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #ffffff;
	border: none;
	background-color: #d15b47;
}


/*右边的弹框*/
.addWorker{
	width: 988px;
	height: 976px;
	background-color: #ffffff;
	box-shadow: 0px 0px 35px 0px 
		rgba(0, 0, 0, 0.15);
	border: solid 1px #dbdbdb;
	position: absolute;
	top: 0;
	right: 0;
}
.addHeadTitle{
	    width: 100%;
    height: 50px;
    border-bottom: #dbdbdb solid 1px;
    display: flex;
    justify-content: space-between;
    box-sizing: border-box;
}
.addHeadTitle li{
	line-height: 50px;
    box-sizing: border-box;
    margin-left: 20px;
    margin-right: 20px;
    font-size: 16px;
    font-weight: normal;
    font-stretch: normal;
    letter-spacing: 0px;
    color: #4d4d4d;
}
.addWorker .riqi{
	width: 100%;
	margin-top: 38px;
	margin-left: 19px;
	font-size: 16px;
	font-weight: normal;
	font-stretch: normal;
	
	letter-spacing: 0px;
	color: #666666;
}
.addWorker .riqi span{
display: inline-block;
font-family: MicrosoftYaHei;
	font-size: 14px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #666666;
	margin-left: 56px;
}
.addWorker .riqi p{
display: inline-block;
font-family: MicrosoftYaHei;
	font-size: 14px;
	width: 73px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #666666;
}
.addWorker .changeRole{
	margin-top: 28px;
	margin-left: 19px;
}
.addWorker .changeRole p{
	width: 73px;
	display: inline-block;
	font-size: 14px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #666666;
}
.addWorker .changeRole input{
	
	margin-left: 56px;
	width: 80%;
}
.addWorker .changeRole .addBox{
	width: 80%;
	height: auto;
	margin-top: -20px;
	margin-left: 138px;
	background-color: #ffffff;
	border: solid 1px #dbdbdb;
	display: flex;
	padding-bottom: 20px;
	
}
.addWorker .changeRole .addBox .listWorker{
	flex: 1;
	background-color: #ffffff;
	border-right: solid 1px #dbdbdb;
}
.addWorker .changeRole .addBox .listperson{
	flex: 2;
}
.addWorker .changeRole .addBox .listWorker .add{
	margin-top: 30px;
	margin-left: 45px;
}
.addWorker .changeRole .addBox .listWorker .add svg{
	position: absolute;
	left: -23px;
	top: 3px;
}
.addWorker .changeRole .addBox .listWorker .treeMenu{
	margin-top: 22px;
	margin-left: 65px;
}
.addWorker .changeRole .addBox .listWorker .treeMenu li{
	margin-top: 37px;
}
.peopleList{
	margin-left: 50px;
	margin-top: 24px;
}
.peopleList li img{
	vertical-align: middle;
	margin: auto 8px;
}
/*vue绑定样式*/
.show{
	display: none;
}

</style>