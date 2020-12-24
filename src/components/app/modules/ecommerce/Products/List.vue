<template>
  <div class="products-list-table">

    <div v-if="products.length">
      <div v-for="product in products" class="item">

        <div class="item-name">{{ product.name }}</div>

        <div class="item-actions">

          <div class="btn btn-edit" @click.prevent="editProduct(product.id)">Edit</div>
          <div class="btn btn-copy" @click.prevent="editProduct(product.id)">Duplicate</div>
          <div class="btn btn-remove" @click.prevent="editProduct(product.id)">
            <img src="/img/garbage.png" alt="">
          </div>

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
.products-list-table {
  width: 70%;
  background-color: white;
  padding: 15px 50px;
  .item {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    margin-bottom: 20px;
    align-items: center;
    .item-actions {
      display: flex;
      align-items: center;
      .btn {
        padding: 5px 34px 7px 35px;
        border-radius: 16px;
        color: white;
        margin: 0 10px;
        cursor: pointer;
      }
      .btn-edit {
        background-color: #0997b1;
      }
      .btn-copy {
        background-color: #50b109;
      }
      .btn-remove {
        padding: 0;
      }
    }
  }
}
</style>
