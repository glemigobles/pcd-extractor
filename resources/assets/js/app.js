/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
require('./bootstrap');

window.Vue = require('vue');
Vue.use(ElementUI);
Vue.use(Loading.directive);
import {
    Loading
} from 'element-ui';
require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('workers', require('./components/users/workers.vue'));
Vue.component('userstable', require('./components/users/usersTable.vue'));
Vue.component('createuser', require('./components/users/createUser.vue'));
Vue.component('edituser', require('./components/users/editUser.vue'));
Vue.component('editmyaccount', require('./components/home/editMyAccount.vue'));
Vue.component('extractor', require('./components/extractor/extractor.vue'));

window.EventBus = new Vue();

const app = new Vue({
    el: '#app'
});