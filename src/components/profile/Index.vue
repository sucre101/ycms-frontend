<template>
  <div class="main-entry">
    <PageNavigation :component-list="tabScreens"/>
  </div>
</template>

<script>
import PageNavigation from "@/components/base/PageNavigationTab"
import ExtendedData from "@/components/profile/extended/ExtendedData"
import General from './general/GeneralData'

export default {
  name: "index",

  components: {
    General, PageNavigation, ExtendedData
  },

  data() {
    return {
      tabScreens: [
        General, ExtendedData
      ],
      userExtended: []
    }
  },

  created() {

  },

  mounted() {
    window.setTitle(this.$route.meta.title)
  },

}
</script>

<style scoped>

</style>
