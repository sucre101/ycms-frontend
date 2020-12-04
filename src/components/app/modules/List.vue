<template>

  <div class="page-modules">

    <page-navigation
      :list="[
        { title: 'Modules' },
        { title: 'Pages' }
      ]"
      @change="changeScreen"
    />

    <div v-for="(tab, index) in tabScreens">
      <component :is="tab" v-show="index === currentScreen" :ref="'tab' + index"/>
    </div>

  </div>
</template>

<script>
import PageNavigation from "../../base/PageNavigation";
import ModulesScreen from "./ModulesScreen";
import PageScreen from "./PageScreen";

export default {

  components: {
    ModulesScreen,
    PageScreen,
    PageNavigation
  },

  data() {
    return {
      tabScreens: [
        ModulesScreen, PageScreen
      ],
      currentScreen: 0
    }
  },

  methods: {

    changeScreen(index) {
      this.currentScreen = index
    }

  }

}
</script>

<style lang="scss" scoped>

</style>
