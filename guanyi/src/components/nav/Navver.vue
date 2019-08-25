<template>
<div class="wrap">


  	<section class="sidebar">
				<ul>
         <li v-for="(item,index) in listData" :key="index"  :class="curIndex==index?'current' : 'classB'" @click="goBranch(index)">
          	<router-link   :to="item.url">
							<span>{{item.name}}</span>
           </router-link>
           
         </li>
			
				</ul>
			</section>
</div>
</template>

<script>
export default {
  data(){
    return{
    
    curIndex:0,
    list:["工作台","权限设置","排班表","讲师列表","排行榜","客户管理","讲师管理","权限设置"],
    listData:[
      {name:"工作台",url:"/home/workbench"},
      {name:"权限设置",url:"/home/schedule"},
      {name:"排班表",url:"/home/teachlist"},
      {name:"讲师列表",url:"/home/ranking"},
      {name:"排行榜",url:"/home/customer"},
      {name:"客户管理",url:"/home/cancel"},
      {name:"讲师管理",url:"/home/teachMan"},
      {name:"权限设置",url:"/home/content"},
    ]
    }
  },
  mounted(){

  },
  methods:{
    goBranch(index){
      console.log(index)
      this.curIndex=index
    }
  }
  }
</script>

<style  scoped>

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
.current{
  	background-color: #009688;
}
</style>