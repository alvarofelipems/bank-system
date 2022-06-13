<template>
  <div>
    <q-toolbar-title>
      <q-bar>
        Incomes
      </q-bar>
    </q-toolbar-title>
    <p>{{ title }}</p>
    <ul>
      <li v-for="income in incomes" :key="income.id" @click="increment">
        {{ income.amount }} - {{ income.description }}
      </li>
    </ul>
    <p>User: {{ name }} </p>
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
import { Todo, Meta } from './models';
import { user } from 'boot/keycloak';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';

let incomes = [];
async function getIncomes()  {
  let response = await api.get('/api/incomes');

  // console.log(response.data);
  incomes = response.data.data

  return { incomes };
}

incomes = (await getIncomes()).incomes;


export default defineComponent({
  name: 'IncomesComponent',
  props: {
    title: {
      type: String,
      required: false
    }
  },
  setup (props) {
    return { ...getIncomes() };
  },
  data: () => {
    return {
      'name': user.name,
      'incomes': incomes
    }
  },
  mounted: () => {
    console.log(user)
    // keycloak.loadUserInfo().then(function (user) {
    //
    // })
  }
});
</script>
