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
            <img src="@/img/uploads/template-1.png" alt="">
            {{ template.name }}
          </span>
        </div>

      </div>

      <div v-if="currentTab === 2" class="custom-styles">
<!--        <div>-->
<!--          <label for="">Checkout button</label>-->
<!--          <div class="color-pick">-->

<!--          </div>-->
<!--          <div :style="checkoutBtnCss">result</div>-->
<!--          <input type="range" min="1" max="20" step="1" :value="styles.checkout_button['border-radius']" @change="changeWidth">-->
<!--        </div>-->
      </div>

    </div>

  </div>
</template>

<script>
import InnerTab from "@/components/base/ui/InnerTab";
import ToggleCheck from "@/components/base/ui/ToggleCheck";
import { moduleUrl } from "@/helpers/general";

export default {
  name: "index",

  components: {
    InnerTab, ToggleCheck
  },

  data() {
    return {
      moduleId: this.$parent.moduleId,
      settings: {},
      module: {},
      styles: {
        checkout_button: []
      },
      tabs: [
        { name: 'Main' },
        { name: 'Templates' },
        { name: 'Styles' },
        { name: 'Color Scheme' }
      ],
      currentTab: 0,
      activeForSave: false,
      loading: true,
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
    }

  },

  computed: {

    templateList() {
      return [
        { templateId: 1, name: 'Default', imgUrl: '' },
        { templateId: 2, name: 'Monster' },
        { templateId: 3, name: 'Wow Template' },
      ]
    },

    // checkoutBtnCss() {
    //
    //   //
    //   let data = ''
    //   //
    //   let arg = this._.cloneDeep(this.styles.checkout_button)
    //
    //   console.log(this.styles.checkout_button)
    //
    //   this._.forEach(arg, (k) => {
    //     console.log(k)
    //   })
    //   // console.log(this.styles.checkout_button)
    //   // this.styles.checkout_button.forEach( (val, key) => {
    //   //
    //   //   console.log(val, key)
    //   //
    //   // } )
    //
    //   return 'background-color: aqua'
    //
    // }

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

    changeWidth(val) {
      // console.log(val)
    },

    changeStoreStructure(val) {
      this.notifier.warning('This function will be in the future')
      // this.settings.store_structure = false
    },

    _saveChanges() {

      axios.patch(`${moduleUrl(this.$route)}/settings`, { settings: this.settings, alias: this.module.alias })
        .then((res) => {
          if (res.data.success) {
            this.activeForSave = false
            this.notifier.success('Save changes')
          }
        })
    },

    _loadData() {

      axios.get(`${this.$parent.$parent.moduleUrl}/settings`)
          .then((res) => {

            if (res.data.settings) {
              this.settings = this._.cloneDeep(res.data.settings)
            }

            this.module = Object.assign('', res.data.module)

            // let data = JSON.parse(res.data.styles.data)
            //
            // for (const [key, value] of Object.entries(data)) {
            //
            //   for (const [k, v] of Object.entries(value)) {
            //     // console.log(k, v)
            //     this.styles[key][k] = v
            //   }
            // }

            // console.log(this.styles.checkout_button)

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
