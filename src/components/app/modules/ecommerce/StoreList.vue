<template>
  <div>

    <preloader :loading="loading" />

    <store-list v-if="!loading"/>

    <div>

    </div>

  </div>
</template>

<script>
import List from './Stores/List'

export default {
  name: "index",

  components: {
    'store-list': List
  },

  watch: {
    $route(to, from) {
      this.editStore = to.query.hasOwnProperty('store');
    }
  },

  data() {
    return {
      storeList: [],
      loading: true,
      userModuleId: Number(this.$route.params.moduleId),
      currencies: [
        { id: 1, name: 'EUR' },
        { id: 2, name: 'USD' },
        { id: 3, name: 'RUB' },
      ],
    }
  },

}
</script>

<style lang="scss" scoped>

</style>
