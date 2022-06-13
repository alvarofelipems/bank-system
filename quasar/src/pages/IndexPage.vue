<template>
  <q-page>
    <div class="bg-grey-2 q-pa-md row">
      <div class="col">
        <div class="row items-center">
          Current Balance
        </div>
        <div class="row items-center">
          <span class="text-h4">${{ balance }}</span>
        </div>
      </div>

      <div class="col" style="max-width: 300px">
        <q-input filled v-model="date" mask="date" :rules="['date']">
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                <q-date v-model="date">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </div>
    </div>

    <div class="bg-grey-2 q-pa-md row">
      <div class="col">
        <div class="row items-center">
          Incomes
        </div>
        <div class="row items-center">
          <div class="col items-center">
            <span class="text-h4">${{ totalIncomes }}</span>
          </div>
          <div class="col items-center">
            <span class="text-h6">Add Income</span>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-grey-2 q-pa-md row">
      <div class="col">
        <div class="row items-center">
          Expenses
        </div>
        <div class="row items-center">
          <div class="col items-center">
            <span class="text-h4">${{ totalExpenses }}</span>
          </div>
          <div class="col items-center">
            <span class="text-h6">Add Expanses</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row items-center justify-evenly">
      <div class="col">
        <transactions-component :transactions="transactions"></transactions-component>
      </div>
    </div>
  </q-page>
</template>

<script lang="ts">
import TransactionsComponent from 'components/TransactionsComponent.vue';
import { defineComponent, ref } from 'vue';

import { api } from 'boot/axios';

export default defineComponent({
  name: 'IndexPage',
  components: { TransactionsComponent },
  setup () {
    return {
      // balance: 0,
      date: ref('2019/02/01')
    };
  },
  data () {
    return {
      transactions: [],
      incomes: null,
      expenses: null
    }
  },
  methods: {
    async getTransactions() {
      let response = await api.get('/api/bff/transactions');

      return response;
    }
  },
  computed: {
    balance() {
      let incomesSum = this.incomes ? this.incomes.meta.sum : 0
      let expensesSum = this.incomes ? this.expenses.meta.sum : 0

      return incomesSum - expensesSum;
    },
    totalIncomes() {
      let incomesSum = this.incomes ? this.incomes.meta.sum : 0
      return incomesSum
    },
    totalExpenses() {
      let expensesSum = this.incomes ? this.expenses.meta.sum : 0
      return expensesSum
    },
  },
  async mounted () {
    let data = (await this.getTransactions()).data
    this.incomes = data.incomes
    this.expenses = data.expenses

    this.incomes.data.forEach((income) => {
      income.type = 'income'
    })

    this.expenses.data.forEach((expense) => {
      expense.type = 'expense'
    })

    this.transactions = this.incomes.data.concat(this.expenses.data)
    this.transactions = this.transactions.sort(function(a, b) {
      return a.created_at < b.created_at ? 1 : -1;
    });
  }
});
</script>
