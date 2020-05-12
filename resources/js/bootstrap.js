import Vue from "vue";
import PortalVue from "portal-vue";
import { updateLocale } from "moment";

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

// date-time module
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
var moment = require("moment");
window.moment = moment;

// event bus
window.events = new Vue();
window.Vue = Vue;

// global authorization
import authorization from "./authorization";
window.Vue.prototype.authorize = function(policy, model, poutses, ble) {
    let user = window.App.user;
    if (!window.App.signedIn) return false;
    if (typeof policy == "string") {
        return authorization[policy](model);
    }
};

// authentication
window.Vue.prototype.signedIn = window.App.signedIn;

// global flash message function
window.flash = function(message, level = "success") {
    window.events.$emit("flash", { message: message, level: level });
};
