<template>
  <div>
    <h1 id="title">{{ title }}</h1>
    <router-view/>
  </div>
</template>

<script>

export default {
  name: "index",

  computed: {

    title() {
      return this.$route.meta.title ? this.$route.meta.title : this.$root.pageTitle
    }

  },

  created() {
    if (this.$route.params.slug !== this.$parent.app.slug) {
      this.$router.push('/apps')
    } else {
      axios.defaults.headers.post.app = this.$parent.app.slug
    }
  }
}
</script>

<style scoped>

</style>
