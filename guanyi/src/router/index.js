import Vue from 'vue'
import Router from 'vue-router'
import Header from '@/components/header/Header'
import Home from '@/components/home/Home'
import Nav from '@/components/nav/Nav'
import Cancel from '@/components/cancel/Cancel'
import Content from '@/components/content/Content'
import TeachMan from '@/components/teacherManage/TeachMan'
import WorkBench from '@/components/workbench/WorkBench'
import TeachList from '@/components/teachlist/TeachList'
import Schedule from '@/components/schedule/Schedule'
import Ranking from '@/components/ranking/Ranking'
import WorkTwo from '@/components/customer/WorkTwo'
Vue.use(Router)

export default new Router({
  mode: 'history',  // 去掉路由地址的#
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      children:[
        {path:'/home/cancel',name:"cancelLink",component:Cancel},//取消申请
        {path:'/home/content',name:"contentLink",component:Content},//权限设置
        {path:'/home/teachMan',name:"teachManageLink",component:TeachMan},//讲师管理
        {path:'/home/workbench',name:"workbenchLink",component:WorkBench},//工作台
        {path:'/home/teachlist',name:"teachlistLink",component:TeachList},//讲师列表
        {path:'/home/schedule',name:"scheduleLink",component:Schedule},//讲师列表
        {path:'/home/ranking',name:"rankingLink",component:Ranking},//排行
        {path:'/home/customer',name:"rankingLink",component:WorkTwo},//排行
        {path:'*',redirect:'/home/content'}
      ],
      
      
    },



  ],

})
