<template>
  <div>
    <component :is="editModule"/>
  </div>
</template>

<script>
export default {
  name: "edit",

  computed: {
    editModule() {
      return () => import('./' + this.$route.params.folder + '/Index')
        .then(m => m.default)
    }
   }
}
</script>

<style scoped>

</style>
