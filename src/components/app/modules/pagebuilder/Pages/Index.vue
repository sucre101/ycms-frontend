<template>

  <div>
    <preloader v-if="loading"/>

    <pages-list v-if="!loading && !editpage"/>

    <page v-if="editpage"/>
  </div>

</template>

<script>
import List from './List'
import Page from './Page'

export default {
  name: "index",

  components: {
    'pages-list': List,
    'page': Page
  },

  data() {
    return {
      loading: false,
      editpage: false,
    }
  },

  created() {
    this.editpage = this.$route.query.hasOwnProperty('page')
  }

}
</script>

<style scoped>

</style>
