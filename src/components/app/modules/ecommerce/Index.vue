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
import StoreList from "./Stores/Index"
import CategoriesList from "./Categories/Index"
import ProductList from "./Products/Index"
import Settings from "./Settings/Index"
import Orders from "./Orders/Index"
import Unions from "./Unions/Index"


export default {
  name: "index",

  components: {
    PageNavigation, StoreList, CategoriesList, ProductList, Settings
  },

  data() {
    return {
      tabScreens: [
        StoreList, CategoriesList, ProductList, Unions, Orders, Settings
      ],
      currentScreen: 0,
      list: [
        { title: 'Stores' },
        { title: 'Categories' },
        { title: 'Products' },
        { title: 'Unions' },
        { title: 'Orders' },
        { title: 'Settings' },
      ],
      memory: {},
      moduleId: null,
    }
  },

  created() {

    this.moduleId = Number(this.$route.params.moduleId)

    this.$root.$on('navigator::setBack', this.setBack)

    if (this.$route.query.hasOwnProperty('tab')) {
      let currentTab = this.$route.query.tab;

      let currentTabIndex = this._.findIndex(this.list, (v) => {
        return v.title.toLocaleLowerCase() === currentTab;
      })

      if (currentTabIndex >= 0) {
        this.currentScreen = currentTabIndex
      }
    }

    this.$on('go::product', (id) => {

      let index = this._.findIndex(this.memory, { title: 'Products' })

      this.setBack(false)

      this.changeScreen(index)
      this.$router.replace({ query: { tab: 'products', product: id } })
    })

  },

  methods: {

    changeScreen(currentTabIndex) {
      this.currentScreen = currentTabIndex
      this.$router.replace(
          { query: { tab: this.list[currentTabIndex].title.toLocaleLowerCase() }}
          )
    },

    setBack(res) {
      if (res) {
        this.memory = this._.cloneDeep(this.list)
        this.list = [ { title: 'Back' } ]
      } else {
        this.list = this._.cloneDeep(this.memory)
      }
    }

  },

  destroyed() {
    this.$off('go::product')
  }

}
</script>

<style scoped>

</style>
