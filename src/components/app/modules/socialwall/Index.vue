<template>
  <div>
    <page-navigation
        ref="pNavigator"
        :list="list"
        @change="changeScreen"
        :selected="currentScreen"
    />

    <div v-for="(tab, index) in tabScreens">
      <component :is="tab" v-if="index === currentScreen"/>
    </div>

  </div>
</template>

<script>
import PageNavigation from "../../../base/PageNavigation"
import Tags from "./Tags/List";
import Posts from "./Posts/Index";

export default {
  name: "index",

  components: {
    PageNavigation, Tags, Posts
  },

  data() {
    return {
      tabScreens: [
        Posts, Tags
      ],
      currentScreen: 0,
      list: [
        { title: 'Posts' },
        { title: 'Tags' },
      ],
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
    changeScreen(currentTabIndex) {
      this.currentScreen = currentTabIndex
      this.$router.replace(
          { query: { tab: this.list[currentTabIndex].title.toLocaleLowerCase() }}
          )
    }
  }

}
</script>

<style scoped>

</style>
