<template>
  <div>

      <Page/>

  </div>
</template>

<script>
import Page from "./Pages/Page"

export default {
  name: "index",

  components: {
    Page
  },

  data() {
    return {
      moduleId: null
    }
  },

  created() {

    this.moduleId = Number(this.$route.params.moduleId)

  },

  methods: {
  }

}
</script>

<style scoped>

</style>
