<template>
  <div>

    <div class="all-but-phone" v-if="isLoggedIn">
      <YcmsHeader
        :name="currentUser.name"
        :last-name="currentUser.lastname"
        :app="app"
      />

      <div id="main-content">

        <ycms-drawer-menu
          v-if="isLoggedIn && currentApp"
          :app="app"
        />

        <div class="settings-block">
          <router-view></router-view>
        </div>

      </div>

      <FileManager v-if="$root.openManager" ref="FileManager"/>

<!--      <Reference ref="reference"/>-->

      <!--      <ycms-phone-block-->
      <!--        v-if="isLoggedIn && currentApp"-->
      <!--        _host="http://localhost:8100/"-->
      <!--        :size="getSizePhone"-->
      <!--      ></ycms-phone-block>-->

    </div>

    <div id="auth" v-else>
      <router-view></router-view>
    </div>

  </div>

</template>

<script>

import { mapGetters } from 'vuex'
import YcmsHeader from "./base/YcmsHeader"
import YcmsDrawerMenu from "./YcmsDrawerMenu"
import FileManager from "@/components/base/filemanager/FileManager";
// import Reference from "./base/Reference";

export default {
  name: "App",

  components: {
    YcmsDrawerMenu,
    YcmsHeader,
    FileManager
    // Reference
  },

  data() {
    return {
      app: {},
      currentApp: false
    }
  },

  computed: {
    ...mapGetters(['currentUser', 'isLoggedIn', 'getApplication'])
  },

  created() {

    this.app = this._.cloneDeep(JSON.parse(this.getApplication))

    if (!this._.isEmpty(this.getApplication)) {
      this.currentApp = true
    }

    if (!this.getApplication && this.currentUser) {
      this.$router.push('/dashboard')
    }

    this.$on('app::set', (data) => {
      this.app = this._.cloneDeep(data)
      this.$children[0].currentApp = this._.cloneDeep(this.app)
    })

    this.$on('app::unset', () => {
      this.app = {}
      this.currentApp = false
      this.$children[0].currentApp = this._.cloneDeep({})
    })

    this.$root.$on('fmanager::open', (data) => {
      this.$root.openManager = data

      if (data) {
        this.$nextTick(() => {
          this.$refs.FileManager.$children[0].open()
        })
      }

    })
  },

  updated() {
    if (this.$route.name !== 'login') {
      // this.$refs.reference.setPage(this.$route.name.replace('-', '_'))
    }
  }

}
</script>

<style scoped>

</style>
