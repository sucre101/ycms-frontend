<template>
  <header ref="header">

    <div class="hamburger">
      <i class="fas fa-bars" @click="hideSidebar()"></i>
      <h1 id="title">{{ title }}</h1>
    </div>

    <div class="user-control-block">

      <div class="publish-button">Publish the app</div>
      <div class="file-manager-icon" @click="$root.$emit('fmanager::open', true)">
        <i class="far fa-folder-open"></i>
      </div>
      <div class="messages-icon new-event"></div>
      <div class="notification-icon new-event"></div>
      <div class="user-avatar" @click="$router.push({name: 'settings'})"></div>

    </div>

<!--    <div class="profile-card">-->
<!--      <p class="profile-name">{{ name }} {{ lastName }}</p>-->
<!--      <span>Manager</span>-->
<!--    </div>-->

<!--    <router-link :to="'/dashboard'" tag="div" class="small-rounded-btn top-button">-->
<!--      Dashboard-->
<!--    </router-link>-->

<!--    <div class="small-rounded-btn top-button">Support</div>-->
<!--    <div class="small-rounded-btn top-button">Tour</div>-->
<!--    <div class="small-rounded-btn top-button" @click="$root.$emit('fmanager::open', true)">File Manager</div>-->
<!--    <div class="small-rounded-btn top-button" @click="logout">Exit</div>-->

  </header>
</template>

<script>
// import Language from "./Language";
import {swapSidebar} from "../../helpers/general"

export default {
  name: "ycms-header",

  // components: {
  //   Language
  // },

  props: {
    name: {
      type: String,
      default: 'John'
    },
    lastName: {
      type: String,
      default: 'Doe'
    },
    appName: {
      type: String,
    },
    app: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },

  computed: {
    currentAppName() {
      return this.currentApp !== null ? this.currentApp.name : false
    },
    title() {
      return this.$route.meta.title ? this.$route.meta.title : this.$root.pageTitle
    }
  },

  data() {
    return {
      currentApp: {}
    }
  },

  created() {
    //
    // if (this.app === null) {
    //   this.$router.push('apps')
    // }

    // this.currentApp = this._.cloneDeep(this.app)
  },

  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push({name: 'login'})
    },

    hideSidebar() {
      let sb = this.$store.getters.getSidebar
      swapSidebar(!sb)
    }

  }
}
</script>

<style scoped lang="scss">

header {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  height: 105px;
  width: 65%;
  margin-left: 240px;
  overflow: hidden;

  .user-control-block {
    width: 25%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    div {
      cursor: pointer;
    }
    .publish-button {
      width: 150px;
      height: 40px;
      color: white;
      background-image: linear-gradient(144deg, #ed59a3 3%, #af2c6d 10%, #e54896 29%);
      font-size: 15px;
      display: flex;
      justify-content: center;
      align-content: center;
      align-items: center;
      border-radius: 11px;
    }
    .messages-icon {
      width: 30px;
      height: 30px;
      background-repeat: no-repeat;
      background-position: 50%;
      background-image: url('~@/assets/img/comment.svg');
    }
    .notification-icon {
      width: 30px;
      height: 30px;
      background-repeat: no-repeat;
      background-position: 50%;
      background-image: url('~@/assets/img/Push.svg');
    }
    .user-avatar {
      width: 25px;
      height: 25px;
      border-radius: 50%;
      background-color: grey;
    }
    .file-manager-icon {
      color: #687c97;
      font-size: 20px;
    }
    .new-event {
      position: relative;
      &::before {
        content: "";
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #eb58a3;
        display: flex;
        right: 5px;
        position: absolute;
        top: 5px;
      }
    }
  }

}
.hamburger {
  width: 45%;
  display: flex;
  cursor: pointer;
  align-items: center;
  margin-left: 35px;
  i {
    cursor: pointer;
  }
}
</style>
