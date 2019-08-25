<template>
    <div class="main">
			<div class="workbench">
				<div id="navleft">
					<div class="left">
						<i><svg t="1566438048482" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="994" width="48" height="48"><path d="M198.4 473.6l332.8-332.8c6.4-6.4 25.6 0 25.6 12.8l0 256 262.4-262.4c6.4-6.4 25.6 0 25.6 12.8l0 729.6c0 12.8-12.8 19.2-25.6 12.8L556.8 627.2l0 256c0 12.8-12.8 19.2-25.6 12.8L198.4 550.4C172.8 531.2 172.8 492.8 198.4 473.6z" p-id="995" fill="#999999"></path></svg></i>
					    <span class="www">工作台</span>
					</div>
					<!--<div class="leftMargin">
						<span >权限设置</span>
						<i><svg t="1566439812853" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1879" width="16" height="16"><path d="M499.2 64c-249.6 0-448 198.4-448 448s198.4 448 448 448 448-198.4 448-448-198.4-448-448-448z m179.2 582.4c12.8 12.8 12.8 32 0 44.8-12.8 12.8-32 12.8-44.8 0L499.2 556.8l-134.4 134.4c-12.8 12.8-32 12.8-44.8 0s-12.8-32 0-44.8L454.4 512 320 377.6c-12.8-12.8-12.8-32 0-44.8s32-12.8 44.8 0l134.4 134.4 134.4-134.4c12.8-12.8 32-12.8 44.8 0 12.8 12.8 12.8 32 0 44.8L544 512l134.4 134.4z" fill="#b2b2b2" p-id="1880"></path></svg></i>				    
					</div>-->
				</div>
				<div class="navright">
					<i><svg t="1566441304237" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="12511" width="20" height="20"><path d="M838.4 473.6 505.6 134.4C499.2 128 480 134.4 480 147.2l0 256L217.6 134.4C211.2 128 192 134.4 192 147.2l0 729.6c0 12.8 12.8 19.2 25.6 12.8l262.4-262.4 0 256c0 12.8 12.8 19.2 25.6 12.8l339.2-339.2C864 531.2 864 492.8 838.4 473.6z" p-id="12512" fill="#999999"></path></svg></i>
					<span >关闭操作</span>
					<i><svg t="1566441552431" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13305" width="20" height="20"><path d="M23.87932707 267.93966356h976.24134586l-488.12067293 610.15084114z" p-id="13306" fill="#999999"></path></svg></i>
					<span >	刷新</span>	
				</div>
			</div>
			<!--表格-->
			<div class="teacherList">
				<table border="0" rules="all" >
				 <tr>
		            <th>序号</th>
		            <th>日期</th>
		            <th>上下午</th>
		            <th>已排班人员数</th>
		            <th>空闲人数（人）</th>
		            <th> 
		            	<p>张冠一</p>
		            	<p>张冠一</p>
		            	<p>张冠一</p>
		            	<p>张冠一</p>
		            </th>    
		         </tr>
				 <!-- 黄色的背景 -->
		        <tr>
		            <td rowspan="2">01</td>
		            <td rowspan="2">2019-08-14</td>
		            <td>上午</td>
		            <td>管理员</td>
		            <td>3000</td>
		            <td class="paiban">
		            	<button>排班</button>
		            	<button>排班</button>
		            	<button>排班</button>
		            	<button>排班</button>
		            </td>
		        </tr>
		        <tr>
		            <td>下午</td>
		            <td>管理员</td>
		            <td>3000</td>
		            <td class="paiban">
						<button>排班</button>
						<button>排班</button>
						<button>排班</button>
						<button>排班</button>
					</td>
		        </tr>
				<!-- 绿色的背景 -->
				
				<tr class="green">
				    <td rowspan="2">01</td>
				    <td rowspan="2">2019-08-14</td>
				    <td>上午</td>
				    <td>管理员</td>
				    <td>3000</td>
				    <td class="paiban">
				    	<button>排班</button>
				    	<button>排班</button>
				    	<button>排班</button>
				    	<button>排班</button>
				    </td>
				</tr>
				<tr class="green">
				    <td>下午</td>
				    <td>管理员</td>
				    <td>3000</td>
				    <td class="paiban">
						<button>排班</button>
						<button>排班</button>
						<button>排班</button>
						<button>排班</button>
					</td>
				</tr>
			   </table>
			</div>
			
			<!--<ul class="testTable">
				<li>序号</li>
				<li>日期</li>
				<li>上下午</li>
				<li>已排班人员数</li>
				<li>空闲人数（人）</li>
				<li>
					<span >张冠一</span>
					<span >张冠一</span>
					<span >张冠一</span>
					<span >张冠一</span>
					<span >张冠一</span>
					<span >张冠一</span>
					<span >张冠一</span>
					<span >张冠一</span>
				</li>
			</ul>-->
	
		
			
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



.teacherList{
	 width:97%; 
	 height:300px; 
	 overflow:scroll;
	 margin: 20px auto;
	 background: #fff;
}

.teacherList table{
	min-width:1620px; 
	height:100px; 
	text-align:center
}
.teacherList table tr{
	height: 40px;
	
}
.teacherList table tr th{
	line-height: 20px;
	background-color: #f5f5f5;
	border-bottom: none;
	border-right: 1px solid #cacaca;
	text-align: center;
	width: 100px;
}
.teacherList table tr th:nth-child(6){
	line-height: 20px;
	background-color: #f5f5f5;
	border-bottom: none;
	border-right: 1px solid #cacaca;
	text-align: center;
	min-width: 500px;
	text-align: left;
	margin-left: 20px;
}
.teacherList table tr th p{
	display: inline-flex;
	margin-left: 40px;
}
.teacherList table tr td{
	border: none;
}
.teacherList table tr td.paiban{
	text-align: left;
}
.teacherList table tr td button{
	margin-left: 40px;
	width: 46px;
	height: 20px;
	background-color: #009688;
	border: none;

	font-family: MicrosoftYaHei;
	font-size: 14px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #ffffff;
	font-family: MicrosoftYaHei;
	
	
	color: #ffffff;
}
.teacherList table tr td{
	background-color:rgb(255,254,242);
	
}
.teacherList table .green td{
	background-color: #edfaf9;
	color: #000000;
}
.testTable{
	width: 1600px;
	height: 40px;
	background: #fff;
	margin: 20px;
	display: flex;
	 overflow:scroll;
}
.testTable li{
	
	flex: 1;
	line-height: 40px;
	text-align: center;
}
.testTable li:nth-child(6){
flex: 3;	
display: flex;
}
.testTable li span{
	flex: 1;
}

</style>