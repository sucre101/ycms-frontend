<template>
  <div class="table-list-items">

    <div v-if="products.length">
      <div v-for="product in products" class="item">

        <div class="item-name">{{ product.name }}</div>

        <div class="item-actions">

          <div class="btn-action blue" @click.prevent="editProduct(product.id)">Edit</div>
          <div class="btn-action green" @click.prevent="editProduct(product.id)">Duplicate</div>
          <div class="btn-action delete" @click.prevent="editProduct(product.id)"></div>

        </div>

      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "products-list",

  data() {
    return {
      products: [],
      module: {}
    }
  },

  created() {
    this.module.id = this.$parent.$parent.moduleId
    this.loadData()
  },

  mounted() {
    window.setTitle('Product list')
  },

  methods: {

    loadData() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/products`)
          .then((res) => {
            this.products = this._.cloneDeep(res.data.products)
          })
    },

    editProduct(id) {

      this.$router.replace({ query: { tab: 'products', product: id } })
      this.$parent.editProduct = true

    }

  }

}
</script>

<style scoped lang="scss">
.table-list-items {
  .item {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    margin-bottom: 20px;
    align-items: center;
    .item-actions {
      display: flex;
      align-items: center;
      width: 33%;
      justify-content: space-between;
    }
  }
}
</style>
