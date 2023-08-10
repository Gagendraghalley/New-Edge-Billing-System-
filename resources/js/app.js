/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./common');
window.Vue = require('vue');
import moment from 'moment';
window.Fire = new Vue();
import { Form, HasError, AlertError } from 'vform';
window.form = Form;


import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

import Swal from 'sweetalert2';


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
window.Swal = Swal;
window.Toast = Toast;
window.Fire = new Vue();

import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
  });
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


/**
 * Routes imports and assigning
 */
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import task_route from './routes';

var allRoutes = [];
allRoutes = allRoutes.concat(task_route);
const routes = allRoutes;
const router = new VueRouter({
    routes
});
// Components
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('Userprofile', require('./components/Profile/Userprofile.vue'));


// Filter Section

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('MM/DD/YYYY')
    }
});

Vue.filter('myDate',function(created){
    return moment(created).format('MMMM DD YYYY');
});

Vue.filter('yesno', value => (value ? '<i class="fas fa-check green"></i>' : '<i class="fas fa-times red"></i>'));

// end Filter

const app = new Vue({
    el: '#app',
    router
});

