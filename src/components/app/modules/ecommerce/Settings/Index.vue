<template>
  <div class="table-list-items">

    <InnerTab
      :items="tabs"
      @change="changeTab"
    />

    <div class="btn-action blue save" @click="_saveChanges" v-if="activeForSave">Save</div>

    <div class="container">

      <div class="settings_main" v-if="currentTab === 0">

        <div class="module-alias input-group">
          <label for="module-alias-label">Module alias</label>
          <input type="text" class="input-field" v-model="module.alias" @change="activeForSave = !activeForSave">
        </div>

        <div class="item" v-if="!loading">
          On/Off store structure
          <ToggleCheck :value="Boolean(settings.store_structure)" @complete="changeStoreStructure"/>
        </div>

      </div>

      <div class="settings_template" v-if="currentTab === 1">

        <div
          v-for="template in templateList"
          :class="{ active: template.templateId === settings.template_id }"
          class="cart"
        >
          <span @click="settings.template_id = template.templateId">
            {{ template.name }}
          </span>
        </div>

      </div>

      <div v-if="currentTab === 2" class="custom-styles">

        <div class="preview-box">
          <select v-model="currentElement" class="select-element" @change="selectElement(currentElement)">
            <template v-for="(index, label) in style">
              <option :value="label">{{ label }}</option>
            </template>
          </select>
          <div v-inject-css="style[currentElement]">Checkout</div>
        </div>

        <div class="style-control">
          <vue-custom-scrollbar class="content-scroll">
            <StyleGenerator :input="currentStyle" :rules-exception="rules[currentElement]" @complete="changeElement"/>
          </vue-custom-scrollbar>
        </div>

      </div>

    </div>

  </div>
</template>

<script>
import InnerTab from "@/components/base/ui/InnerTab"
import ToggleCheck from "@/components/base/ui/ToggleCheck"
import { moduleUrl } from "@/helpers/general"
import {baseStyleTemplate} from "../getters"
import { injectCss } from "@/vue-directives"
import ColorPicker from "@/components/base/ui/ColorPicker"
import vueCustomScrollbar from 'vue-custom-scrollbar'
import "vue-custom-scrollbar/dist/vueScrollbar.css"
import StyleGenerator from "@/components/base/StyleGenerator"
import rulesStyleElementExclude from '../getters'

export default {
  name: "index",

  components: {
    InnerTab, ToggleCheck, ColorPicker, vueCustomScrollbar, StyleGenerator
  },

  directives: {
    injectCss
  },

  data() {
    return {
      moduleId: this.$parent.moduleId,
      settings: {},
      module: {},
      styles: {},
      tabs: [
        { name: 'Main' },
        { name: 'Templates' },
        { name: 'Styles' },
        { name: 'Color Scheme' }
      ],
      currentTab: 0,
      activeForSave: false,
      loading: true,
      currentElement: {},
      rules: rulesStyleElementExclude,
      numericRules: ['width', 'height', 'border-radius', 'border-width', 'font-size'],
    }
  },

  watch: {

    settings: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    },

    module: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    },

    style: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    },


  },

  computed: {

    templateList() {
      return [
        { templateId: 1, name: 'Default', imgUrl: '' },
        { templateId: 2, name: 'Monster' },
        { templateId: 3, name: 'Wow Template' },
      ]
    },

    style() {
      return Object.keys(this.styles).length ? this.styles : baseStyleTemplate
    },

    currentStyle() {
      return this.styles[this.currentElement]
    }

  },

  mounted() {
    window.setTitle('Settings list')
  },

  created() {
    this._loadData()
  },

  methods: {

    changeTab(index) {
      this.currentTab = index
    },

    changeElement($data) {
      this.styles[this.currentElement] = $data
    },

    changeStoreStructure(val) {
      this.notifier.warning('This function will be in the future')
    },

    prepareSendData() {
      let $data = this.styles
      for (let element in $data) {
        for (let property in $data[element]) {
          if (this.numericRules.includes(property)) {
            $data[element][property] = parseInt($data[element][property])
          }
        }
      }
      return $data
    },

    selectElement($element) {
      this.currentStyle = this.style[$element]
    },

    _saveChanges() {
      axios.patch(`${moduleUrl(this.$route)}/settings`, { styles: this.prepareSendData(), alias: this.module.alias })
        .then((res) => {
          if (res.data.success) {
            this.activeForSave = false
            this.notifier.success('Save changes')
          }
        })
    },

    mergeStyleData($data) {

      for (let style in $data) {
        if (this.style.hasOwnProperty(style)) {
          if (typeof $data[style] === "object") {
            for (let innerStyle in $data[style]) {
              this.style[style][innerStyle] = $data[style][innerStyle]
            }
          }
        }
      }
      this.styles = this._.cloneDeep(this.style)
    },

    _loadData() {

      axios.get(`${this.$parent.$parent.moduleUrl}/settings`)
          .then((res) => {

            if (res.data.settings) {
              this.settings = this._.cloneDeep(res.data.settings)
            }

            this.mergeStyleData(res.data.styles ? JSON.parse(res.data.styles.data) : baseStyleTemplate)

            this.currentElement = Object.keys(this.style)[0]

            this.module = Object.assign('', res.data.module)
          })
          .then((res) => {
            this.activeForSave = false
            this.loading = false
          })
          .catch(err => console.log(err))

    },

  }

}
</script>

<style scoped lang="scss">

.table-list-items {
  .save {
    position: absolute;
    top: 20px;
    right: 30px;
  }
  .container {
    margin-top: 50px;
    .settings_main {
      display: flex;
      flex-direction: column;
      .item {
        display: flex;
        width: 30%;
        align-items: center;
      }
    }
    .custom-styles {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      position: relative;
      .preview-box {
        align-self: center;
        align-content: center;
        align-items: center;
        width: 60%;
        display: flex;
        flex-direction: column;
        select {
          position: absolute;
          top: 0;
        }
      }
      .style-control {
        width: 40%;
        display: flex;
        flex-direction: column;
        max-height: 500px;
        section {
          width: 100%;
          display: flex;
          padding: 0 30px;
        }
        label {
          display: flex;
          flex-direction: column;
        }
      }
    }
    .settings_template {
      display: flex;
      flex-direction: row;
      justify-content: flex-start;
      flex-wrap: wrap;
      .cart {
        width: 140px;
        display: flex;
        justify-content: center;
        cursor: pointer;
        padding-top: 15px;
        font-size: 14px;
        span {
          display: flex;
          flex-direction: column;
          width: 80%;
          align-items: center;
          text-align: center;
          img {
            width: 100%;
          }
        }
        &.active {
          border: 1px solid red;
        }
      }
    }
  }

}

</style>
