<template>
  <div>
    <router-view/>
  </div>
</template>

<script>

export default {
  name: "index",

  created() {
    // if (this.$route.params.slug !== this.$parent.app.slug) {
    //   this.$router.push('/apps')
    // }
  }
}
</script>

<style scoped>

</style>
