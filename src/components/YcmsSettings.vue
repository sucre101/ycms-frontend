<template>
  <div class="container">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'ycms-settings',

  data() {
    return {}
  },

  methods: {
    fixPages(){
      post('/fix-pages')
    },
  },
}
</script>

<style lang="scss" scoped>

</style>
