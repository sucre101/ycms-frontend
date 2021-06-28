<template>
  <div class="table-list-items">

    <vue-custom-scrollbar class="content-scroll" v-if="products.length" @ps-y-reach-end="_loadData(false)">
      <div v-for="product in products" class="item">

        <div class="item-name">{{ product.name }}</div>

        <div class="item-actions">

          <div class="btn-action blue" @click.prevent="_editProduct(product.id)">Edit</div>
          <div class="btn-action green" @click.prevent="_duplicateProduct(product.id)">Duplicate</div>
          <div class="btn-action delete" @click.prevent="_deleteProduct(product.id)"></div>

        </div>

      </div>
    </vue-custom-scrollbar>

<!--    <div v-if="products.length">-->
<!--      -->
<!--    </div>-->

    <div class="new-item">
      <div class="input-group" v-if="inputField">
        <input type="text" class="input-field" v-model.trim="newProduct" @keypress.enter="_createProduct">
        <div>
          <i class="fas fa-times" @click="newProduct = null"></i>
          <i class="fas fa-plus" @click="_createProduct"></i>
        </div>
      </div>

      <div class="input-group" v-if="inputField">
        <select v-model="categoryId">
          <option :value="null">Category</option>
          <option :value="category.id" v-for="category in categories">{{ category.name }}</option>
        </select>
      </div>
    </div>

  </div>
</template>

<script>
import {moduleUrl} from "@/helpers/general";
import vueCustomScrollbar from 'vue-custom-scrollbar'
import "vue-custom-scrollbar/dist/vueScrollbar.css"

export default {
  name: "products-list",

  components: {
    vueCustomScrollbar
  },

  data() {
    return {
      products: [],
      inputField: false,
      skip: 20,
      limit: 2,
      stopLoad: false,
      newProduct: null,
      categoryId: null,
      categories: []
    }
  },

  created() {
    this._loadData()
    this.$parent.$parent.$on('add::element', v => {
      this.inputField = !this.inputField
    })
  },

  methods: {

    _loadData(firstLoad = true) {

      if (this.stopLoad) {
        return
      }

      if (firstLoad) {
        axios.get(`${moduleUrl(this.$route)}/product`)
            .then((res) => {
              this.products = this._.cloneDeep(res.data.products)
              this.categories = this._.cloneDeep(res.data.categories)
            })
      } else {
        axios.get(`${moduleUrl(this.$route)}/product/${this.limit}/${this.skip}`)
          .then((res) => {
            if (res.data.success) {
              if (res.data.products.length > 1) {
                this.products = this._.concat(this.products, res.data.products)
                this.$nextTick(() => {
                  this.skip = this.products.length
                })
              } else {
                this.stopLoad = true
              }
            }
          })
      }

    },

    _editProduct(id) {
      this.$router.replace({ query: { tab: 'products', product: id } })
      this.$parent.editProduct = true
    },

    _duplicateProduct(productId) {
      axios.get(`${moduleUrl(this.$route)}/product/${productId}/duplicate`)
        .then((res) => {
          if (res.data.success) {
            this.products.push(res.data.product)
            this.notifier.success('Product duplicated')
          }
        })
    },

    _deleteProduct(id) {

      let $filteredArray = this.products.filter((val) => {
        return val.id !== id
      })

      axios.delete(`${moduleUrl(this.$route)}/product/${id}`)
        .then((res) => {
          if (res.data.success) {
            this.products = $filteredArray
            this.notifier.success('Product deleted')
          }
        })

    },

    _createProduct() {

      if (this.categoryId === null || (this.newProduct === null || this.newProduct === '')) {
        this.notifier.warning('One ore more params dont filled')
        return false
      }

      let $insertData = {
        name: this.newProduct,
        category: this.categoryId,
        module: this.$route.params.moduleId
      }

      axios.post(`${moduleUrl(this.$route)}/product`, $insertData)
        .then((res) => {
          if (res.data.success) {
            this.products.push(res.data.product)
            this.notifier.success('Product created')
          }
        })
        .then(res => this.inputField = false)

    }

  }

}
</script>

<style scoped lang="scss">
.table-list-items {
  .content-scroll {
    max-height: 600px !important;
  }
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
  .input-group {
    width: 30%;
    position: relative;
    input {
      width: auto;
      padding: 7px 50px 7px 22px;
    }
    div {
      position: absolute;
      top: 13px;
      right: 0;
      width: 60px;
      display: flex;
      justify-content: space-evenly;
      color: #0997b1;
      font-size: 12px;
      i {
        cursor: pointer;
      }
    }
  }
  .new-item {
    display: flex;
    flex-direction: row;
    align-items: center;
    .input-group {
      margin-right: 20px;
    }
  }
}
</style>
