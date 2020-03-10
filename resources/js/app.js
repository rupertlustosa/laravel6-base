/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import TDate from './boot-vue-functions'
import store from "./store";
import VueLoading from 'vuejs-loading-plugin';
import VueAWN from "vue-awesome-notifications";

window.Vue = require('vue');
Vue.use(VueLoading, {
    dark: false, // default false
    text: 'Carregando...', // default 'Loading'
    loading: false, // default false
    //customLoader: myVueComponent, // replaces the spinner and text with your own
    //background: 'rgb(47, 64, 80)', // set custom background
    classes: ['loading-screen-inspinia', 'animated', 'fadeIn'] // array, object or string
});

let optionsVueAWN = {
    position: "top-right",
    clean: true,
    labels: {
        tip: '',
        info: '',
        success: '',
        warning: '',
        alert: '',
        async: 'Loading',
        confirm: 'Confirmation required',
    },
    icons: {
        enabled: false,
    }
    //durations: {success: 0}
};

Vue.use(VueAWN, optionsVueAWN);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('link-destroy-component', require('./components/LinkDestroyComponent').default);

Vue.component(
    'nozzle-list-component',
    require('./components/sales/NozzleListComponent').default
);

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
    store,
    el: '#app',
    methods: {
        dateFormatBR: TDate.dateBR,
        dateFormatUS: TDate.dateUS,
    }
});

$('#app').tooltip({
    selector: "[data-toggle=tooltip]",
    container: "body"
});
