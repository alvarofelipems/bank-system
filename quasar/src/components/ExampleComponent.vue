<template>
  <div>
    <q-toolbar-title>
      <q-bar>
        Page Title
      </q-bar>
    </q-toolbar-title>
    <p>{{ title }}</p>
    <ul>
      <li v-for="todo in todos" :key="todo.id" @click="increment">
        {{ todo.id }} - {{ todo.content }}
      </li>
    </ul>
    <p>Count: {{ todoCount }} / {{ meta.totalCount }}</p>
    <p>Active: {{ active ? 'yes' : 'no' }}</p>
    <p>Clicks on todos: {{ clickCount }}</p>
    <p>User: {{ name}} </p>
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

function useClickCount() {
  const clickCount = ref(0);
  function increment() {
    clickCount.value += 1
    return clickCount.value;
  }

  return { clickCount, increment };
}

function useDisplayTodo(todos: Ref<Todo[]>) {
  const todoCount = computed(() => todos.value.length);
  return { todoCount };
}

export default defineComponent({
  name: 'ExampleComponent',
  props: {
    title: {
      type: String,
      required: true
    },
    todos: {
      type: Array as PropType<Todo[]>,
      default: () => []
    },
    meta: {
      type: Object as PropType<Meta>,
      required: true
    },
    active: {
      type: Boolean
    }
  },
  setup (props) {
    return { ...useClickCount(), ...useDisplayTodo(toRef(props, 'todos')) };
  },
  data: () => {
    return {
      'name': user.name
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
