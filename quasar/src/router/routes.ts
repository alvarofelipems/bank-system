import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    // path: '/:catchAll(.*)*',
    // path: '/:pathMatch(.*)*',
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/IndexPage.vue') }],
  },
  {
    path: '/incomes',
    query: {},
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/IncomesPage.vue') }],
  },
  {
    path: '/expenses',
    query: {},
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/ExpensePage.vue') }],
  },
  {
    path: '/admin/checks',
    query: {},
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/ChecksPage.vue') }],
  },

  // Always leave this as last one,
  // but you can also remove it
  // {
  //   path: '/:catchAll(.*)*',
  //   component: () => import('pages/ErrorNotFound.vue'),
  // },
];

export default routes;
