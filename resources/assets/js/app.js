// Vue
window.Vue = require('vue');

// axios
window.axios = require('axios');

window.axios.defaults.headers.post = {
    'Accept': 'application/json',
    'X-CSRF-TOKEN': window.App.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

// 全局变量
window.events = new Vue();

window.Vue.prototype.authorize = function (handler) {
    let user = window.App.user;

    return user ? handler(user) : false;
}

// 全局方法
window.flash = function (message) {
    window.events.$emit('flash', message);
}

// Vue
const app = new Vue({
    el: '#app',
    components: {
        'flash': require('./components/Flash.vue'),
        'thread-view': require('./components/ThreadView.vue')
    }
});