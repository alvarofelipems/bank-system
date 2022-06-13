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
    </div>

    <div class="row items-center justify-evenly">
      <div class="col">
        <div class="q-pa-md">
          <div class="q-gutter-y-md column">
            <q-input v-model="amount" label="Amount" type="number" hint="*The money will be deposited in your account once the check is accepted">
              <template v-slot:prepend>
                <q-icon name="price_change" />
              </template>
            </q-input>

            <q-input v-model="description" label="Description">
              <template v-slot:prepend>
              </template>
            </q-input>

            <q-btn color="primary" label="Add Purchase" @click="addPurchase" />
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';

import { api } from 'boot/axios';

export default defineComponent({
  name: 'ExpensePage',
  components: {  },
  setup () {
    return {
    };
  },
  data () {
    return {
      transactions: [],
      incomes: null,
      expenses: null,
      amount: null,
      description: null,
    }
  },
  methods: {
    async getTransactions() {
      let response = await api.get('/api/bff/transactions');

      return response;
    },
    addPurchase() {
      api.post('/api/expenses', {
        amount: this.amount,
        description: this.description,
      }).then((response) => {
        this.amount = null
        this.description = null
      })
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
