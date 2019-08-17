
require('./bootstrap');
require('admin-lte');
window.Vue = require('vue');
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import { Form, HasError, AlertError } from 'vform'
import Snotify, { SnotifyPosition } from 'vue-snotify'
import moment from 'moment';
import Profile from "./Profile";
import XLSX from 'xlsx';

Vue.prototype.$profile= new Profile(window.user);

window.Form = Form;
window.XLSX = XLSX;
const SnotifyOptions = {
  toast: {
    position: SnotifyPosition.rightTop
  }
}

Vue.use(Snotify, SnotifyOptions)
Vue.use(VueRouter)
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '4px'
})
Vue.filter('mydate',function(createAt){
  return moment(createAt).format('MMMM Do YYYY, h:mm');
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('customer-component', require('./components/CustomerComponent.vue').default);
// Vue.component('category-component', require('./components/CategoryComponent.vue').default);
Vue.component('pagination-component', require('./components/partial/PaginationComponent.vue').default);
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


let routes = [
    { path: '/customer', component: require('./components/CustomerComponent.vue').default },
    { path: '/category', component: require('./components/CategoryComponent.vue').default },
    { path: '/product', component: require('./components/ProductComponent.vue').default },
    { path: '/purchase', component: require('./components/PurchaseComponent.vue').default },
    { path: '/supplier', component: require('./components/SupplierComponent.vue').default },
    { path: '/sales', component: require('./components/SalesComponent.vue').default },
    { path: '/dashboard', component: require('./components/DashboardComponent.vue').default },
    { path: '/user', component: require('./components/UserComponent.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  });

const app = new Vue({
    el: '#app',
    router
});
