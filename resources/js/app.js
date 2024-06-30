import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue/dist/vue.esm-bundler';
import ExpenseForm from './components/ExpenseForm.vue';
import ExpenseList from './components/ExpenseList.vue';

const app = createApp();

app.component('ExpenseForm', ExpenseForm);
app.component('ExpenseList', ExpenseList);

app.mount('#app');