<template>
  <div class="table-list-items">
    <h4>Store categories</h4>

    <Tree :data="categoryList" draggable="draggable" class="list-group" cross-tree="cross-tree" @change="rebuildTree">
      <div slot-scope="{data, store, vm}">
        <template v-if="!data.isDragPlaceHolder">
          <div class="node-group item">

            <div class="item-title">
              <img src="/img/drag-drop.svg" alt="" class="handle">
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

    <div class="btn-action blue save" @click="saveChange" v-if="activeForSave">
      Save
    </div>

  </div>
</template>

<script>
import draggable from "vuedraggable";
import {DraggableTree} from 'vue-draggable-nested-tree'

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
      changed: null
    }
  },

  created() {
    this.module.id = this.$parent.$parent.moduleId
    this.loadData()
  },

  mounted() {
    window.setTitle('Category list')
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

    saveChange() {
      axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/categories/rebuild`, this.changed)
        .then((res) => {
          if (res.data.success) {
            this.notifier.success('Categories save')
          }
        })
        .then( res => this.activeForSave = false )
    },

    loadData() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/categories`)
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
}

</style>
