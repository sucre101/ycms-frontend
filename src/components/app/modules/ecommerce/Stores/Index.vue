<template>
  <div>

    <preloader :loading="loading" />

    <store-list v-if="!loading && !editStore" />

    <store v-if="editStore"/>

  </div>
</template>

<script>
import List from './List'
import Store from './Store'

export default {
  name: "index",
  title: 'Stores',

  components: {
    'store-list': List,
    'store': Store
  },

  data() {
    return {
      loading: false,
      editStore: false,
    }
  },

  created() {
    this.editStore = this.$route.query.hasOwnProperty('store');
  },

  watch: {
    $route(to, from) {
      this.editStore = to.query.hasOwnProperty('store');

      if (!to.query.hasOwnProperty('store')) {

      }
    },
  },

}
</script>
