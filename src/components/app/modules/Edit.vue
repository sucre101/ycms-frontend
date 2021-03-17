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
      return () => import('./' + this.$route.params.folder.toLocaleLowerCase() + '/Index')
        .then(m => m.default)
    },

    moduleUrl() {
      return `/${this.$route.params.folder.toLowerCase()}/${this.$route.params.moduleId}`
    }

  },
}
</script>

<style scoped>

</style>
