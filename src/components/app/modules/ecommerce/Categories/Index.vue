<template>
  <div>
    <preloader :loading="loading" />

    <categories-list v-if="!loading && !editCategory"/>

    <category v-if="editCategory"/>

  </div>
</template>

<script>
import List from './List'
import Category from "./Category"

export default {
  name: "index",
  title: 'Categories',
  components: {
    'categories-list': List,
    'category': Category
  },

  data() {
    return {
      loading: false,
      editCategory: false,
      list: []
    }
  },

  created() {
    this.editCategory = this.$route.query.hasOwnProperty('category');
  },



}
</script>

<style scoped>

</style>
