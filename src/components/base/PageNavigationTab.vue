<template>
  <div>
    <page-navigation
        :list="tabs"
        :selected="currentIndex"
        @change="changeScreen"
    />

    <div v-for="(tab, index) in componentList">
      <component :is="tab" v-if="index === currentIndex" :ref="'tab' + index"/>
    </div>
  </div>
</template>

<script>
import PageNavigation from "@/components/base/PageNavigation"

export default {
  name: "page-navigation-tab",

  components: {
    PageNavigation
  },

  props: {
    componentList: {
      type: Array,
      required: true,
      default: () => {
        return []
      }
    }
  },

  data() {
    return {
      currentIndex: 0
    }
  },

  computed: {
    tabs() {
      return this._.each(this.componentList, value => value.hasOwnProperty('title') ? [].push(value.title) : false)
    }
  },

  created() {
    if (this.$route.query.hasOwnProperty('tab')) {
      let currentTab = this.$route.query.tab;

      let currentTabIndex = this._.findIndex(this.componentList, (v) => {
        return v.title.toLocaleLowerCase() === currentTab;
      })

      if (currentTabIndex >= 0) {
        this.currentIndex = currentTabIndex
      }
    }
  },

  methods: {
    changeScreen(currentTabIndex) {
      this.currentIndex = currentTabIndex
      this.$router.replace(
          { query: { tab: this.componentList[currentTabIndex].title.toLocaleLowerCase() }}
      )
    },
  }


}
</script>

<style scoped>

</style>
