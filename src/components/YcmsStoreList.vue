<template>
  <div class="main-box">

    <TopButtons
      :links="[
        {name: 'Go back', href: '/app/'+app.slug+'/shops', class: buttonClass, cond: true},
        {name: 'Edit structure', href: '/app/'+app.slug+'/shops/structure', class: buttonClass, cond: disableStructure, switched: true},
      ]"
    />

    <div class="switch-opened-structure">
      <label for="enable-structure">Show structure</label>
      <toggle-button
        id="enable-structure"
        class="ml-39"
        v-model="disableStructure"
        @drag="changeStructureView"
        :color="{checked: '#0997B1', unchecked: '#868686'}"
        :width="30"
        :height="20"
        :margin="2"
        :disabled="app.stores.length < 2"
      />
    </div>

    <div class="shops-without-structure">

      <Tree
        :data="appStores"
        draggable="draggable"
        cross-tree="cross-tree"
      >
        <div slot-scope="{data, store}" style="display: flex; align-items: center">
          <template v-if="!data.isDragPlaceHolder">
            <img src="/img/drag-drop.svg" alt="" class="handle">
            {{ data.name }}
            <div class="action-group">
              <div class="actions" v-if="!data.default" style="width: auto; font-size: 15px;">
                <a
                  href=""
                  @click.prevent="setDefaultStore(data.id)"
                >
                  set default store
                </a>
              </div>
              <div class="actions" v-if="disableStructure">
                <ion-icon
                  v-if="!data.structure.length"
                  title="store without structure"
                  name="information-circle-outline"
                  style="color: darkred"
                />
                <ion-icon
                  v-else
                  :title="data.structure[0].name"
                  name="location-outline"
                />
              </div>
              <div class="actions">
                {{ store.id }}
                <ion-icon
                  title="edit store"
                  @click="goToEditStore(data.id)"
                  name="create-outline"/>
              </div>
              <div class="actions">
                <ion-icon
                  title="copy store"
                  @click="copyStore(data.id)"
                  name="copy-outline"/>
              </div>
              <div class="actions">
                <ion-icon
                  title="delete store"
                  @click="deleteStore(data.id)"
                  name="trash-outline"/>
              </div>
            </div>
          </template>
        </div>
      </Tree>

    </div>

    <div class="action-group" style="width: auto">
      <div class="actions" style="width: auto">
        <ycms-action-buttons
          :buttons-list="[
            {
              title: 'Add store',
              handler: 'eval:this.$parent.$refs.newShopModal.open()',
              class: 'bg-green-gradient',
            },
          ]"
          align="left"
        />
      </div>
      <div class="actions" style="width: auto">
        <ycms-action-buttons
          :buttons-list="[
            {
              title: 'Save',
              handler: 'eval:this.$parent.updateTree()',
              class: 'bg-green-gradient',
              enabled: false
            },
          ]"
          align="right"
        />
      </div>
    </div>

    <new-shop-modal
      ref="newShopModal"
      @submitted="createStore"
    />
  </div>
</template>

<script>
  import {DraggableTree} from 'vue-draggable-nested-tree'
  import YcmsTopButtons from './YcmsTopButtons'

  export default {
    name: "ycms-store-list",

    components: {
      Tree: DraggableTree,
      TopButtons: YcmsTopButtons
    },

    data() {
      return {
        appStores: [],
        newStore: {},
        storeList: {},
        disableStructure: false,
        buttonClass: 'small-rounded-btn content-top-button mr-15',
      }
    },

    props: ['app', 'currencies'],

    created() {
      this.disableStructure = this.app.stores.length < 2 || this.app.settings.store_structure
      this.appStores = _.cloneDeep(this.app.stores)
    },

    methods: {
      updateTree() {

        const stores = []

        _.forEach(this.appStores, (element) => {
          stores.push(element.id)
        })

        axios.post('/app/' + this.app.slug + '/shops/update-order', stores)
          .then((res) => {
            this.notifier.success('tree rebuild success')
          });
      },

      addStore(structureId) {
        if (structureId) {
          this.newStore.structureId = structureId
        }
        this.$refs.newShopModal.open()
      },

      createStore(data) {
        data.structureId = this.newStore.structureId

        axios.post('/app/' + this.app.slug + '/shops/create-store', {data: data, structure: this.disableStructure})
          .then((res) => {
            this.appStores = _.cloneDeep(res.data.stores)
          })
      },

      goToEditStore(id) {
        return window.location.href = '/app/' + this.app.slug + '/shops/' + id;
      },

      copyStore(storeId) {
        axios.post('/app/' + this.app.slug + '/shops/copy-store', {id: storeId})
          .then((res) => {
            this.appStores = _.cloneDeep(res.data.stores)
            this.notifier.success('Store copied')
          })
      },

      deleteStore(storeId) {
        axios.post('/app/' + this.app.slug + '/shops/delete-store', {id: storeId})
          .then((res) => {
            this.appStores = _.cloneDeep(res.data.stores)
            this.notifier.success('Store Deleted')
          })
      },

      changeStructureView() {

        this.disableStructure = !!this.disableStructure
        this.$emit('updateTop')

        axios.post('/app/' + this.app.slug + '/shop-settings/update', {store_structure: this.disableStructure})
          .then((res) => {
            if (res.data.success) {
              this.notifier.success(this.disableStructure ? 'structure enabled' : 'structure disabled')
            }
          })
      },

      setDefaultStore(storeId) {
        axios.post('/app/' + this.app.slug + '/shops/default-store', {id: storeId})
          .then((res) => {
            this.appStores = _.cloneDeep(res.data.stores)
          })
      },
    }
  }
</script>

<style lang="scss">


  .main-box {
    position: relative;
  }

  .action-group {
    display: flex;
    flex-direction: row;
    margin-left: auto;
    width: auto !important;
    font-size: 21px;
    padding: 5px;
  }

  .actions {

    color: #0997b1;
    margin-left: 10px;
    width: 30px;
    font-size: 21px;
  }

  .he-tree {
    border: 1px solid #ccc;
    padding: 20px;
  }

  .tree-node-inner {
    padding: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
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

  .switch-opened-structure {
    display: flex;
    width: auto;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    float: right;
    top: 8px;
    right: 45px;
    position: absolute;
  }

  .shops-without-structure {
    clear: both;
    margin-right: 20px;

    .handle {
      padding: 0 10px;
    }
  }
</style>
