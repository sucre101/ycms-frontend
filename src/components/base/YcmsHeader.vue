<template>
  <header ref="header">

    <div class="profile-card">
      <p class="profile-name">{{ name }} {{ lastName }}</p>
      <span>Manager</span>
    </div>

    <router-link :to="'/dashboard'" tag="div" class="small-rounded-btn top-button">
      Dashboard
    </router-link>

    <div class="small-rounded-btn top-button">Support</div>
    <div class="small-rounded-btn top-button">Tour</div>
    <div class="small-rounded-btn top-button" @click="$root.$emit('fmanager::open', true)">File Manager</div>
    <div class="small-rounded-btn top-button" @click="logout">Exit</div>

  </header>
</template>

<script>
// import Language from "./Language";

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
    }
  },

  data() {
    return {
      currentApp: {}
    }
  },

  created() {
    this.currentApp = this._.cloneDeep(this.app)
  },

  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push({name: 'login'})
    },
  }
}
</script>

<style scoped lang="scss">

header {
  display: flex;
  align-items: center;
  height: 80px;
  background-color: #c6c9cf;

  .profile-card {

    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    width: 264px;
    height: 100%;
    margin-right: 75px;

    .profile-name {
      font-weight: 700;
      margin: 0;
    }
  }

}

//header {
//  position: relative;
//
//  .left-side {
//    display: flex;
//    align-items: center;
//    width: 400px;
//    position: absolute;
//    top: 0;
//    left: 0;
//
//    .app-icon {
//      width: 111px;
//      display: flex;
//      align-items: center;
//      justify-content: center;
//
//      img {
//        width: auto;
//        max-width: 80%;
//      }
//    }
//
//    .platform {
//      display: flex;
//      flex-direction: column;
//
//      .name {
//        font-size: 24px;
//        font-weight: 500;
//        font-stretch: normal;
//        font-style: normal;
//        line-height: 1.21;
//        letter-spacing: normal;
//        color: #0989cc;
//      }
//
//      p {
//        font-size: 14px;
//        color: #222222;
//        margin: 0;
//
//        span {
//          font-size: 13px;
//          font-weight: normal;
//          font-stretch: normal;
//          font-style: normal;
//          line-height: 1.15;
//          letter-spacing: normal;
//          color: #b5b5b5;
//          padding-right: 10px;
//        }
//      }
//    }
//  }
//
//
//  .top-bar {
//    display: flex;
//    align-items: center;
//    width: calc(100% - 111px);
//    flex-direction: row;
//    justify-content: space-between;
//    margin-left: 400px;
//
//    .top-links {
//      display: flex;
//      flex-direction: row;
//      justify-content: space-between;
//      width: 60%;
//      font-size: 13px;
//      font-weight: 500;
//      font-stretch: normal;
//      font-style: normal;
//      line-height: 1.15;
//      letter-spacing: normal;
//      color: #222222;
//
//      > div {
//        display: flex;
//        align-items: center;
//        cursor: pointer;
//        position: relative;
//
//        img {
//          padding-right: 10px;
//        }
//
//        span {
//          font-weight: bold;
//          display: inline-block;
//          vertical-align: middle;
//        }
//
//      }
//    }
//
//  }
//}
</style>
