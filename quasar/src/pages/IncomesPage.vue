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

            <q-file filled bottom-slots v-model="check" label="Check" counter>
              <template v-slot:prepend>
                <q-icon name="cloud_upload" @click.stop />
              </template>
              <template v-slot:append>
                <q-icon name="close" @click.stop="check = null" class="cursor-pointer" />
              </template>

              <template v-slot:hint>
                Field hint
              </template>
            </q-file>

            <q-btn color="primary" label="Deposit Check" @click="depositCheck" />
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
  name: 'IncomesPage',
  components: {  },
  setup () {
    return {
      // balance: 0,
    };
  },
  data () {
    return {
      transactions: [],
      incomes: null,
      expenses: null,
      amount: null,
      description: null,
      check: null,
      showCheck: false
    }
  },
  methods: {
    async getTransactions() {
      let response = await api.get('/api/bff/transactions');

      return response;
    },
    depositCheck() {
      api.post('/api/incomes', {
        amount: this.amount,
        description: this.description,
      }).then((response) => {
        let formData = new FormData()
        formData.append('check', this.check)
        api.post(
          '/api/incomes/' + response.data.data.id + '/checks',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
        ).then(response => {
          console.log(response)
        })
        this.check = null
        this.description = null
        this.amount = null
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
