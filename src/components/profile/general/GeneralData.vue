<template>
  <div class="table-list-items">
    General data
  </div>
</template>

<script>
export default {
  name: "general-data",
  title: 'General',

  data() {
    return {
      userData: this.$store.getters.currentUser
    }
  },

}
</script>

<style scoped>

</style>
