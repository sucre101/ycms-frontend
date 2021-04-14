<template>
  <div class="category-table">
    <InnerTab :items="[
        { name: 'General' },
        { name: 'Parent' },
        { name: 'Specs' },
        { name: 'Media' }
    ]"
        @change="selectTab"
    />

    <div class="btn-action blue save" @click="_saveChanges" v-if="activeForSave">
      Save
    </div>

    <vue-custom-scrollbar class="content-scroll">

      <div class="category-tabs">

        <div class="tab-general" v-if="currentTab === 0">

          <div class="input-group">
            <label for="">Title</label>
            <input type="text" class="input-field" v-model="category.name">
          </div>

          <div class="input-group textfield">
            <label for="">Description</label>
            <ckeditor :editor="editor" v-model="category.description"></ckeditor>
          </div>

        </div>

        <div class="tab-parent" v-if="currentTab === 1">
          <div class="input-group">
            <label for="">Parent category</label>
            <select v-model="category.parent_id">
              <template v-for="category in list">
                <option :value="category.id">{{ category.name }}</option>
              </template>
            </select>
          </div>
        </div>

        <div class="tab-specs" v-if="currentTab === 2">
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
          <div class="ycms-button bg-green-gradient" @click="addSpecGroupInput" v-if="!newSpecGroup">
            Add group
          </div>
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

        <div class="tab-media" v-if="currentTab === 3">

          <div class="input-group category-icon" @click="openFileManager">
            <label for="">Icon</label>
            <img :src="category.icon ? `@/img/${$store.getters.currentUser.user_folder}/${category.icon}` : '@/img/Group 260.svg'" alt="" >
          </div>

        </div>
      </div>
    </vue-custom-scrollbar>

  </div>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import InnerTab from "@/components/base/ui/InnerTab";
import YcmsActionButtons from "@/components/YcmsActionButtons";
import {moduleUrl} from "@/helpers/general";
import FileManager from "@/components/base/filemanager/FileManager";
import vueCustomScrollbar from 'vue-custom-scrollbar'
import "vue-custom-scrollbar/dist/vueScrollbar.css"

export default {
  name: "category",

  components: {
    InnerTab, YcmsActionButtons, ckeditor: CKEditor.component, FileManager, vueCustomScrollbar
  },

  data() {
    return {
      editor: ClassicEditor,
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
      list: []
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

    this._loadData()

    this.$root.$on('set::file', this.setImageCategory)
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

    changeParent(id) {
      this.category.parent_id = id
    },

    setImageCategory(item) {
      console.log(item)
      this.category.icon = item.name
    },

    openFileManager() {
      this.$root.$emit('fmanager::open', true)
    },

    _saveChanges() {
      axios.patch(`${moduleUrl(this.$route)}/category/${this.category.id}`, this.category)
        .then((res) => {
          this.notifier.success('Category save')
        })
    },

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/category/${this.categoryId}`)
        .then((res) => {
          this.category = this._.cloneDeep(res.data.category)

          if (this.category.description === null) {
            this.category.description = ''
          }

          let rootArr = [ { name: 'Root', id: null } ]
          this.list = this._.concat(rootArr, res.data.list)
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
  .content-scroll {
    margin-top: 50px;
  }
  .category-tabs {
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
            color: black;
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
  .save {
    position: absolute;
    top: 25px;
    right: 20px;
  }
}
.input-group {
  &.textfield {
    max-width: 800px;
  }
}
.category-icon {
  cursor: pointer;
}
</style>
