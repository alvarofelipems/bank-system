<template>
  <div>
    <q-toolbar-title>
      <q-bar @click="getTransactions()">
        Transactions
      </q-bar>
    </q-toolbar-title>
    <q-list dense bordered padding class="rounded-borders">
      <q-item clickable v-ripple v-for="transaction in transactions" :key="transaction.id" @click="showDialogCheck(transaction)">
        <q-item-section>
          {{ transaction.description }}
        </q-item-section>
        <q-item-section>
          {{ dateTime(transaction.created_at) }}
        </q-item-section>
        <q-item-section :style="'color: ' + amountColor(transaction) ">
          ${{ money(transaction.amount) }}
        </q-item-section>
      </q-item>
    </q-list>
    <p>User: {{ name }} </p>

    <q-dialog v-model="showCheck">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">{{ selectedTransaction.description }}</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <img :src="selectedTransaction.check" style="width: 100%" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script lang="ts">
import {
  defineComponent,
  PropType,
  computed,
  ref,
  toRef,
  Ref,
} from 'vue';
import { user } from 'boot/keycloak';
import { useQuasar } from 'quasar';
import moment from 'moment';


import { api } from 'boot/axios';

export default defineComponent({
  name: 'TransactionsComponent',
  props: {
    transactions: {
      required: false
    }
  },
  setup (props) {
    return { };
  },
  data: () => {
    return {
      showCheck: false,
      selectedTransaction: null,
      name: user.name,
      // transactionss: this.$props.transactions
    }
  },
  methods: {
    async getTransactions() {
      console.log('getTransactions');
      api.get('/api/bff/transactions').then((response) => {
        // this.transactions = response.data.incomes.data
        // temp1.data.concat(temp2.data)
        return 1;
      });
    },
    money(amount) {
      return amount.toFixed(2)
    },
    dateTime(datetime) {
      return moment(datetime).format('LLL');
    },
    amountColor(transaction) {
      if (transaction.type == 'expense') {
        return 'red'
      }

      if (transaction.status == 'approved') {
        return 'green'
      }

      return 'blue'
    },
    showDialogCheck(transaction) {
      this.selectedTransaction = transaction;
      if (transaction.check) {
        this.showCheck = true;
      }
    }
  },
  async mounted () {
    // console.log(user)
    // this.transactions = await (this.getTransactions()).data.incomes.data
    // transactions.then((response) => {
    //   console.log(response)
    // });
  }
});

</script>
