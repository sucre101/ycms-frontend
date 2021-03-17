<template>
  <div class="table-list-items">

    <InnerTab
      :items="tabs"
      @change="changeTab"
    />

    <div class="btn-action blue save" @click="_saveChanges" v-if="activeForSave">Save</div>

    <div class="container">

      <div class="settings_main" v-if="currentTab === 0">

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
            <img src="/img/uploads/template-1.png" alt="">
            {{ template.name }}
          </span>
        </div>

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
      tabs: [
        { name: 'Main' },
        { name: 'Templates' },
        { name: 'Styles' },
        { name: 'Color Scheme' }
      ],
      currentTab: 0,
      activeForSave: false,
      loading: true
    }
  },

  watch: {

    settings: {
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

    changeStoreStructure(val) {
      this.settings.store_structure = val.value
    },

    _saveChanges() {

      axios.patch(`${moduleUrl(this.$route)}/settings`, this.settings)
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
            this.settings = this._.cloneDeep(res.data.settings)
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
