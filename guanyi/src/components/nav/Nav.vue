<template>
    <div class="hello">
			<!-- <section class="sidebar">
				<ul>
					<li>
						<a href="#">
							<span>工作台</span>
						</a>
					</li>
					<li>
						<a href="#">
							<span>排班表</span>
						</a>
					</li>
					<li>
						<a href="#">
							<span>讲师列表</span>
						</a>
					</li>
					<li>
						<router-link to="/home/teachManage">
							<span>排行榜</span>
						</router-link>
					</li>
					<li>
						<a href="#">
							<span>客户管理</span>
						</a>
					</li>
					<li>
						<router-link to="/home/cancel">
							<span>取消申请</span>
						</router-link>
					</li>
					<li>
						<router-link to="/home/teachManage">
							<span>讲师管理</span>
						</router-link>
					</li>
					<li >
						<router-link to="/home/content">
							<span>权限设置</span>
						</router-link>
					</li>					
				</ul>
			</section> -->
			<!-- <el-radio-group v-model="isCollapse" style="margin-bottom: 20px;">
				<el-radio-button :label="false"><img src="@/assets/workpart.png" alt=""> </el-radio-button>
				<el-radio-button :label="true">收起</el-radio-button>
			</el-radio-group> -->
			<el-menu default-active="1-4-1" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" :collapse="isCollapse">
			

  <router-link to="/home/workbench">
	  <el-menu-item index="1">
		<img src="@/assets/workpart.png" alt=""> 
		<span slot="title">工作台</span>
	</el-menu-item>
  </router-link>
	
  <router-link to="/home/schedule">
	<el-menu-item index="2">
		<img src="@/assets/workpart.png" alt=""> 
		<span slot="title">排班表</span>
	</el-menu-item>
	</router-link>
	<router-link to="/home/teachlist">
		<el-menu-item index="3">
		<img src="@/assets/workpart.png" alt=""> 
		<span slot="title">讲师列表</span>
	</el-menu-item>
	</router-link>

		<router-link to="/home/ranking">
	<el-menu-item index="4">
		<img src="@/assets/workpart.png" alt=""> 
		<span slot="title">排行榜</span>
	</el-menu-item>
		</router-link>
		
		<router-link to="/home/customer">
	     <el-menu-item index="5">
			<img src="@/assets/workpart.png" alt=""> 
			<span slot="title">客户管理</span>
	    </el-menu-item>
	</router-link>

	<router-link  to="/home/cancel">
		<el-menu-item index="6">
		<img src="@/assets/workpart.png" alt=""> 
		<span slot="title">取消申请</span>
	</el-menu-item>
	</router-link>
	<router-link to="/home/teachMan">
       <el-menu-item index="7">
		<img src="@/assets/workpart.png" alt=""> 
		<span slot="title">讲师管理</span>
	</el-menu-item>
	</router-link>
	
	<router-link to="/home/content">
	<el-menu-item index="8">
		<img src="@/assets/workpart.png" alt=""> 
		<span slot="title">权限设置</span>
	</el-menu-item>
  </router-link>
  
  
  
</el-menu>
		</div>
</template>
<script>
import Element from 'element-ui';
export default {
     data() {
      return {
        isCollapse: false
      };
    },
    methods: {
      handleOpen(key, keyPath) {
        console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
        console.log(key, keyPath);
      }
    }
}
</script>





<style scoped>
.hello{
    
    
    height: 100%;
    position: fixed;
    z-index: 88;
    padding-top: 60px;
    -webkit-transition: 0.3s ease;
    transition: 0.3s ease
}
.el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 800px;
	padding-top: 20px;
    background: #20222a
  }
.el-menu-item{
  color:#fff;
}
.is-active{
  background: #009688
}
.el-menu-item img{
  margin-right: 5px;
}
.el-menu--collapse {
    width: 64px;
       background: #20222a;
}


  .el-menu-item:hover {
    outline: 0;
   background: #009688
}







.wrap{
	width: 220px;
	background: #20222a;
    height: 800px;
    position: fixed;
    z-index: 88;
	padding-top: 60px;
	transition: 0.3s ease;
}
.wrap .sidebar ul li a{
	color: #fff;
	font-family: MicrosoftYaHei;
	font-size: 16px;
	font-weight: normal;
	font-stretch: normal;
	line-height: 40px;
	letter-spacing: 0px;
	color: #ffffff;
}

.wrap .sidebar ul li a{
	
    display: block;
    position: relative;
    height: 60px;
    line-height: 60px;
    font-size: 16px;
    padding-left: 60px;
    color: #fff;
    transition: 0.3s ease;
  

}
.wrap .sidebar ul li a.i_8 {
    /* background: url(../img/persion.png) 27px center no-repeat; */
   
}
.wrap .sidebar ul li.cur{
	background-color: #009688;
}

</style>