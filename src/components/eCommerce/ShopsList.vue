<template>
<div class="shops-list">
  <div class="shops-list-item" v-for="shop in shops" :key="shop.id">
    <div style="width: 21px"></div>
    <div class="title">
      <a :href="`/app/${appSlug}/shops/${shop.id}`">{{ shop.name }}</a>
    </div>

    <div class="buttons-block">
      <a
        class="small-rounded-btn blue-bg"
        :href="`/app/${appSlug}/shops/${shop.id}`"
      >
        Edit
      </a>

      <div
        class="small-rounded-btn green-bg"
        @click="restore(shop)"
      >
        Duplicate
      </div>
    </div>

    <toggle-button
      v-model="shop.active"
      :color="{checked: '#0997B1', unchecked: '#868686'}"
      :width="51"
      :height="31"
      :margin="2"
    />

    <img
      class="ml-auto"
      src="/img/garbage.png"
      alt="garb"
      @click="showDeletionWarning(shop)"
    >
  </div>

  <new-shop-modal @submitted="createShop" ref="newShopModal"></new-shop-modal>

  <ycms-action-buttons
    :buttons-list="[
      {
        title: 'ADD SHOP',
        handler: 'eval:this.$parent.$refs.newShopModal.open()',
        class: 'bg-green-gradient'
      },
    ]"
    align="right"
  />
</div>
</template>

<script>
export default {
  name: 'shops-list',

  data() {
    return {
      shops: this._shops,
      markedForDeletion: null,
      debug: true,
    }
  },

  computed: {
  },

  props: {
    _shops: Array,
    appSlug: String,
    currencies: Array,
  },

  methods: {
    createShop(shop) {
      post('/app/'+this.appSlug+'/shops/create', shop).then(shop => this.shops.push(shop))
    },
    showDeletionWarning(shop){
      this.markedForDeletion = shop
      this.notifier.confirm('Are you sure?', this.deleteShop)
    },
    deleteShop() {
      post('/app/'+this.appSlug+'/shops/delete', this.markedForDeletion)
      .then(shop => this.shops.pull(this.markedForDeletion))
    },
  },
}
</script>

<style lang="scss" scoped>

.shops-list {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin: 0 39px 0 0;
}

.shops-list-item {

  justify-content: flex-start;
  width: 100%;
  height: 56px;
  padding: 0 21px;
  background-color: rgba(#868686, .06);
  border-radius: 5px;
  margin-bottom: 5px;

  .title {
    a {
      display: block;
      color: #0997b1;
      margin-left: 22px;
      min-width: 100px;
    }
  }

  .small-rounded-btn {

    width: 93px;
    height: 31px;
    font-size: 14px;
    font-weight: 300;
    padding: 0;
  }

  .vue-js-switch {
    margin-bottom: 0;
    margin-right: 59px;
  }
  .v-switch-core {
    background-color: #0997b1 !important;
  }

  img {
    cursor: pointer;
  }

  .buttons-block {

    flex: 1;
  }
}
</style>
