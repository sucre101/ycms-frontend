<template>
  <div>
    <loading
      :active="loading"
      :is-full-page="true"
      :opacity="0.6"
      :enforce-focus="true"
      color="#0997b1"
      background-color="#000000"
    />
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "preloader",

  components: {
    Loading
  },

  props: {
    loading: {
      type: Boolean,
      default: true,
    },
  }
}
</script>

<style scoped>

</style>
