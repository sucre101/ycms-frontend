<template>
  <div class="table-list-items">
    <h4>Your stores</h4>

    <div class="shops-without-structure" v-if="storeList.length">

      <draggable
        :list="storeList"
        :disabled="false"
        class="list-group"
        ghost-class="ghost"
        @start="dragging = true"
        @end="dragging = false"
      >
        <div
          class="list-group-item"
          v-for="store in storeList"
          :key="store.id"
        >
          <div class="item-title">
            <img src="/img/drag-drop.svg" alt="" class="handle">
            {{ store.name }}
          </div>

          <div class="item-actions">
            <div class="btn-action-edit" @click="editStore(store.id)">Edit</div>
            <div @click="deleteStore(store.id)">
              <img src="/img/garbage.png" alt="">
            </div>
          </div>

        </div>
      </draggable>

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
          align="right"
        />
      </div>
    </div>

    <new-shop-modal
      ref="newShopModal"
      @submitted="addStore"
    />
  </div>
</template>

<script>
import NewShopModal from "../../../../eCommerce/NewShopModal";
import draggable from 'vuedraggable'
import YcmsActionButtons from "../../../../YcmsActionButtons";

export default {
  name: "stores-list",

  components: {
    NewShopModal,
    draggable,
    YcmsActionButtons
  },

  data() {
    return {
      storeList: [],
      userModuleId: Number(this.$route.params.moduleId),
      currencies: [
        { id: 1, name: 'EUR' },
        { id: 2, name: 'USD' },
        { id: 3, name: 'RUB' },
      ],
    }
  },

  created() {
    this.loadData();
  },

  mounted() {
    window.setTitle('Store list')
  },

  methods: {

    loadData() {

      axios.post('/ecommerce/store-list', {uModuleId: this.userModuleId})
        .then((res) => {
          this.storeList = this._.cloneDeep(res.data.stores)
        })
        .then(res => this.$parent.loading = false)

    },

    editStore(id) {
      this.$parent.loading = true;

      this.$router.replace({ query: { tab: 'stores', store: id } })
    },

    addStore(data) {

      axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.$parent.$parent.moduleId}/create-store`, data)
        .then((res) => {
          this.storeList = this._.cloneDeep(res.data.stores)
        })

    },

    deleteStore(id) {

      axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.$parent.$parent.moduleId}/delete-store`, { store_id: id})
          .then((res) => {
            this.storeList = this._.cloneDeep(res.data.stores)
          })

    }

  }

}
</script>

<style scoped lang="scss">
.table-list-items {
  width: 70%;
  background-color: white;
  padding: 15px 50px;

  .list-group {
    display: flex;
    flex-direction: column;

    .list-group-item {
      display: flex;
      flex-direction: row;
      padding: 10px 15px;
      align-items: center;
      justify-content: space-between;

      .item-title {
        width: 40%;
        max-width: 400px;
        display: flex;
        justify-content: flex-start;

        .handle {
          cursor: move;
          padding-right: 10px;
        }
      }
      .item-actions {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        width: 15%;

        .btn-action-edit {
          width: 100px;
          height: 30px;
          border-radius: 16px;
          background-color: #0997b1;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          cursor: pointer;
        }
      }
    }
  }
}
</style>
