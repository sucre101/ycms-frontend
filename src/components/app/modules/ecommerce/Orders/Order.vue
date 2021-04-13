<template>
  <div class="table-list-items">

    <InnerTab :items="tabs"
              @change="selectTab"
    />

    <div class="btn-action save-btn blue" v-if="activeForSave" @click="_saveOrder">
      Save
    </div>

    <div class="order-tabs">

      <div class="order-main" v-if="currentTab === 0">

        <div>
          Customer name: <span>{{ order.name }}</span>
        </div>

        <div>
          Customer email: <span>{{ order.email }}</span>
        </div>

        <div>
          Customer phone: <span>{{ order.phone }}</span>
        </div>

        <div>
          Order date create: {{ order.created_at }}
        </div>

        <div>
          Comment:
          <div>
            {{ order.comment }}
          </div>
        </div>

      </div>

      <div class="order-products" v-if="currentTab === 1">

        <div class="table-header-products">
          <div class="title">Title</div>
          <div class="qty">Quantity</div>
          <div class="price">Price</div>
        </div>

        <div class="product" v-for="(item, index) in order.cart_to_product">

          <div class="product-name">
            <span @click="goProduct(item.product.id)">{{ item.product.name }}</span>
          </div>

          <div class="product-qty">
            <span @click="changeQty(index, false)">-</span>
            <input class="qty-input" type="text" v-model="item.quantity">
            <span @click="changeQty(index, true)">+</span>
          </div>

          <div class="product-price">{{ Number(item.price) * Number(item.quantity)}} {{ order.store.currency.name }}</div>

        </div>

      </div>

      <div v-if="currentTab === 2">
        3
      </div>

    </div>

  </div>
</template>

<script>
import InnerTab from "@/components/base/ui/InnerTab";
import {moduleUrl} from "@/helpers/general";
export default {
  name: "order",

  components: {
    InnerTab
  },

  data() {
    return {
      orderId: null,
      tabs: [
        { name: 'Main' },
        { name: 'Products' },
        { name: 'Address' },
      ],
      currentTab: 0,
      order: {},
      activeForSave: false,
    }
  },

  computed: {
    total(){
      let total = 0;

      this._.forEach(this.order.cart_to_product, (val) => {
        total = total + val.price * val.quantity;
      })
      return total;
    }
  },

  watch: {

    $route(to, from) {

      if (!to.query.hasOwnProperty(this.$options.name)) {
        this.$parent.editOrder = false
      }
    },

    order: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    }

  },

  created() {
    this.orderId = Number(this.$route.query.order)

    this.$root.$emit('navigator::setBack', true)

    setTimeout(() => {
      this._loadData()
    }, 1000)
  },

  mounted() {
    window.setTitle('Order edit')
  },

  methods: {

    selectTab(index) {
      this.currentTab = index
    },

    changeQty(index, val) {
      if (val === true) {
        this.order.cart_to_product[index].quantity++
      } else {
        this.order.cart_to_product[index].quantity--
      }

      if (this.order.cart_to_product[index].quantity < 1) {
        this.order.cart_to_product[index].quantity = 0
      }

      this.activeForSave = true
    },

    _saveOrder() {
      axios.patch(`${moduleUrl(this.$route)}/order/${this.order.id}`, this.order)
        .then((res) => {
          this.notifier.success('Order save')
        })
        .then(res => this.activeForSave = false)
    },

    goProduct(id) {
      this.$parent.$parent.$emit('go::product', id)
    },

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/order/${this.orderId}`)
          .then((res) => {
            this.order = this._.cloneDeep(res.data.order)
          }).then( res => this.activeForSave = false)


    },

  }
}
</script>

<style scoped lang="scss">

.order-tabs {
  margin-top: 30px;
}
.order-products {
  .table-header-products {
    display: flex;
    flex-direction: row;
    border-bottom: 1px solid;
    justify-content: center;
    width: 70%;
    .title {
      width: 60%;
    }
    .qty, .price {
      width: 20%;
    }
  }
  .product {
    display: flex;
    flex-direction: row;
    justify-content: center;
    width: 70%;
    margin-top: 20px;
    .product-name {
      width: 60%;
      span {
        cursor: pointer;
      }
    }
    .product-qty, .product-price {
      width: 20%;
      display: flex;
    }
    .qty-input {
      border: 1px solid !important;
      width: 60px;
      text-align: center;
      margin: 0 10px;
    }
  }

}
.save-btn {
  position: absolute;
  top: 20px;
  right: 30px;
}
</style>
