<template>
  <div>

    <page-navigation
        :list="tabs"
        :selected="currentIndex"
        @change="changeView"
    />

    <div v-for="(tab, index) in tabScreens">
      <component :is="tab" v-show="index === currentIndex" :ref="'tab' + index"/>
    </div>

  </div>
</template>

<script>
import PageNavigation from "@/components/base/PageNavigation";
import MainTab from "./MainTab"
import PaymentTab from "./payment/Index"

export default {
  name: "index",

  components: {
    PageNavigation, MainTab, PaymentTab
  },

  data() {
    return {
      currentIndex: 0,
      tabs: [
        { title: 'Main' },
        { title: 'Payment' },
      ],
      tabScreens: [
        MainTab, PaymentTab
      ]
    }
  },

  mounted() {
    window.setTitle('Application settings')
  },

  created() {
    if (this.$route.query.hasOwnProperty('tab')) {
      let currentTab = this.$route.query.tab;

      let currentTabIndex = this._.findIndex(this.tabs, (v) => {
        return v.title.toLocaleLowerCase() === currentTab;
      })

      if (currentTabIndex >= 0) {
        this.currentIndex = currentTabIndex
      }
    }
  },

  methods: {
    changeView(view) {
      this.currentIndex = view
      this.$router.replace(
          { query: { tab: this.tabs[view].title.toLocaleLowerCase() }}
      )
    }
  }
}
</script>

<style scoped>

</style>
