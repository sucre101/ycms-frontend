<template>
  <div>
    <div v-if="settings">
      Settings for - {{ settings.user_module_id }}
      Structure - {{ settings.store_structure }}
    </div>
  </div>
</template>

<script>
export default {
  name: "settings",

  data() {
    return {
      settings: {},
      module: {}
    }
  },

  created() {
    this.module.id = this.$parent.moduleId
    this.loadModule()
  },

  methods: {

    loadModule() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/settings`)
        .then((res) => {
          this.settings = this._.cloneDeep(res.data.settings)
          console.log(this.settings)
        })

    }

  }
}
</script>

<style scoped>

</style>
