<template>
  <div class="apps-list">
    <div class="apps-list-item"
         v-for="app in apps" :key="app.id"
         :data-id="app.id"
    >
      <div class="ml-auto" >
<!--                <img-->
<!--                  style="margin-top: 20px"-->
<!--                  src="@/assets/img/Group 260.svg"-->
<!--                  alt="icon"-->
<!--                >-->
      </div>
      <div class="title">
        {{ app.slug }}
      </div>
      <div class="actions">
        <a :href="`/app/${app.slug}/pages`" class="small-rounded-btn blue-bg text-white">Edit</a>
      </div>
      <div class="actions">
        <a @click="duplicateApp(app.id)" class="small-rounded-btn green-bg text-white" >Duplicate</a>
      </div>

      <img
        class="ml-auto"
        src="@/assets/img/garbage.png"
        alt="garb"
        @click="showDeleteWarning(app)"
      >
    </div>

<!--    <ycms-action-buttons-->
<!--      :buttons-list="[-->
<!--        {-->
<!--          title: 'NEW APP',-->
<!--          handler: 'eval:this.$parent.showNewAppModal()',-->
<!--          class: 'bg-green-gradient'-->
<!--        },-->
<!--      ]"-->
<!--      align="right"-->
<!--    />-->
    <sweet-modal
      class="modal"
      ref="newAppModal"
      width="850"
      overlay-theme="dark"
    >


      <div v-if="step == 1">
        <h6>Create your first "Killer App"</h6>
        <input
          v-model="appName"
          class="rounded-input"
          width="100%"
          type="text"
          placeholder="Name of App"
        />
        <p class="required-text">* Please don’t name the mobile app with more than 8 characters and without spaces. This name will be displayed under the icon on your smartphone’s home screen</p>

        <a @click="goToStep(2)" class="small-rounded-btn green-bg text-white" >Next   <i class="fa fa-chevron-right"></i></a>
      </div>
      <div id="2-step" v-if="step == 2">
        <h6>Create your first "Killer App"</h6>
        <div class="icon-content">
          <div class="left-image">
            <p class="text-info">App icon <span class="required-text">*</span> </p>
            <p class="required-text pt-2">* Please download the main icon for your mobile app. This icon will only be displayed on your smartphone and will not be displayed on the App Store or Google Play</p>
          </div>
          <div class="right-image">
            <ycms-image-uploader
              name="app-icon"
              type="app-icon"
              :crop-in-modal="false"
              :ratio="1"
              style="margin-right: 15px"
              size="43px"
              icon="app-image"
              min-dimensions="1024"
              v-gref:appicon
            ></ycms-image-uploader>
          </div>
        </div>
        <div class="icon-content">
          <div class="left-image">
            <p class="text-info">Startup image <span class="required-text">*</span></p>
            <p class="required-text pt-2">* Please download the startup image for your mobile app. This image is loaded when the app starts for the first time. You should consider special fields for cropping the image so that your image looks good in portrait and landscape mode</p>
          </div>
          <div class="right-image">
            <ycms-image-uploader
              name="startup-image"
              type="startup-image"
              :ratio="1"
              style="margin-right: 15px"
              size="43px"
              icon="image"
              min-dimensions="1024"
              v-gref:appicon
            ></ycms-image-uploader>
          </div>
        </div>
        <a @click="goToStep(1)" class="small-rounded-btn code-bg text-white" > <i class="fa fa-chevron-left"></i> Back</a>
        <a @click="goToStep(4)" class="small-rounded-btn green-bg text-white pr-2" >Next  <i class="fa fa-chevron-right"></i></a>
      </div>
      <!--      <div id="3-step" v-if="step == 3">-->
      <!--        <h6>Select template menu</h6>-->
      <!--        <div>-->
      <!--          <ycms-page-template-->
      <!--            name="menuTemplate"-->
      <!--            title="Side Menu"-->
      <!--            img="@/assets/img/side-menu.svg"-->
      <!--            value="side-menu">-->
      <!--          </ycms-page-template>-->
      <!--          <ycms-page-template-->
      <!--            name="menuTemplate"-->
      <!--            title="Side Menu + Tab"-->
      <!--            img="@/assets/img/side-menu-tabs.svg"-->
      <!--            value="side-menu-tabs">-->
      <!--          </ycms-page-template>-->
      <!--          <ycms-page-template-->
      <!--            name="menuTemplate"-->
      <!--            title="Tab Menu"-->
      <!--            img="@/assets/img/tabs.svg"-->
      <!--            value="tabs">-->
      <!--          </ycms-page-template>-->
      <!--          <ycms-page-template-->
      <!--            name="menuTemplate"-->
      <!--            title="Blank Page"-->
      <!--            img="@/assets/img/blank.svg"-->
      <!--            value="blank">-->
      <!--          </ycms-page-template>-->
      <!--        </div>-->
      <!--        <a @click="goToStep(2)" class="small-rounded-btn code-bg text-white" > <i class="fa fa-chevron-left"></i> Back</a>-->
      <!--        <a @click="goToStep(4)" class="small-rounded-btn green-bg text-white" >Next</a>-->
      <!--      </div>-->
      <div id="4-step" v-if="step == 4">
        <h6>Select your first some feature for Homepage </h6>
        <div>
          <ycms-page-template  v-for="module in modules" :key="module.id"
                               :data-id="module.id"
                               name="homePageModule"
                               :title="module.title"
                               :description="module.description"
                               :img="module.image"
                               :value="module.name">
          </ycms-page-template>
        </div>
        <a @click="goToStep(2)" class="small-rounded-btn code-bg text-white" > <i class="fa fa-chevron-left"></i> Back</a>
        <a @click="goToStep(5)" class="small-rounded-btn green-bg text-white" >Next  <i class="fa fa-chevron-right"></i></a>
      </div>
      <div id="5-step" v-if="step == 5">
        <div class="loader">
          <div class="leftHalf"></div>
          <div class="spinner"></div>
          <div class="rightHalf"></div>
        </div>
        <h1>The app is generating</h1>
        <h6>Please do not close the browser window until the first mobile app is generated</h6>
      </div>
    </sweet-modal>
  </div>

