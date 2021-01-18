<template>
  <div class="category-table">
    <InnerTab :items="[
        { name: 'General' },
        { name: 'Specs' }
    ]"
        @change="selectTab"
    />

    <div class="category-tabs">

      <div class="tab-general" v-if="currentTab === 0">
        tab general
      </div>

      <div class="tab-specs" v-if="currentTab === 1">
        <div class="spec-group-list">
          <div
              v-for="(specGroup, index) in category.spec_groups"
              class="spec-group"
              :class="{ active:  index === currentActive }"
              @mouseenter="currentActive = index"
          >
            <div class="btn-action">
              <span @click="addNewSpec(index)">Add</span>
              <span @click="deleteSpecGroup(index)">Remove</span>
            </div>
            {{ specGroup.name }}
            <div class="spec" v-for="(spec, specIndex) in specGroup.specs">
              {{ spec.name }}
              <span @click="removeSpec(index, specIndex)">remove</span>
            </div>
            <div class="inputSpec" v-if="newSpec && index === showSpecIndex">
              <input
                  type="text"
                  v-model.trim="newSpecName"
                  @keyup.enter="addNewSpec(index)"
                  @keyup.esc="newSpec = false"
                  ref="inputSpec"
              >
              <span @click="newSpec = false">Cancel</span>
              <span @click="addNewSpec(index)">save</span>
            </div>
          </div>
        </div>
        <ycms-action-buttons
            v-if="!newSpecGroup"
            :buttons-list="[
                {
                  title: 'Add group',
                  handler: 'eval:this.$parent.addSpecGroupInput()',
                  class: 'bg-green-gradient'
                },
              ]"
            align="left"
        />
        <div v-if="newSpecGroup" class="new-spec-input">
          <input
              type="text"
              v-model.trim="newSpecGroupName"
              @keyup.enter="setSpecDataName"
              @keyup.esc="newSpecGroup = false"
              ref="inputSpecGroup"
          />
          <span @click="newSpecGroup = false">X</span>
          <span @click="setSpecDataName">Enter</span>
        </div>
      </div>
    </div>

    <div class="save-button" @click="saveAllData" v-if="activeForSave">
      <img src="/img/ycms/exit_icon.svg" title="save product">
    </div>

  </div>
</template>

<script>
import InnerTab from "@/components/base/ui/InnerTab";
import YcmsActionButtons from "@/components/YcmsActionButtons";

export default {
  name: "category",

  components: {
    InnerTab, YcmsActionButtons
  },

  data() {
    return {
      currentTab: 0,
      category: {},
      categoryId: null,
      currentActive: 0,
      newSpecGroupName: '',
      newSpecName: '',
      newSpec: false,
      newSpecGroup: false,
      showSpecIndex: 0,
      activeForSave: false,
    }
  },

  watch: {
    $route(to, from) {

      if (!to.query.hasOwnProperty('category')) {
        this.$parent.editCategory = false
      }
    },

    category: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    },
  },

  created() {
    this.categoryId = this.$route.query.category

    this.loadData()
  },

  mounted() {
    window.setTitle('Category edit')
  },


  methods: {

    selectTab(tab) {
      this.currentTab = tab
    },

    addSpecGroupInput() {
      this.newSpecGroup = true
      this.$nextTick(() => {
        this.$refs.inputSpecGroup.focus()
      })
    },

    setSpecDataName() {
      this.category.spec_groups.push({ name: this.newSpecGroupName, specs: [] })
      this.newSpecGroupName = ''
      this.newSpecGroup = false;
    },

    deleteSpecGroup(index) {
      this.category.spec_groups.splice(index, 1)
    },

    addNewSpec(index) {

      this.newSpec = true
      this.showSpecIndex = index

      this.$nextTick(() => {
        this.$refs.inputSpec[0].focus()
      })

      if (this.newSpecName.length > 0) {
        this.category.spec_groups[index].specs.push({ name: this.newSpecName })
        this.newSpec = false
        this.newSpecName = ''
      }

    },

    removeSpec(spIndex, sIndex) {
      this.category.spec_groups[spIndex].specs.splice(sIndex, 1)
    },

    saveAllData() {
      axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.$parent.$parent.moduleId}/category/update-category`, this.category)
        .then((res) => {
          console.log(res)
        })
    },

    loadData() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.$parent.$parent.moduleId}/category/${this.categoryId}`)
        .then((res) => {
          this.category = this._.cloneDeep(res.data.category)
        })
          .then( res => this.activeForSave = false)

    }

  }

}
</script>

<style scoped lang="scss">
.category-table {
  width: 70%;
  background-color: white;
  padding: 15px 50px;
  position: relative;
  .category-tabs {
    margin-top: 50px;

    .spec-group-list {
      .spec-group.active {
        .spec {
          display: flex;
          justify-content: space-between;
        }
        .spec:hover {
          background-color: #f0f3f6;
        }
      }
      .spec-group {
        display: flex;
        flex-direction: column;
        border: 4px solid #f0f3f6;
        width: 48%;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        position: relative;
        margin-bottom: 20px;
        .spec {
          font-weight: normal;
          font-size: 16px;
          padding-left: 15px;
          cursor: pointer;
          display: none;
          span {
            padding-right: 40px;
          }
        }
        .btn-action {
          position: absolute;
          right: 0;
          span {
            font-size: 12px;
            font-weight: normal;
            margin: 0 15px;
          }
        }
      }
    }
  }
  .new-spec-input {
    display: flex;
    align-items: center;
    margin-top: 20px;
    width: 35%;
    justify-content: space-between;
    input {
      padding: 8px 16px 9.5px 25px;
      border-radius: 22px;
      border: solid 1px #868686 !important;
    }
    span {
      border: 1px solid;
      cursor: pointer;
    }
  }
  .inputSpec {
    input {
      border-radius: 22px;
      border: solid 1px #868686 !important;
      font-size: 14px;
      padding: 3px 10px 3.5px 13px;
    }
  }
  .save-button {
    position: absolute;
    top: 25px;
    right: 20px;
  }
}
</style>
