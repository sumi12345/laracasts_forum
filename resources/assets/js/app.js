// axios
window.axios = require('axios');

window.axios.defaults.headers.post = {
    'Accept': 'application/json',
    'X-CSRF-TOKEN': window.App.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

// Vue
window.Vue = require('vue');

// Vue events
window.events = new Vue();

// Vue signedIn
window.Vue.prototype.signedIn = window.App.signedIn;

// Vue authorizations
let authorizations = require('./authorizations.js');

window.Vue.prototype.authorize = function (ability, obj) {
    if (! window.App.signedIn) return false;
    return authorizations[ability](obj);
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
        'thread-view': require('./components/ThreadView.vue'),
        'notifications': require('./components/Notifications.vue'),
        'avatar': require('./components/Avatar.vue'),
    }
});