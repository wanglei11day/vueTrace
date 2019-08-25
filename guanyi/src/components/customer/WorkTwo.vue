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
			
		<!-- 数据列表第一拍 -->
			<div class="account">
				<div class=" perStatic ">
					<!-- 个人统计 -->
					<ul class="perTitle">
						<li>个人统计</li>
						<li><input type="text" name="" id="" value="按日统计" /></li>
					</ul>
					<div class="today">今日</div>
					<ul class="descNum">
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
					</ul>
					<div class="today">昨日</div>
					<ul class="descNum">
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							<p>33</p>
						</li>
					</ul>
				</div>
				<!-- 整体统计 -->
				<div class="numStatic ">
					<ul class="perTitle">
						<li>整体统计</li>
						<li><input type="text" name="" id="" value="按周统计" /></li>
					</ul>
					<div class="today">本周整体</div>
					<ul class="descNum">
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
						</li>
					</ul>
					<div class="today">本周整体</div>
					<ul class="descNum">
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
							
						</li>
						<li>
							<p>11</p>
							<p>22</p>
						</li>
					</ul>
				</div>
			</div>
			<!-- 第二排日历和erhart -->
			<div class="second">
				<div class="mouth">
					<ul class="mouthTitle">
						<li>我的排班</li>
						<li>benue</li>
					</ul>
                    <Calender  ></Calender>
					<!-- <div id="calendar"></div> -->
				</div>
				<div class="echart">
					<div class="peixunEchart">
						<ul class="mouthTitle">
							<li>培训情况统计 </li>
							<li>benue</li>
						</ul>
						<!-- erchart -->
						<div id="trainerDesc"></div>
						<div class="warning">
							<ul class="mouthTitle">
								<li>提醒</li>
								<li>benue</li>
							</ul>
							
							<ul class="company">
								<li>企业名称</li>
								<li>地点</li>
								<li>培训开始时间</li>
								<li>培训结束时间</li>
								<li>操作</li>
							</ul>
							<ul class="comDesc">
								<li>成都冠一科技有限公司</li>
								<li>四川省成都市成华区....</li>
								<li>2019-08-14  9:00</li>
								<li>2019-08-14  9:00</li>
								<li> <button>确定</button> <button>取消</button> </li>
							</ul>
						</div>
							
						
					</div>
				</div>
			</div>
		</div>
</template>
<script>
import $ from 'jquery'
import Calender from '@/components/calender/Calender'



var echarts = require('echarts')



export default {
    components:{
      Calender
    },
     mounted(){
        //  日历
       

            

        //  erchart
         var trainerDesc = echarts.init(document.getElementById('trainerDesc'));
            trainerDesc.setOption({
            backgroundColor: '#f6f6f6',
            title: {
                
                subtext: '排名数量'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['百度', '360', '搜狗']
            },
            toolbox: {
                show: true,
                orient: 'vertical',
                feature: {
                    mark: {
                        show: true
                    },
                    show: true,
                    magicType: {
                        show: true,
                        type: ['line', 'bar'],
                        title: {
                            line: "折线",
                            bar: "柱形"
                        }
                    },
                    restore: {
                        show: true,
                        title: "还原"
                    },
                    saveAsImage: {
                        show: true,
                        title: "导出"
                    }
                }
            },
            calculable: true,
            xAxis: [{
                type: 'category',
                data: ['首页', '第二页', '第三页']
            }],
            yAxis: [{
                type: 'value'
            }],
            series: [{
                name: '百度',
                type: 'bar',
                itemStyle: {

                    normal: {
                        color: '#ff7f50',
                        label: {
                            show: true,
                            position: 'top',
                            textStyle: {
                                fontSize: 18
                            }
                        }
                    }
                },
                data: [1, 2, 3]
            }, {
                name: '360',
                type: 'bar',
                itemStyle: {
                    normal: {
                        color: '#87cefa',
                        label: {
                            show: true,
                            position: 'top',
                            textStyle: {
                                fontSize: 18
                            }
                        }
                    }
                },
                data: [4, 5, 6]
            }, {
                name: '搜狗',
                type: 'bar',
                itemStyle: {
                    normal: {
                        color: '#da70d6',
                        label: {
                            show: true,
                            position: 'top',
                            textStyle: {
                                fontSize: 18
                            }
                        }
                    }
                },
                data: [7, 8, 9]
            }]
        })
     }
}
// 日历

 //日历
  var data = [
            { startDate: "2018-6-10", name: "事件1" },
            { startDate: "2018-7-10", name: "事件1" },
            { startDate: "2018-8-10", name: "事件1" },
            { startDate: "2018-9-10", name: "事件1" },
            { startDate: "2018-10-10", name: "事件1" },
            { startDate: "2018-11-1", name: "事件2" },
            { startDate: "2018-11-1", name: "事件11" },
            { startDate: "2018-12-1", name: "事件12" },
            { startDate: "2018-12-1", name: "事件13" },
            { startDate: "2018-12-1", name: "事14" },
            { startDate: "2019-1-10", name: "事件14" },
            { startDate: "2019-2-10", name: "事件14" },
            { startDate: "2019-3-10", name: "事件14" },
            { startDate: "2019-4-10", name: "事件14" },
            { startDate: "2019-5-10", name: "事件14" },
            { startDate: "2019-6-10", name: "事件14" },
            { startDate: "2019-7-10", name: "事件14" },
            { startDate: "2019-8-10", name: "事件14" },
            { startDate: "2019-9-10", name: "事件14" },
            { startDate: "2019-10-10", name: "事件14" },
            { startDate: "2019-11-10", name: "事件14" },
            { startDate: "2019-12-10", name: "事件14" },
            { startDate: "2020-1-10", name: "事件14" },
            { startDate: "2020-2-10", name: "事件14" },
        ]

        // $("#calendar").calendar({
        //     data: data,
        //     mode: "month",

        //     cellClick: function (events) {

        //     },
        // })



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



