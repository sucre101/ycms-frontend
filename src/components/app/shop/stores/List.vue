<template>
  <div class="main-box">

    <!--    <TopButtons-->
    <!--      :links="[-->
    <!--        {name: 'Go back', href: '/app/'+app.slug+'/shops', class: buttonClass, cond: true},-->
    <!--        {name: 'Edit structure', href: '/app/'+app.slug+'/shops/structure', class: buttonClass, cond: disableStructure, switched: true},-->
    <!--      ]"-->
    <!--    />-->

    <!--    <div class="switch-opened-structure">-->
    <!--      <label for="enable-structure">Show structure</label>-->
    <!--      <toggle-button-->
    <!--        id="enable-structure"-->
    <!--        class="ml-39"-->
    <!--        v-model="disableStructure"-->
    <!--        @drag="changeStructureView"-->
    <!--        :color="{checked: '#0997B1', unchecked: '#868686'}"-->
    <!--        :width="30"-->
    <!--        :height="20"-->
    <!--        :margin="2"-->
    <!--        :disabled="app.stores.length < 2"-->
    <!--      />-->
    <!--    </div>-->

    <div class="shops-without-structure">

      <!--      <Tree-->
      <!--        :data="app.stores"-->
      <!--        draggable="draggable"-->
      <!--        cross-tree="cross-tree"-->
      <!--      >-->
      <!--        <div slot-scope="{data, store}" style="display: flex; align-items: center">-->
      <!--          <template v-if="!data.isDragPlaceHolder">-->
      <!--            <img src="/img/ycms/icon-drag.svg" alt="" class="handle">-->
      <!--            {{ data.name }}-->
      <!--            <div class="action-group">-->
      <!--              <div class="actions" v-if="!data.default" style="width: auto; font-size: 15px;">-->
      <!--                <a-->
      <!--                  href=""-->
      <!--                  @click.prevent="setDefaultStore(data.id)"-->
      <!--                >-->
      <!--                  set default store-->
      <!--                </a>-->
      <!--              </div>-->
      <!--              <div class="actions" v-if="disableStructure">-->
      <!--                <ion-icon-->
      <!--                  v-if="!data.structure.length"-->
      <!--                  title="store without structure"-->
      <!--                  name="information-circle-outline"-->
      <!--                  style="color: darkred"-->
      <!--                />-->
      <!--                <ion-icon-->
      <!--                  v-else-->
      <!--                  :title="data.structure[0].name"-->
      <!--                  name="location-outline"-->
      <!--                />-->
      <!--              </div>-->
      <!--              <div class="actions">-->
      <!--                {{ store.id }}-->
      <!--                <ion-icon-->
      <!--                  title="edit store"-->
      <!--                  @click="goToEditStore(data.id)"-->
      <!--                  name="create-outline"/>-->
      <!--              </div>-->
      <!--              <div class="actions">-->
      <!--                <ion-icon-->
      <!--                  title="copy store"-->
      <!--                  @click="copyStore(data.id)"-->
      <!--                  name="copy-outline"/>-->
      <!--              </div>-->
      <!--              <div class="actions">-->
      <!--                <ion-icon-->
      <!--                  title="delete store"-->
      <!--                  @click="deleteStore(data.id)"-->
      <!--                  name="trash-outline"/>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </template>-->
      <!--        </div>-->
      <!--      </Tree>-->

      <draggable v-model="app.stores" @change="log">
        <div v-for="element in app.stores" :key="element.id" class="item">
          <span>
            {{ element.name }}
          </span>
          <div class="action">
            <btn
              image="/img/ycms/edit_icon.svg"
            />
          </div>
        </div>
      </draggable>

    </div>

    <!--    <div class="action-group" style="width: auto">-->
    <!--      <div class="actions" style="width: auto">-->
    <!--        <ycms-action-buttons-->
    <!--          :buttons-list="[-->
    <!--            {-->
    <!--              title: 'Add store',-->
    <!--              handler: 'eval:this.$parent.$refs.newShopModal.open()',-->
    <!--              class: 'bg-green-gradient',-->
    <!--            },-->
    <!--          ]"-->
    <!--          align="left"-->
    <!--        />-->
    <!--      </div>-->
    <!--      <div class="actions" style="width: auto">-->
    <!--        <ycms-action-buttons-->
    <!--          :buttons-list="[-->
    <!--            {-->
    <!--              title: 'Save',-->
    <!--              handler: 'eval:this.$parent.updateTree()',-->
    <!--              class: 'bg-green-gradient',-->
    <!--              enabled: false-->
    <!--            },-->
    <!--          ]"-->
    <!--          align="right"-->
    <!--        />-->
    <!--      </div>-->
    <!--    </div>-->

    <!--    <new-shop-modal-->
    <!--      ref="newShopModal"-->
    <!--      @submitted="createStore"-->
    <!--    />-->
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
