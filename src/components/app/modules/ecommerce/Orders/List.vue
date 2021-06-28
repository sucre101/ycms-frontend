<template>
  <div class="table-list-items">

    <h4>Order list</h4>

    <div class="order-item" v-for="order in orders">

      <div class="order-data">
        <div class="order-customer">{{ order.name }}</div>

        <div class="customer-email">{{ order.email }}</div>

        <div class="order-status">{{ order.status.name }}</div>

        <div class="order-store">{{ order.store.name }}</div>
      </div>

      <div class="order-actions">

        <div class="btn-action blue" @click="editOrder(order)">Edit</div>
        <div class="btn-action delete"></div>

      </div>

    </div>

  </div>
</template>

<script>
import {moduleUrl} from "@/helpers/general";

export default {
  name: "orders-list",

  data() {
    return {
      orders: [],
    }
  },

  created() {
    this._loadData();
  },

  methods: {

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/order`)
        .then((res) => {
          this.orders = this._.cloneDeep(res.data.orders)
        })

    },

    editOrder(order) {
      this.$router.replace({ query: { tab: 'orders', order: order.id } })
      this.$parent.editOrder = true
      this.$parent.orderData = this._.cloneDeep(order)
    }

  }

}
</script>

<style scoped lang="scss">
.table-list-items {
  .order-item {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    padding: 10px 0;
    align-items: center;
    .order-actions {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      width: 20%;
    }
    .order-data {
      width: 75%;
      display: flex;
      justify-content: space-between;
      .order-email {
        width: 50px;
        display: flex;
        align-items: center;
        align-content: center;
      }
    }
  }
}
</style>