/*统计表*/
.account{
	width: 97%;
	height: 284px;
	margin: 20px auto;
	display: flex;
	
}
.account .perStatic{
	flex: 1;
	margin-right: 20px;
	background: #FFFFFF;
}
.account .numStatic{
	flex: 1;
	background: #fff;
}

.account .perStatic .perTitle{
	width: 100%;
	height: 38px;
	border-bottom: #dbdbdb solid 1px;
	display: flex;
	justify-content: space-between;
	box-sizing: border-box;
}
.account .perStatic .perTitle li{
	line-height: 38px;
	box-sizing: border-box;
	margin-left: 20px;
	margin-right: 20px;
	
}
.account .perStatic .perTitle li input{
	color: #999;
	padding-right: 10px;
	box-sizing: border-box;
	
}
.account .perStatic .today{
	margin-left: 20px;
	margin-top: 10px;
}
.account .perStatic .descNum{
	width: 97%;
	margin: auto;
	display: flex;
	margin-top: 18px;
	padding-bottom: 10px;
	border-bottom: 1px solid #e5e5e5;
}
.account .perStatic .descNum li{
	flex: 1;
	border-right: 1px solid #e5e5e5;
	text-align: center;
}
.account .perStatic .descNum li:last-child{
	border-right: none;
}
.account .perStatic .descNum li p:nth-child(2n+1){
	color: #009688;
}
/* <!-- 整体统计 --> */


.account .numStatic{
	flex: 1;
	margin-right: 20px;
	background: #FFFFFF;
}
.account .numStatic{
	flex: 1;
	background: #fff;
}

.account .numStatic .perTitle{
	width: 100%;
	height: 38px;
	border-bottom: #dbdbdb solid 1px;
	display: flex;
	justify-content: space-between;
	box-sizing: border-box;
}
.account .numStatic .perTitle li{
	line-height: 38px;
	box-sizing: border-box;
	margin-left: 20px;
	margin-right: 20px;
	
}
.account .numStatic .perTitle li input{
	color: #999;
	padding-right: 10px;
	box-sizing: border-box;
	
}
.account .numStatic .today{
	margin-left: 20px;
	margin-top: 10px;
}
.account .numStatic .descNum{
	width: 97%;
	margin: auto;
	display: flex;
	margin-top: 18px;
	padding-bottom: 10px;
	border-bottom: 1px solid #e5e5e5;
}
.account .numStatic .descNum li{
	flex: 1;
	border-right: 1px solid #e5e5e5;
	text-align: center;
}
.account .numStatic .descNum li:last-child{
	border-right: none;
}
.account .numStatic .descNum li p:nth-child(2n){
	color: #009688;
}
/* <!-- 第二排日历和erhart --> */
.second{
	width: 97%;
	margin: 20px auto;
	display: flex;
}

.second .mouth{
	flex: 1;
	height: 625px;
	background: #fff;
	margin-right: 20px;
}
.second .echart{
	flex: 1;
	background: #fff;
}
/* 日历插件 */

#calendar{
	width: 100%;
	height: auto;
}
.mouthTitle{
	width: 100%;
    height: 38px;
    border-bottom: #dbdbdb solid 1px;
    display: flex;
    justify-content: space-between;
    box-sizing: border-box;
}
.mouthTitle li{
   line-height: 38px;
    box-sizing: border-box;
    margin-left: 20px;
    margin-right: 20px;
}
/* peixun */
.peixunEchart{
	width: 100%;
	height: 342px;
}
#trainerDesc{
	background: #fff;
	width: 100%;
	height: 240px;
}
/* 提醒 */
.warning{
	width: 100%;
	height: 215px;
	margin: 20px auto;
}
.company{
	width: 97%;
	margin: 15px auto;
	display: flex;
	height: 34px;
	background: #F5F5F5;
	padding-top: 5px;
	padding-bottom: 5px;
}
.company li{
	flex: 1;
	border-right: 1px solid #e5e5e5;
	line-height: 37px;
	text-align: center;
}
.comDesc{
	width: 97%;
	margin: 15px auto;
	display: flex;
	height: 44px;
	background: #fff;
	
}
.comDesc li{
	flex: 1;
	overflow: hidden;
	line-height: 37px;
	text-align: center;
}
.comDesc li button{
	background-color: #ffffff;
	border: solid 1px #7f7f7f;
}
.comDesc li button:first-child{
	border: solid 1px #009688;
	background: #fff;
}

</style>