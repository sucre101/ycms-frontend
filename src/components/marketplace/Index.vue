<template>
  <div class="box-list modules">
    <div class="top-header">
      <h4>Feature List</h4>
      <div class="feature-filter">
        <span>All feature</span>
        <span>E-Commerce</span>
        <span>Social Networks</span>
      </div>
      <div class="search-block">

      </div>
    </div>
    <vue-custom-scrollbar class="content-scroll">
      <div class="main-block grid">
        <div class="module-block" v-for="module in modules">
          <div class="icon">
            <img :src="getImage(module.image)">
          </div>
          <div class="description">
            <div class="name">{{ module.title }}</div>
            <span>{{ module.description }}</span>
          </div>
          <div class="actions">
            <div class="toggle-install">
              <ToggleCheck
                  :value="checkModuleInstalled(module)"
                  @complete="installModule($event, module)"
              />
              {{ getInstalledTitle(module) }}
            </div>
            <div class="button-action"
                 :class="getModuleLicenseButtonColor(module)">
              {{ getModuleLicenceTitle(module) }}
            </div>
          </div>
        </div>

        <div class="module-block future-module">
          <div class="icon">
            <img src="http://api.ycms/img/modules/Sales.module.png">
          </div>
          <div class="description">
            <div class="name">AR-Wiki</div>
            <span>You can create your own AR-Wiki directory with a built-in editor</span>
          </div>
          <div class="actions">
            <div class="toggle-install">
              <ToggleCheck
                  :value="false"
                  @complete="installModule($event, module)"
                  :disabled="true"
              />
              unavailable
            </div>
            <div class="button-action button-invest">
              invest
            </div>
          </div>
        </div>

        <div class="module-block future-module">
          <div class="icon">
            <img src="http://api.ycms/img/modules/Sales.module.png">
          </div>
          <div class="description">
            <div class="name">Voice-bot</div>
            <span>Program your own voice bot for your business. Voice chat based on GPT-3</span>
          </div>
          <div class="actions">
            <div class="toggle-install">
              <ToggleCheck
                  :value="false"
                  @complete="installModule"
                  :disabled="true"
              />
              unavailable
            </div>
            <div class="button-action button-invest">
              invest
            </div>
          </div>
        </div>

        <div class="module-block future-module">
          <div class="icon">
            <img src="http://api.ycms/img/modules/Sales.module.png">
          </div>
          <div class="description">
            <div class="name">Form-builder</div>
            <span>You will be able to create forms with different ones for any needs. Logical triggers.</span>
          </div>
          <div class="actions">
            <div class="toggle-install">
              <ToggleCheck
                  :value="false"
                  :disabled="true"
              />
              unavailable
            </div>
            <div class="button-action button-invest">
              invest
            </div>
          </div>
        </div>
      </div>
    </vue-custom-scrollbar>

    <div class="bottom-footer">

    </div>
  </div>
</template>

<script>
import {imageUrl} from "@/helpers/general"
import ToggleCheck from "../base/ui/ToggleCheck"
import vueCustomScrollbar from 'vue-custom-scrollbar'
import "vue-custom-scrollbar/dist/vueScrollbar.css"

export default {
  name: "index",

  components: {
    ToggleCheck, vueCustomScrollbar
  },

  data() {
    return {
      modules: [],
      installedModules: []
    }
  },

  mounted() {
    window.setTitle(this.$route.meta.title)
  },

  created() {
    this._loadModules()
  },

  methods: {

    getModuleLicenceTitle($module) {
      switch ($module.module_licence) {
        case 1:
          return 'Free'
        case 2:
          return 'Non free'
      }
    },

    getModuleLicenseButtonColor($module) {
      switch ($module.module_licence) {
        case 1:
          return 'free-btn'
        case 2:
          return 'btn-buy'
      }
    },

    installModule($event, $module) {
      return $event.value ? this._addModule($module.id) : this._removeModule($module.id)
    },

    _addModule($id) {
      console.log($id, 'install')
    },

    _removeModule($id) {
      console.log($id, 'remove module')
    },

    getInstalledTitle($module) {
      if (this.checkModuleInstalled($module)) {
        return `Installed`
      } else {
        return `Not installed`
      }
    },

    checkModuleInstalled($module) {

      let index = this._.findIndex(this.installedModules, { module_id: $module.id })

      return index !== -1;
    },

    getImage(path) {
      return imageUrl(path)
    },

    _loadModules() {

      axios.get('modules')
        .then((res) => {
          if (res.data.success) {
            this.modules = [...res.data[0].modules]
            this.installedModules = [...res.data[0].installed]
          }
        })

    }

  }

}
</script>

<style scoped lang="scss">
.modules {
  .top-header {
    h4 {
      color: #222b45;
      width: 33%;
      font-size: 20px;
      margin: 0;
      padding-left: 15px;
    }
    .feature-filter {
      width: 33%;
      display: flex;
      justify-content: space-around;
    }
    .search-block {
      width: 33%;
    }
  }
  .module-block {
    background-color: #f9fbfd;
    display: flex;
    width: 32%;
    margin: 10px 0;
    flex-direction: row;
    flex-wrap: wrap;
    &.future-module {
      img {
        filter: grayscale(100%);
      }
    }
    .icon {
      width: 45%;
      display: flex;
      img {
        width: 100%;
      }
    }
    .description {
      width: 55%;
      display: flex;
      flex-direction: column;
      margin-top: 15px;
      .name {
        color: #222b45;
        font-size: 13px;
        font-family: 'SFProText-Bold', sans-serif;
        margin-bottom: 10px;
      }
      span {
        color: #687c97;
        font-size: 13px;
      }
    }
    .actions {
      width: 100%;
      display: flex;
      min-height: 30px;
      justify-content: space-between;
      padding: 0 20px;
      padding-bottom: 15px;
      .toggle-install {
        width: 35%;
        font-size: 13px;
        display: flex;
        align-items: center;
        label {
          padding-right: 15px;
        }
      }
      .button-action {
        width: 135px;
        height: 38px;
        order-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-family: 'SFProText-Light', sans-serif;
        border-radius: 8px;
        &.free-btn {
          background-color: #687c97;
        }
        &.button-invest {
          background-color: #ED59A3;
          cursor: pointer;
        }
      }
    }
  }
}
</style>