<template>
  <div>
    User Settings
  </div>
</template>

<script>
export default {
  name: "index",

  mounted() {
    window.setTitle(this.$route.meta.title)
  }
}
</script>

<style scoped>

</style>