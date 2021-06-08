<template>

  <div>
    <preloader v-if="loading"/>

    <products-list v-if="!loading && !editProduct"/>

    <product v-if="editProduct"/>
  </div>

</template>

<script>
import List from './List'
import Product from './Product'

export default {
  name: "index",
  title: 'Products',
  components: {
    'products-list': List,
    'product': Product
  },

  data() {
    return {
      loading: false,
      editProduct: false,
    }
  },

  created() {
    this.editProduct = this.$route.query.hasOwnProperty('product')
  }

}
</script>

<style scoped>

</style>
