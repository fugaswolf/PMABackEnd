/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import { Form, HasError, AlertError } from 'vform';

import DatePicker from 'vue2-datepicker';
window.DatePicker = DatePicker;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// ES6 Modules or TypeScript
import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})
window.toast = toast;

Vue.component('pagination', require('laravel-vue-pagination'));


window.Form = Form;
import VueRouter from 'vue-router'
import { createDecipher } from 'crypto';
Vue.use(VueRouter)

const routes = [{
        path: '/dashboard',
        component: require('./components/Dashboard.vue').default
    },
    {
        path: '/profile',
        component: require('./components/Profile.vue').default
    },
    {
        path: '/timer',
        component: require('./components/Timer.vue').default
    },
    {
        path: '/reports',
        component: require('./components/Reports.vue').default
    },
    {
        path: '/projects',
        component: require('./components/Projects.vue').default
    },
    {
        path: '/tasks',
        component: require('./components/Tasks.vue').default
    },
    {
        path: '/customers',
        component: require('./components/Customers.vue').default
    },
    {
        path: '/users',
        component: require('./components/Users.vue').default
    },
    {
        path: '/tokenmanager',
        component: require('./components/TokenManager.vue').default
    }
]


import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
  })


const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})

Vue.filter('myDate', function(created){
    return moment(created).format('MMMM Do YYYY, H:mm:ss');
});

window.Fire = new Vue();





/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
