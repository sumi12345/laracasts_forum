// 定义 Vue
window.Vue = require('vue');

// 事件全局变量
window.events = new Vue();

// flash 全局方法, 发起 flash 事件, 将由 flash 组件捕捉到
window.flash = function (message) {
    window.events.$emit('flash', message);
}

// 注册组件
const app = new Vue({
    el: '#app',
    components: {
        'flash': require('./components/Flash.vue'),
        'reply': require('./components/Reply.vue')
    }
});
