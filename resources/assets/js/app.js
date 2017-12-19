// Lodash
window._ = require('lodash');

// Axios
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.interceptors.response.use(function (response) {
    return response;
  }, function (error) {
    if(error.response.status == 401){
        window.localStorage.removeItem('acccess_token');
        window.access_token=null;
        window.location.href='/#/login';
    }        
    return error;
  });

// Loader
window.loader = {
    show: function(duration){
        if(duration === undefined){
            duration = 400; // Default jQuery speed
        }
        $('#loader').fadeIn(duration);
    },
    hide: function(duration){
        if(duration === undefined){
            duration = 400; // Default jQuery speed
        }
        $('#loader').fadeOut(duration);
    }
};

// CSRF Token
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

//access token
window.access_token= window.localStorage.getItem('access_token');
let userPermissions= window.localStorage.getItem('user_permissions');
window.user_permissions= JSON.parse(userPermissions);

// Translations
import Vuex from 'vuex';
import vuexI18n from 'vuex-i18n';
const store = new Vuex.Store();
Vue.use(vuexI18n.plugin, store);
Vue.i18n.add('en', require('./translations/en.js')());
Vue.i18n.add('nl', require('./translations/nl.js')());
Vue.i18n.set(document.getElementsByTagName('html')[0].getAttribute('lang'));

// Define the page components
const Pages = require('./pages')();

// Define the routes
const routes = require('./routes')(Pages);

// Define the router
const Router = new VueRouter({
    routes: routes
});

var openRoutes=[
    '/login',
    '/recover-password',
    '/recover-password-confirmation',
    '/reset-password/.+',
];
Router.beforeEach((to, from, next) => {  
    if(to.path == '/logout'){        
        window.localStorage.removeItem("access_token");
        window.localStorage.removeItem("user_permissions");
        window.access_token= null;
        window.user_permissions= null;
        return next('/login');
    }

    let isOpenRoute= false;
    for(let i=0; i < openRoutes.length; i++){
        if(to.path.match(openRoutes[i])){
            isOpenRoute=true;
            break;
        }
    }

    if(!isOpenRoute && (window.access_token === null || window.access_token === undefined)){
        return next('/login');
    }

    window.axios.defaults.headers.common['Authorization'] = "Bearer "+ window.access_token;
    window.axios.defaults.headers.common['Accept'] = "application/json";

    return next();
    
});

// Define the global components
Vue.component('hs-side-nav', require('./components/ui/HsSideNav/template.vue'));
Vue.component('hs-header', require('./components/ui/HsHeader/template.vue'));

// Create the root instance
window.app = new Vue({
    router: Router,    
    watch: {

        // When the route changes
        '$route': function(){

            // Show the loader
            window.loader.show();

            // Close the side nav
            $(document).ready(function(){
                $('.button-collapse').sideNav('hide');
            });
        }

    }
}).$mount('#app');