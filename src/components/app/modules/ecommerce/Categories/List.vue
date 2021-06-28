<template>
  <div class="table-list-items">
    <h4>Store categories</h4>

    <Tree :data="categoryList" draggable="draggable" class="list-group" cross-tree="cross-tree" @change="rebuildTree">
      <div slot-scope="{data, store, vm}">
        <template v-if="!data.isDragPlaceHolder">
          <div class="node-group item">

            <div class="item-title">
              <img src="@/assets/img/drag-drop.svg" alt="" class="handle">
              {{ data.name }}
            </div>

            <div class="item-actions">
              <div class="btn-action blue" @click="editCategory(data)">Edit</div>
              <div class="btn-action delete" @click=""></div>
            </div>

          </div>
        </template>
      </div>
    </Tree>

    <div class="input-group" v-if="inputField">
      <input type="text" class="input-field" v-model.trim="newCategory" @keypress.enter="_createCategory">
      <div>
        <i class="fas fa-times" @click="newCategory = null"></i>
        <i class="fas fa-plus" @click="_createCategory"></i>
      </div>
    </div>

    <div class="rounded-circle green-bg-gradient" @click="inputField = !inputField">
      <i class="fas fa-plus"></i>
    </div>

    <div class="btn-action blue save" @click="_saveChange" v-if="activeForSave">
      Save
    </div>

  </div>
</template>

<script>
import draggable from "vuedraggable";
import {DraggableTree} from 'vue-draggable-nested-tree'
import {moduleUrl} from "@/helpers/general";

export default {

  name: "category-list",

  components: {
    draggable, 'Tree': DraggableTree
  },

  data() {
    return {
      categoryList: [],
      module: {},
      activeForSave: false,
      changed: null,
      newCategory: null,
      inputField: false,
    }
  },

  created() {
    this.module.id = this.$parent.$parent.moduleId
    this._loadData()
  },

  methods: {

    rebuildTree(element, target) {

      this.changed = {}

      this.changed = {
        id: element.id,
        parent_id: element.parent.id,
        target: target.getPureData(),
      }

      this.activeForSave = true
    },

    _createCategory() {

      axios.post(`${moduleUrl(this.$route)}/category`, { name: this.newCategory })
        .then((res) => {
          if (res.data.success) {
            this.categoryList = this._.cloneDeep(res.data.categories)
            this.inputField = false
            this.newCategory = null
            this.activeForSave = false
          }
        })

    },

    _saveChange() {
      axios.patch(`${moduleUrl(this.$route)}/category`, this.changed)
        .then((res) => {
          if (res.data.success) {
            this.notifier.success('Categories save')
          }
        })
        .then( res => this.activeForSave = false )
    },

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/category`)
        .then((res) => {
          this.categoryList = this._.cloneDeep(res.data.categories)
        })
        .then(res => this.activeForSave = false)

    },

    editCategory(element) {
      this.$parent.editCategory = true
      this.$router.replace({ query: { tab: 'categories', category: element.id } })
    }

  }

}
</script>

<style scoped lang="scss">

.table-list-items {
  .list-group-item {
    display: flex;
  }
  .btn-action.save {
    position: absolute;
    top: 20px;
    right: 30px;
  }
  .input-group {
    width: 30%;
    position: relative;
    input {
      width: auto;
      padding: 7px 50px 7px 22px;
    }
    div {
      position: absolute;
      top: 13px;
      right: 0;
      width: 60px;
      display: flex;
      justify-content: space-evenly;
      color: #0997b1;
      font-size: 12px;
      i {
        cursor: pointer;
      }
    }
  }
}

</style>
