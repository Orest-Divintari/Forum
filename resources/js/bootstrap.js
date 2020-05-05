import Vue from "vue";
import PortalVue from "portal-vue";

window._ = require("lodash");

//Portal

Vue.use(PortalVue);
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require("popper.js").default;
    window.$ = window.jQuery = require("jquery");

    require("bootstrap");
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");
// the authorize method is available to all Vue instances
// it can be called using this.authorize()

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
var moment = require("moment");
window.moment = moment;
window.events = new Vue();
window.Vue = Vue;

window.Vue.prototype.authorize = function(handler) {
    let user = window.App.user;
    // console.log(window.App.user);
    // return user ? handler(user) : false;
    //we can add additional admin priviliges
    // return handler(window.App.user);
    return user ? handler(user) : false;
};
window.Vue.prototype.signedIn = window.App.signedIn;
window.flash = function(message, level = "success") {
    window.events.$emit("flash", { message: message, level: level });
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
