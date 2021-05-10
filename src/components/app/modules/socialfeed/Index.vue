<template>
  <div>

    <Settings />

  </div>
</template>

<script>
import Settings from "./Settings"

export default {
  name: "index",

  components: {
    Settings
  },

  data() {
    return {
      currentScreen: 0,
      memory: {},
      moduleId: null
    }
  },

  created() {

    this.moduleId = Number(this.$route.params.moduleId)


    this.$root.$on('navigator::setBack', res => {
      if (res) {
        this.memory = this._.cloneDeep(this.list)
        this.list = [ { title: 'Back' } ]
      } else {
        this.list = this._.cloneDeep(this.memory)
      }
    })

    if (this.$route.query.hasOwnProperty('tab')) {
      let currentTab = this.$route.query.tab;

      let currentTabIndex = this._.findIndex(this.list, (v) => {
        return v.title.toLocaleLowerCase() === currentTab;
      })

      if (currentTabIndex >= 0) {
        this.currentScreen = currentTabIndex
      }
    }

  },

  methods: {
  }

}
</script>

<style scoped>

</style>
