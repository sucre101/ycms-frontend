<template>
  <div class="main-box">

    <div class="shops-without-structure">

      <draggable v-model="app.stores" @change="log">
        <div v-for="element in app.stores" :key="element.id" class="item">
          <span>
            {{ element.name }}
          </span>
          <div class="action">
            <btn
              image="@/assets/img/ycms/edit_icon.svg"
            />
          </div>
        </div>
      </draggable>

    </div>
  </div>
</template>

<script>
import {DraggableTree} from 'vue-draggable-nested-tree'
import NewShopModal from "../../../eCommerce/NewShopModal";
import draggable from 'vuedraggable'
import ActionBtn from "../../../base/ui/ActionBtn";

export default {
  name: "storeList",

  components: {
    Tree: DraggableTree,
    'new-shop-modal': NewShopModal,
    draggable: draggable,
    'btn': ActionBtn
  },

  data() {
    return {
      stores: [],
      app: {},
      disableStructure: false
    }
  },

  created() {
    this.app = this._.cloneDeep(this.$root.$children[0].app)
  },

  mounted() {
    console.log(axios.defaults)
  },

  destroyed() {
    this.app = {}
  },

  methods: {

    log(one, two) {
      console.log(one, two)
    },

    setDefaultStore() {

    },

    deleteStore() {

    },

    goToEditStore() {

    },

    copyStore() {

    }

  }


}
</script>

<style scoped lang="scss">
.shops-without-structure {
  .item {
    display: flex;
    width: 40%;
  }
}
</style>