</template>

<script>

    export default {
        data() {
            return {
                apps: this.appsList,
                modules: this.modulesList,
                appToDelete: null,
                appName: null,
                homePageModule: null,
                // menuTemplate: null,
                awaitingIcon: {},
                debug: true,
                step: 1
            }
        },

        computed: {
        },

        props: {
            appsList: Array,
            modulesList: Array,
        },

        methods: {
            goToStep(st){
                this.step = st;
                if(st == 5){
                    this.createApp()
                }
            },
            showDeleteWarning(app){
                this.appToDelete = app
                this.notifier.confirm('Are you sure?', this.deleteApp)
            },
            deleteApp() {
                post('/app/delete', {
                    app: this.appToDelete.id,
                })
                    .then(res => this.apps.splice(this.apps.indexOf(this.appToDelete), 1))
            },
            duplicateApp(app) {
                post('/app/duplicate', {
                    app: app,
                })
                    .then(res => this.apps.push(res.app))
            },
            showNewAppModal() {
                this.$refs.newAppModal.open()
            },
            createApp() {
                post('/store-app', {
                    appName: this.appName,
                    homePageModule: this.homePageModule,
                    // menuTemplate: this.menuTemplate,
                })
                    .then(res => this.apps.push(res.app))
                    .then(this.$refs.newAppModal.close())
                    .then(this.step = 1)
                    .then(this.appName = null)
            },
        },

        mounted() {
            this.$root.$on('ls-change', (name, val) => {
                if (name == 'homePageModule') this.homePageModule = val
                // if (name == 'menuTemplate') this.menuTemplate = val
            })
        },

    }
</script>

<style lang="scss" scoped>


  .apps-list {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin: 0 39px 0 0;
  }

  .apps-list-item {

    justify-content: flex-start;
    width: 100%;
    height: 56px;
    padding: 0 21px;
    background-color: rgba(#868686, .06);
    border-radius: 5px;
    margin-bottom: 5px;
  }
  .actions {
    display: block;
    color: #0997b1;
    margin-left: 22px;
    min-width: 100px;
  }
  .title {
    justify-content: flex-start;
    width: 50%;
    height: 31px;
    font-size: 14px;
    font-weight: 300;
    margin-left: 57px;
    padding-left: 14px;
  }
  .required-text{
    color:#b40000;
    text-align:left;
    font-size:14px;
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
