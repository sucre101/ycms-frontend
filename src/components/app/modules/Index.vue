<template>
  <div>
    <router-view/>
  </div>
</template>

<script>

export default {
  name: "index",

  created() {
    // if (this.$route.params.slug !== this.$root.$children[0].app.slug) {
    //   this.$router.push('/apps')
    // }
  }
}
</script>
