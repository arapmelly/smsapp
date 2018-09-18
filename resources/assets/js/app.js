
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import OfficeUIFabricVue from 'office-ui-fabric-vue';

import axios from 'axios';

// import css style
import 'office-ui-fabric-vue/dist/index.css';

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.min.css';

Vue.use(OfficeUIFabricVue);

Vue.use(axios);


Vue.component('vue-ctk-date-time-picker', VueCtkDateTimePicker);
Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('contact-component', require('./components/ContactComponent.vue'));
Vue.component('text-component', require('./components/TextComponent.vue'));
Vue.component('group-component', require('./components/GroupComponent.vue'));
Vue.component('outbox-component', require('./components/OutboxComponent.vue'));
Vue.component('bulk-component', require('./components/bulk-component.vue'));
Vue.component('main-component', require('./components/MainComponent.vue'));

const app = new Vue({
    el: '#app'
});
