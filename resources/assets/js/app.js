window.Vue = require('vue');

// 事件全局变量
window.events = new Vue();

// flash 组件 通知
Vue.component('flash', require('./components/Flash.vue'));

// flash 全局方法, 发起 flash 事件, 将由 flash 组件捕捉到
window.flash = function (message) {
    window.events.$emit('flash', message);
}

const app = new Vue({
    el: '#app'
});