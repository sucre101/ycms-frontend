<template>

  <div>

    <post-list v-if="!showBlocks"/>

    <post v-else />
  </div>

</template>

<script>
import List from './List'
import Post from './Post'

export default {
  name: "posts",

  components: {
    'post-list': List,
    'post': Post
  },

  data() {
    return {
      loading: false,
      showBlocks: false,
    }
  },

  created() {
    this.editpage = this.$route.query.hasOwnProperty('page')
  }

}
</script>

<style scoped>

</style>
