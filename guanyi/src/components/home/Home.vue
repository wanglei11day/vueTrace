<template>
<div>
    <Headder></Headder>
     <Navver></Navver>
     <!-- <Content></Content> -->
       <router-view></router-view>
     
   
</div>
    
</template>
<script>
import Headder from '@/components/header/Header'
import Nav from '@/components/nav/nav'
import Navver from '@/components/nav/Navver'
import Content from '@/components/content/Content'
export default {
    components:{
        Headder,
        Nav,
        Navver,
        Content,
    }
    
}
</script>

<style >
.rrr{
    margin-top: 20px;  
    margin-left: 500px; 
    width: 100%;
}
</style>