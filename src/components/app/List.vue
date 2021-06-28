<template>
  <div class="apps-list" :class="{ 'scroller' : appsCount }">

    <div class="create" @click="createApp()">Create</div>

    <div class="create-app-block">
      <div class="title">
        <h4>Creating new application</h4>
      </div>
      <div class="main">
        <span>Please enter full informations below</span>

        <div class="input-group">
          <label>
            <input type="text" placeholder="App Name" class="input-field" v-model="appName">
          </label>
        </div>

      </div>
    </div>

<!--    <div class="apps-list-item"-->
<!--         v-for="app in apps" :key="app.id"-->
<!--         :data-id="app.id"-->
<!--    >-->
<!--      <div class="app-item">-->
<!--        <div class="app-icon">-->
<!--          <img :src="app.image ? app.image : $root.baseUrl + '/img/ycms/base_icon.png'"/>-->
<!--        </div>-->
<!--        <div class="title">-->
<!--          {{ app.name }}-->
<!--          <span>Administrator <p>{{ app.user.name }} {{ app.user.lastname }}</p></span>-->
<!--        </div>-->
<!--        <div class="app-item-action">-->
<!--          <div class="action-btn y-btn-black" @click="showDeleteWarning(app)">-->
<!--            Delete-->
<!--          </div>-->
<!--          <div class="action-btn y-btn-blue" @click.prevent="selectApplication(app)">-->
<!--            Manage-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--    </div>-->

<!--    <div class="apps-list-item" v-if="!isEmployee">-->
<!--      <div class="app-item new-app">-->
<!--        <div class="title">-->
<!--          New App-->
<!--        </div>-->
<!--        <div class="app-name">-->
<!--          <input id="application-name" type="text" v-model.trim="appName">-->
<!--          <Tooltip-->
<!--              text="* Please don’t name the mobile app with more than 8 characters and without spaces. This name will be displayed under the icon on your smartphone’s home screen"/>-->
<!--        </div>-->
<!--        <div class="app-item-action">-->
<!--          <div class="action-btn y-btn-blue" @click="showNewAppModal">-->
<!--            Create-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--    </div>-->

<!--    <sweet-modal-->
<!--        class="modal"-->
<!--        ref="newAppModal"-->
<!--        width="850"-->
<!--        overlay-theme="dark"-->
<!--    >-->

<!--      <div id="2-step" v-if="step === 1">-->
<!--        <h6>Create your first "Killer App"</h6>-->
<!--        <div class="icon-content">-->
<!--          <div class="left-image">-->
<!--            <p class="text-info">App icon <span class="required-text">*</span></p>-->
<!--            <p class="required-text pt-2">* Please download the main icon for your mobile app. This icon will only be-->
<!--              displayed on your smartphone and will not be displayed on the App Store or Google Play</p>-->
<!--          </div>-->
<!--          <div class="right-image">-->
<!--            <ycms-image-uploader-->
<!--                name="app-icon"-->
<!--                type="app-icon"-->
<!--                :crop-in-modal="false"-->
<!--                :ratio="1"-->
<!--                style="margin-right: 15px"-->
<!--                size="43px"-->
<!--                icon="app-image"-->
<!--                min-dimensions="1024"-->
<!--            ></ycms-image-uploader>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="icon-content">-->
<!--          <div class="left-image">-->
<!--            <p class="text-info">Startup image-->
<!--              <span class="required-text">*</span>-->
<!--            </p>-->
<!--            <p class="required-text pt-2">* Please download the startup image for your mobile app. This image is loaded-->
<!--              when the app starts for the first time. You should consider special fields for cropping the image so that-->
<!--              your image looks good in portrait and landscape mode</p>-->
<!--          </div>-->
<!--          <div class="right-image">-->
<!--            <ycms-image-uploader-->
<!--                name="startup-image"-->
<!--                type="startup-image"-->
<!--                :ratio="1"-->
<!--                style="margin-right: 15px"-->
<!--                size="43px"-->
<!--                icon="image"-->
<!--                min-dimensions="1024"-->
<!--            ></ycms-image-uploader>-->
<!--          </div>-->
<!--        </div>-->
<!--        <a @click="goToStep(2)" class="small-rounded-btn green-bg text-white pr-2">-->
<!--          Next-->
<!--          <i class="fa fa-chevron-right"></i>-->
<!--        </a>-->
<!--      </div>-->

<!--      <div id="4-step" v-if="step === 2">-->
<!--        <h6>Select your first some feature for Homepage </h6>-->
<!--        <div>-->
<!--          <ycms-page-template-->
<!--              v-for="module in modules" :key="module.id"-->
<!--              :data-id="module.id"-->
<!--              name="homePageModule"-->
<!--              :title="module.title"-->
<!--              :description="module.description"-->
<!--              :img="module.image"-->
<!--              :value="module.name">-->
<!--          </ycms-page-template>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div id="5-step" v-if="step === 3">-->
<!--        <div class="loader">-->
<!--          <div class="leftHalf"></div>-->
<!--          <div class="spinner"></div>-->
<!--          <div class="rightHalf"></div>-->
<!--        </div>-->
<!--        <h1>The app is generating</h1>-->
<!--        <h6>Please do not close the browser window until the first mobile app is generated</h6>-->
<!--      </div>-->

<!--      <div class="pagination">-->
<!--        <span class="y-arrow-left" v-if="step !== 1" @click="step&#45;&#45;"></span>-->
<!--        <h2>Step {{ step }} of 3</h2>-->
<!--        <span class="y-arrow-right" @click="step++"></span>-->
<!--      </div>-->

<!--    </sweet-modal>-->
  </div>

</template>

<script>
import YcmsImageUploader from "../YcmsImageUploader"
import Tooltip from '../../components/base/ui/Tooltip'

