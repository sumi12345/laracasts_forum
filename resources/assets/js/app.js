// Vue
window.Vue = require('vue');

// axios
window.axios = require('axios');

window.axios.defaults.headers.common = {
    //'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

// 全局变量
window.events = new Vue();

// 全局方法
window.flash = function (message) {
    window.events.$emit('flash', message);
}

// Vue
const app = new Vue({
    el: '#app',
    components: {
        'flash': require('./components/Flash.vue'),
        'reply': require('./components/Reply.vue')
    }
});