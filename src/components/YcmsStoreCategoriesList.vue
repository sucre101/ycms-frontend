<template>
  <div>
    <Tree :data="categories" draggable="draggable" cross-tree="cross-tree" @change="rebuildTree">
      <div slot-scope="{data, store, vm}">
        <template v-if="!data.isDragPlaceHolder">
          <div class="node-group">
            <b v-if="data.children && data.children.length" @click="store.toggleOpen(data)" class="icons-group">
              <ion-icon v-if="data.open" name="remove-outline"></ion-icon>
              <ion-icon v-else name="add-outline"></ion-icon>
            </b>
            <a>{{ data.name }}</a>
            <div class="action-group">
              <div class="actions ">
                <ion-icon @click="goToEdit(data.id)" name="create-outline"></ion-icon>
              </div>
              <div class="actions ">
                <ion-icon @click="duplicateApp(data.id)" name="copy-outline"></ion-icon>
              </div>
              <div class="actions ">
                <ion-icon @click="showDeleteWarning(data.id)" name="trash-outline"></ion-icon>
              </div>
            </div>
          </div>
        </template>
      </div>
    </Tree>

    <sweet-modal
      class="modal"
      ref="modulesModal"
      width="915"
      overlay-theme="dark"
    >
      <h6>Add Category</h6>
      <input
        v-model="categoryName"
        class="rounded-input"
        width="100%"
        type="text"
        placeholder="Name of Category"
      />
      <ycms-action-buttons
        :buttons-list="[
        {
          title: 'CREATE',
          handler: 'eval:this.$parent.$parent.createCategory()',
          class: 'bg-green-gradient',
          action: '/app/' + appSlug + '/'
        }
      ]"
        align="right"
      />
    </sweet-modal>
    <ycms-action-buttons
      :buttons-list="[
        {
          title: 'ADD CATEGORY',
          handler: 'eval:this.$parent.showModulesModal()',
          class: 'bg-green-gradient'
        }
      ]"
      align="right"
    />
  </div>
</template>

<script>
  // import { diff, addedDiff, deletedDiff, updatedDiff, detailedDiff } from 'deep-object-diff';
  import {DraggableTree} from 'vue-draggable-nested-tree'

  export default {
    components: {
      Tree: DraggableTree
    },
    data() {
      return {
        categories: this.categoriesList,
        newPageModule: null,
        homeWarningShown: false,
        categoryName: '',
        toDelete: [],
        toRestore: [],
        deletePermanently: [],
        markedForDeletion: null,
        awaitingIcon: {},
        debug: true,
      }
    },

    computed: {},

    props: {
      categoriesList: Array,
      appId: String,
      appSlug: String,
      parentPage: Number,
    },

    methods: {
      goToEdit(id) {
        window.location.href="/app/"+this.appSlug+"/shop-categories/"+id+"/edit"
      },
      showModulesModal() {
        this.$refs.modulesModal.open()
      },
      createCategory() {
        post('/app/'+ this.appSlug +'/shop-categories/new-category', {
          app_id: this.appId,
          name: this.categoryName,
          parent_id: this.parentPage,
        })
        .then(res => {
              this.categories.push(res.category)
              this.$refs.modulesModal.close()
              this.notifier.success('category added success')
            }
        )
      },
      duplicateApp(id) {
        post('/app/'+ this.appSlug +'/shop-categories/duplicate', {
          id: id,
        })
        .then(res => {
          this.categories = res.categories
          this.notifier.success('tree duplicated success')
        })
      },
      rebuildTree(element, targetTree) {

        post('/app/'+ this.appSlug +'/shop-categories/rebuild', {
          id: element.id,
          parent_id: element.parent.id,
          target: targetTree.getPureData(),
        } )
        .then(res => {
          this.notifier.success('tree rebuild success')
        })
      },
      showDeleteWarning(id){
        this.markedForDeletion = id
        this.notifier.confirm('Are you sure?', this.deleteCategory)
      },
      deleteCategory() {
        post('/app/'+ this.appSlug +'/shop-categories/delete', {
          id: this.markedForDeletion,
        })
        .then(res => {
          this.categories = res.categories
          this.notifier.success('category deleted success')
          this.categoryName = ''
        })
      },
    },
    mounted() {
      this.$root.$on('ls-change', (name, val) => {
        if (name == 'newPageModule') this.newPageModule = val
      })
    },
  }
</script>

<style lang="scss" scoped>

  .node-group {
    display: flex;
    flex-direction: row;
    margin-left: auto;
    min-width: 100px;
    font-size: 21px;
    padding: 5px;
    /*border: 1px solid #ccc;*/
    cursor: pointer;
  }

  .action-group {
    display: flex;
    flex-direction: row;
    margin-left: auto;
    min-width: 100px;
    font-size: 21px;
    padding: 5px;
  }

  .actions {

    color: #0997b1;
    margin-left: 10px;
    width: 30px;
    font-size: 21px;
  }

  .category-list {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin: 0 39px 0 0;
  }

  .category-list-item {

    justify-content: flex-start;
    width: 100%;
    height: 56px;
    padding: 0 21px;
    background-color: rgba(#868686, .06);
    border-radius: 5px;
    margin-bottom: 5px;

    .icon-container {

      background-image: linear-gradient(45deg, #2ccae6, #0997b1);
      box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
      width: 31px;
      height: 31px;
      border-radius: 50%;
      color: white;
      font-size: 21px;
      margin-left: 32px;
    }

    .title {
      justify-content: flex-start;
      width: 40%;
      height: 31px;
      font-size: 14px;
      font-weight: 300;
      margin-left: 57px;
      padding-left: 14px;
    }

    .module {

      justify-content: flex-start;
      background-color: #868686;
      color: white;
      width: 158px;
      height: 31px;
      border-radius: 16px;
      font-size: 14px;
      font-weight: 300;
      margin-left: 57px;
      padding-left: 14px;
    }

    .vue-js-switch {
      margin-bottom: 0;
    }

    .v-switch-core {
      background-color: #0997b1 !important;
    }

    img {
      cursor: pointer;
    }

    .restore-block {

      flex: 1;
    }
  }

  .action-buttons-w-switch {
    display: flex;

    .switch-block {
      margin-top: 42.5px;

      justify-content: flex-start;

      .vue-js-switch {
        margin: 0 21px 0 0;
      }
    }

    .bottom-buttons {
      flex: 1;
    }
  }

  .icons-group {
    margin-top: 4px;
  }

  .he-tree {
    /*border: 1px solid #ccc;*/
    padding: 20px;
  }

  .tree-node {
  }

  .tree-node-inner {
    padding: 5px;
    /*border: 1px solid #ccc;*/
    cursor: pointer;
  }

  .draggable-placeholder {
  }

  .draggable-placeholder-inner {
    border: 1px dashed #0088F8;
    box-sizing: border-box;
    background: rgba(0, 136, 249, 0.09);
    color: #0088f9;
    text-align: center;
    padding: 0;
    display: flex;
    align-items: center;
  }

</style>
