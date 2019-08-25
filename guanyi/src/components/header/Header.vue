<template>
    <div class="headerTop">
			<div class="left">
				<img src="./img/logo.png"  class="logo"/>
				<span class="text">名冠天下排班系统</span>	
			</div>
			<div class="right">
				<a>
					余丹青， 您好
					<img src="./img/txnh.png" />
					<img src="./img/down.png" />
				</a>
				<a>排班提醒
				 <img src="./img/down.png" />
				</a>
				<a>
				 <img src="./img/close.png" />
				</a>
			</div>
		</div>
</template>
<script>
export default {
    
}
</script>
<style scoped>
.headerTop{
	width: 100%;
	height: 60px;
	background-color: #fafafa;
	box-shadow: 0px 0px 5px 0px 
		rgba(0, 0, 0, 0.15), 
		0px 0px 1px 0px 
		#dbdbdb;
		position: fixed;
		z-index: 100;
}
.headerTop .left{
	padding: 8px;
}

.headerTop .left img{
	margin:auto;
	width: 44px;
	height: 44px;
	display:inline-block;
	vertical-align:middle
}

.headerTop .left span{
	text-align: center;
    font-size: 22px;
	font-weight: normal;
	font-stretch: normal;
	letter-spacing: 0px;
	color: #333333;
}
.headerTop .right{
	float: right;
	line-height: 60px;
	margin-right: 29px;
}
.headerTop .right a img{
	vertical-align: middle;
	margin: auto 3px;
	}
.headerTop .right a{
	margin: 0 5px;
}
</style>