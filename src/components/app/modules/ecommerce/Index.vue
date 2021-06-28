<template>
  <div class="box-list">
    <div class="top-header">
      <PageNavigation
          :component-list="tabScreens"
          :return-component="true"
          @component="setComponent"
      />
    </div>
    <div class="main-block">
      <component :is="component"/>
    </div>
    <div class="bottom-footer">
      <div class="button new-element" @click.prevent="addElement" v-if="showAddButton">New element</div>
    </div>
  </div>
</template>

<script>
import PageNavigation from "../../../base/PageNavigationTab"
import StoreList from "./Stores/Index"
import CategoriesList from "./Categories/Index"
import ProductList from "./Products/Index"
import Settings from "./Settings/Index"
import Orders from "./Orders/Index"
import Unions from "./Unions/Index"
import Labels from "./Labels/Index"

export default {
  name: "index",

  components: {
    PageNavigation, StoreList, CategoriesList, ProductList, Settings, Labels, Orders, Unions
  },

  data() {
    return {
      tabScreens: [
        StoreList, CategoriesList, ProductList, Unions, Labels, Orders, Settings
      ],
      currentScreen: 0,
      memory: {},
      moduleId: null,
      component: StoreList,
      showAddButton: true
    }
  },

  mounted() {
    window.setTitle('Edit module E-Commerce')
  },

  updated() {
    this.checkShowAddButton()
  },

  created() {

    this.moduleId = Number(this.$route.params.moduleId)

    this.$root.$on('navigator::setBack', this.setBack)

    if (this.$route.query.hasOwnProperty('tab')) {
      let currentTab = this.$route.query.tab;

      let currentTabIndex = this._.findIndex(this.tabScreens, (v) => {
        return v.title.toLocaleLowerCase() === currentTab;
      })

      if (currentTabIndex >= 0) {
        this.currentScreen = currentTabIndex
        this.component = this.tabScreens[this.currentScreen]
      }

      this.checkShowAddButton()
    }

    this.$on('go::product', (id) => {

      let index = this._.findIndex(this.memory, { title: 'Products' })

      this.setBack(false)

      this.changeScreen(index)
      this.$router.replace({ query: { tab: 'products', product: id } })
    })

  },

  methods: {

    checkShowAddButton() {
      this.$nextTick(() => {
        switch (this.component.title) {
          case 'Orders':
            this.showAddButton = false
            break
          case 'Settings':
            this.showAddButton = false
            break
          default:
            this.showAddButton = true
            break
        }
      })
    },

    changeScreen(currentTabIndex) {
      this.currentScreen = currentTabIndex
      this.$router.replace(
          { query: { tab: this.list[currentTabIndex].title.toLocaleLowerCase() }}
          )
    },

    addElement() {
      this.$emit('add::element')
    },

    setComponent($component) {
      this.component = $component
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

<style scoped lang="scss">
.top-header {
  .page-nav {
    display: flex;
    height: 100%;
  }
}
.bottom-footer {
  .button {
    background-color: #8674f4;
    color: white;
    cursor: pointer;
    width: 150px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 10px 0 10px 20px;
    border-radius: 8px;
  }
}
</style>
