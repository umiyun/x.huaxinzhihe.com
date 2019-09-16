import Vue from 'vue'
import App from './App'
import siteinfo from './siteinfo.js'
Vue.prototype.siteinfo=siteinfo

import home from './pages/home/index.vue'
Vue.component('home',home)

import activitys from './pages/activitys/index.vue'
Vue.component('components',activitys)

import user from './pages/user/index.vue'
Vue.component('user',user)

import cuCustom from './colorui/components/cu-custom.vue'
Vue.component('cu-custom',cuCustom)

Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()

 



