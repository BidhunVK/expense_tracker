import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue/dist/vue.esm-bundler';
import ExpenseForm from './components/ExpenseForm.vue';

const app = createApp({
    // App setup
});

app.component('ExpenseForm', ExpenseForm);

app.mount('#app');