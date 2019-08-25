// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css'
import { Button, Select } from 'element-ui';

Vue.config.productionTip = false
Vue.use(ElementUI);
/* eslint-disable no-new */
Vue.use(ElementUI, { size: 'small', zIndex: 3000 });

Vue.component(Button.name, Button);
Vue.component(Select.name, Select);
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  ElementUI,
  components: { App },
  template: '<App/>'
})
