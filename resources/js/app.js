require('./bootstrap');

import Vue from 'vue';
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'
import store from './store';
import Vuelidate from 'vuelidate';
import DadataSuggestions from 'vue-dadata-suggestions'

Vue.use(DadataSuggestions,{
    token: '378e4547c4b237b1cab568fb5c78be39590eb1cd',
    type: 'NAME'
});

Vue.use(Buefy, {
    defaultIconPack: 'fa'
});
Vue.use(Vuelidate);

Vue.component('dashboard-component', require('./components/dashboard/DashboardComponent.vue').default);
Vue.component('header-component', require('./components/parts/Header.vue').default);

// Auth Components
Vue.component('login-component', require('./components/auth/Login.vue').default);

// Users
Vue.component('users-list', require('./components/users/UsersList.vue').default);

// Individuals
Vue.component('individual-list', require('./components/individual/IndividualList.vue').default);
Vue.component('individual-page', require('./components/individual/IndividualPage.vue').default);


Vue.component('user-page', require('./components/users/UserPage.vue').default);

// Editor
Vue.component('editor-component', require('./components/editor/EditorComponent.vue').default);

// Documents
Vue.component('documents-page', require('./components/documents/DocumentsPage.vue').default);

// Events
Vue.component('toast-notifications', require('./components/events/Toast.vue').default);

// Modal
Vue.component('modal', require('./components/modal/Modal.vue').default);

//
Vue.component('tasks', require('./components/tasks/TasksComponent.vue').default);

const app = new Vue({
    el: '#app',
    store
});