export default {

  components: {
    YcmsImageUploader,
    Tooltip
  },

  data() {
    return {
      apps: [],
      modules: this.modulesList,
      appToDelete: null,
      appName: null,
      homePageModule: null,
      // menuTemplate: null,
      awaitingIcon: {},
      debug: true,
      step: 1,
      appsList: [],
      modulesList: [],
      appsCount: false,
      isEmployee: this.$store.getters.isEmployee
    }
  },

  props: {},

  watch: {
    step(val) {
      if (val === 3) {
        return this.createApp()
      }
    }
  },

  beforeCreate() {
    // this.$store.commit('unsetApplication')
    // this.$parent.$emit('app::unset')
  },

  created() {
    this.$parent.currentApp = false
    this._getData()
  },

  mounted() {
    // this.$root.$on('ls-change', (name, val) => {
    //   if (name === 'homePageModule') this.homePageModule = val
    //   // if (name == 'menuTemplate') this.menuTemplate = val
    // })
  },

  destroyed() {
    this.$parent.currentApp = true
  },

  methods: {

    createApp() {
      if (this.appName === '' || this.appName === null) {
        this.notifier.warning('Enter app name')
        return false
      }
      axios.put('/apps', {
        appName: this.appName,
      })
    },

    _getData() {
      axios.get('/apps')
          .then((res) => {
            this.apps = this._.cloneDeep(res.data.apps);
          })
    },

    selectApplication(app) {
      this.$store.commit('setApplication', JSON.stringify(app))
      this.$parent.$emit('app::set', app)

      this.$parent.showSidebar = true
      this.$nextTick(() => {
        this.$router.push({name: 'app', params: {slug: app.slug}})
      })
    }

  }

}
</script>

<style lang="scss" scoped>


.apps-list {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  width: 100%;

  .create {
    width: 135px;
    height: 38px;
    margin: 20px 23px 20px 819px;
    padding: 10.8px 45.5px 9.2px;
    border-radius: 8px;
    background-color: #8674f4;
    font-size: 15px;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    letter-spacing: 1px;
    font-family: 'SFProText-Light', sans-serif;
  }

  .create-app-block {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    background: #ffffff;
    box-shadow: 0 10px 15px 0 rgb(202 215 227 / 11%);
    border: solid 1px #edf2f6;
    .title {
      width: 100%;
      display: flex;
      border-bottom: 1px solid #edf2f6;
      padding: 10px 20px;
      h4 {
        color: #222b45;
        font-size: 20px;
        margin-bottom: 0;
      }
    }
    .main {
      width: 100%;
      display: flex;
      padding: 10px 20px;
      flex-direction: column;
      span {
        color: #687c97;
        font-size: 15px;
        padding: 15px 0;
        margin-bottom: 15px;
      }
      .input-group {
        width: 35%;
        input {
          width: 100%;
          border-radius: 8px;
          border: solid 1px #edf2f6;
          color: #687c97;
        }
        input::-moz-placeholder {
          font-family: 'SFProText-Light', sans-serif;
          color: #c5cee0;
        }
        input::-webkit-input-placeholder {
          font-family: 'SFProText-Light', sans-serif;
          color: #c5cee0;
        }
      }
    }
  }

  .apps-list-item:hover {
    border: solid 3px rgba(9, 137, 204, 0.73);
  }

  .pagination {
    display: flex;
    width: 30%;
    margin: auto;
    align-items: center;
    justify-content: space-evenly;

    h2 {
      margin-bottom: 3px;
    }
  }
}

.scroller {
  overflow-y: scroll;
  height: 100%;
}

.apps-list::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #F5F5F5;
  border-radius: 10px;
}

.apps-list::-webkit-scrollbar {
  width: 5px;
  background-color: #F5F5F5;
}

.apps-list::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background-color: #FFF;
  background-image: -webkit-linear-gradient(top,
      #0989cc,
      #2696d1 53%,
      #0989cc);
}


.actions {
  display: block;
  color: #0997b1;
  margin-left: 22px;
  min-width: 100px;
}

.required-text {
  color: #b40000;
  text-align: left;
  font-size: 14px;
}

.loader {
  position: relative;
  width: 100px;
  height: 100px;
  overflow: hidden;
  margin: 50px auto;
}

.leftHalf, .rightHalf, .spinner {
  top: 0;
  position: absolute;
  width: 50%;
  height: 100%;
}

.leftHalf {
  left: 0;
  background: #fff;
  z-index: 3;
  opacity: 1;
  -webkit-animation: showHide 2s infinite steps(1, end);
  border-radius: 100% 0 0 100%/ 50% 0 0 50%;
}

.rightHalf {
  right: 0;
  background: #0997b1;
  z-index: 1;
  opacity: 0;
  -webkit-animation: showHide 2s infinite steps(1, end) reverse;
  border-radius: 0 100% 100% 0/ 0 50% 50% 0;
}

.spinner {
  overflow: hidden;
  left: 0;
  background: #0997b1;
  -webkit-animation: spin 2s linear infinite;
  -webkit-transform-origin: center right;
  z-index: 2;
  border-radius: 100% 0 0 100%/ 50% 0 0 50%;
}

@-webkit-keyframes spin {
  0% {
    -webkit-transform: rotate(0deg);
  }

  100% {
    -webkit-transform: rotate(360deg);
  }
}

@-webkit-keyframes showHide {
  0% {
    opacity: 1;
  }

  50%, 100% {
    opacity: 0;
  }
}

.icon-content {

  align-items: initial;
  flex: 1;
  height: auto;

  .right-image {
    width: 50%;
    height: 205px;
    float: right;
  }

  .left-image {
    width: 50%;
    height: 205px;
    float: left;
  }
}
</style>
