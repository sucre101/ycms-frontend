<template>
  <div>
    <orders-list v-if="!editOrder"/>

    <order v-if="editOrder" @go-product="goProduct"/>
  </div>
</template>

<script>
import List from "./List"
import Order from "./Order"

export default {
  name: "index",
  title: 'Orders',
  components: {
    'orders-list': List,
    Order
  },

  data() {
    return {
      editOrder: false,
      orderData: {}
    }
  },

  created() {
    this.editOrder = this.$route.query.hasOwnProperty('order')
  },

  methods: {

    goProduct(url) {
      // this.$router.push(url)
      // console.log(this.$parent)
    }

  }


}
</script>

<style scoped>

</style>
