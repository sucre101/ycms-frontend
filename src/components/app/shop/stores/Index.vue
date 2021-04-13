<template>
  <div>
    {{ store.name }}
  </div>
</template>

<script>

export default {
  name: "index",

  components: {},

  data() {
    return {
      store: {},
    }
  },

  created() {
    let index = this._.findIndex(this.$root.$children[0].app.stores, {id: Number(this.$route.params.store)})

    if (index >= 0) {
      this.store = this._.cloneDeep(this.$root.$children[0].app.stores[index])
    } else {
      this.$router.push({name: 'shop-list', params: {slug: this.$root.$children[0].app.slug}})
    }
  },

  methods: {}

}
</script>

<style scoped lang="scss">

</style>
