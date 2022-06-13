<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          BNB Bank
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label
          header
        >
          BNB Bank
        </q-item-label>

        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import EssentialLink from 'components/EssentialLink.vue';

const linksList = [
  {
    title: 'Balance',
    icon: 'balance',
    link: '#/'
  },
  {
    title: 'Income',
    icon: 'trending_up',
    link: '#/incomes'
  },
  {
    title: 'Expenses',
    icon: 'trending_down',
    link: '#/expenses'
  },
  {
    title: 'Checks',
    icon: 'price_change',
    link: '/checks'
  },
  {
    title: 'Notification',
    icon: 'notifications',
    link: '/notifications'
  },
  {
    title: 'Profile',
    icon: 'person',
    link: '/profile'
  },
  {
    title: 'Settings',
    icon: 'settings',
    link: 'https://awesome.quasar.dev'
  },
  {
    title: 'Help',
    icon: 'help',
    link: 'https://awesome.quasar.dev'
  },
  {
    title: 'Approve checks',
    icon: 'help',
    link: '#/admin/checks'
  }
];

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  setup () {
    const leftDrawerOpen = ref(false)

    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
});
</script>
