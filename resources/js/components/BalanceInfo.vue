<template>
  <div>
    <h2>Текущий баланс: {{ balance.balance }}</h2>
    <h3>Последние 10 транзакций:</h3>
    <ul class="balance-transaction-list">
      <li v-for="transaction in transactions" :key="transaction.id" :class="{ 'text-danger': transaction.type === 'credit', 'text-success': transaction.type === 'debit' }">
			{{ formatDate(transaction.created_at) }}, {{transaction.type === 'credit' ? '-' : '+'}}{{ transaction.amount }} ({{ transaction.description }})
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      balance: {
      	balance: 0
      },
      transactions: [],
    };
  },
  mounted() {
    this.loadData();
    // Обновление данных каждые 5 секунд
    setInterval(this.loadData, 5000);
  },
  methods: {
    loadData() {
      axios.get('/user/balance/current').then(response => {
      	if (response.data.success) {
      		this.balance = response.data.payload.balance;
      		this.transactions = response.data.payload.transactions;
      	}
      });
    },
    formatDate(date) {
      const options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
      return new Date(date).toLocaleDateString(undefined, options);
    }
  }
};
</script>