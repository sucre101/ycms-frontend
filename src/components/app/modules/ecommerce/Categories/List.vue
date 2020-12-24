<template>
  <div class="categories-list-table">
    <h4>Store categories</h4>

    <Tree :data="categoryList" draggable="draggable" class="list-group" cross-tree="cross-tree" @change="rebuildTree">
      <div slot-scope="{data, store, vm}">
        <template v-if="!data.isDragPlaceHolder">
          <div class="node-group list-group-item">

            <div class="item-title">
              <img src="/img/drag-drop.svg" alt="" class="handle">
              {{ data.name }}
            </div>

            <button @click.prevent="editCategory(data)">
              Edit
            </button>

          </div>
        </template>
      </div>
    </Tree>

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
      module: {}
    }
  },

  created() {
    this.module.id = this.$parent.$parent.moduleId
    this.loadData()
  },

  methods: {

    rebuildTree(element, target) {

      let cat = {
        id: element.id,
        parent_id: element.parent.id,
        target: target.getPureData(),
      }

      axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/categories/rebuild`, cat)
        .then((res) => {
          // console.log(res)
        })

    },

    loadData() {

      // console.log(this.$parent)

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/categories`)
          .then((res) => {
            this.categoryList = this._.cloneDeep(res.data.categories)
            // console.log(this.categoryList)
          })
    },

    editCategory(element) {
      this.$parent.editCategory = true
      this.$router.replace({ query: { tab: 'categories', category: element.id } })
    }

  }

}
</script>

<style scoped lang="scss">

.categories-list-table {
  width: 70%;
  background-color: white;
  padding: 15px 50px;

  h4 {
    text-align: center;
    font-size: 10px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.4;
    letter-spacing: 2px;
    color: #aaaeb3;
    margin: 15px 0;
  }
  .list-group-item {
    display: flex;
  }
}

</style>
