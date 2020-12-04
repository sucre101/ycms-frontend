<template>
  <div>
    Settings for - {{ module.id }}
  </div>
</template>

<script>
export default {
  name: "settings",

  data() {
    return {
      module: {}
    }
  },

  created() {
    this.module.id = this.$parent.moduleId
    this.loadModule()
  },

  methods: {

    loadModule() {

      axios.post('/' + this.$route.params.folder.toLowerCase() + '/module-settings', { id: this.module.id })
        .then((res) => {
          console.log(res)
        })

    }

  }
}
</script>

<style scoped>

</style>
