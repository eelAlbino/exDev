import {createApp} from 'vue/dist/vue.esm-bundler.js';
import BalanceInfo from './components/BalanceInfo.vue';

const app = createApp({});
app.component('balance-info', BalanceInfo);
app.mount('#app');