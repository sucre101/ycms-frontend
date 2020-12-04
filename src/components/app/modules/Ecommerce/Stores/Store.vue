<template>
  <div>Edit Store #{{ store }}</div>
</template>

<script>
export default {
  name: "store",

  data() {
    return {

    }
  },

  created() {
    this.$root.$emit('navigator::setBack', true)
    setTimeout(() => {
      this.loadData()
    }, 1000)
  },

  computed: {

    store() {
      return this.$route.query.store
    }

  },


  mounted() {
    this.$parent.loading = true;
  },

  methods: {

    loadData() {
      this.$nextTick(() => {
        this.$parent.loading = false;
      })
    }

  }

}
</script>

<style scoped>

</style>
