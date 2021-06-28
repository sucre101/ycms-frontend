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
            <img src="@/assets/img/drag-drop.svg" alt="" class="handle">
            {{ store.name }}
          </div>

          <div class="item-actions">
            <div class="btn-action-edit" @click="editStore(store.id)">Edit</div>
            <div @click="deleteStore(store.id)">
              <img src="@/assets/img/garbage.png" alt="">
            </div>
          </div>

        </div>
      </draggable>

    </div>

    <div class="action-group" style="width: auto">
      <div class="actions" style="width: auto">

      </div>
    </div>

    <create-store-modal
      ref="newShopModal"
      @submitted="addStore"
    />
  </div>
</template>

<script>
import draggable from 'vuedraggable'
import {moduleUrl} from "@/helpers/general"
import CreateStoreModal from "@/components/app/modules/ecommerce/Stores/modals/CreateStoreModal"

export default {
  name: "stores-list",

  components: {
    CreateStoreModal,
    draggable,
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
    this._loadData();
  },

  methods: {

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/store`)
          .then((res) => {
            this.storeList = this._.cloneDeep(res.data.stores)
            this.$nextTick(() => {
              this.$parent.loading = false
            })
          })

    },

    editStore(id) {
      this.$parent.loading = true;

      this.$router.replace({ query: { tab: 'stores', store: id } })
    },

    addStore(data) {
      axios.post(`${moduleUrl(this.$route)}/create-store`, data)
        .then((res) => {
          this.storeList = this._.cloneDeep(res.data.stores)
          console.log(this.storeList)
        })
    },

    deleteStore(id) {

      axios.post(`${moduleUrl(this.$route)}/delete-store`, { store_id: id})
          .then((res) => {
            this.storeList = this._.cloneDeep(res.data.stores)
          })

    }

  }

}
</script>

<style scoped lang="scss">
.table-list-items {

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
