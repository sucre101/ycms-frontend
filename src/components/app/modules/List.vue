<template>
  <div class="box-list installed-modules">
    <div class="top-header">
      <h4>Installed modules</h4>
    </div>
    <div class="main-block list">
      <div class="module" v-for="module in modules">
        <div @click="$router.push({
            name: 'module-edit',
              params: {
                moduleId: module.id,
                folder: module.module.front_folder.toLowerCase()
              }
          })">
          {{ module.alias || module.module.name }}
        </div>
      </div>
    </div>
    <div class="bottom-footer">

    </div>
  </div>
</template>

<script>

export default {

  data() {
    return {
      modules: []
    }
  },

  created() {
    this._loadModules()
  },

  mounted() {
    window.setTitle(this.$route.meta.title)
  },

  methods: {

    _loadModules() {

      axios.get('module')
        .then((res) => {
          this.modules = [...res.data.modules]
        })

    }

  }

}
</script>

<style lang="scss" scoped>
.installed-modules {
  .top-header {
    justify-content: flex-start !important;
    h4 {
      padding-left: 15px;
      font-size: 20px;
      font-family: 'SFProText-Light', sans-serif;
    }
  }
}
</style>
