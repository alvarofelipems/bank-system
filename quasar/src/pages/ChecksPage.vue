<template>
  <q-page>
    <div class="row items-center justify-evenly">
      <div class="col">
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
      </div>
    </div>
  </q-page>

  <q-dialog v-model="showDialog">
    <q-card>
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">{{ selectedTransaction.description }}</div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <q-btn color="primary" label="Approve" @click="approveCheck" />
        <q-btn color="red" label="Disaprove" @click="desapproveCheck" />
        <img :src="selectedTransaction.check" style="width: 100%" />
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import moment from 'moment';

import { api } from 'boot/axios';

export default defineComponent({
  name: 'ChecksPage',
  components: {  },
  setup () {
    return {};
  },
  data () {
    return {
      transactions: [],
      showDialog: false
    }
  },
  methods: {
    async getChecks() {
      let response = await api.get('/api/admin/incomes');

      return response;
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
        this.showDialog = true;
      }
    },
    approveCheck() {
      api.put('/api/admin/incomes/' + this.selectedTransaction.id, {
        status: 'approved'
      })
    },
    desapproveCheck() {
      api.put('/api/admin/incomes/' + this.selectedTransaction.id, {
        status: 'declined'
      })
    }
  },
  computed: {
  },
  async mounted () {
    let data = (await this.getChecks()).data
    this.transactions = data.data
  }
});
</script>
