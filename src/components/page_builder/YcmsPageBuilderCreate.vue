<template>
  <div>
    <a class="ycms-button bg-green-gradient" :href="'/app/'+app.slug+'/page-builder'">Go Back</a>
  </div>
</template>
<script>
  export default {
    name: 'ycms-page-builder-create',
    props: {
      app: Object,
    },
    components:{
    },
    data() {
      return {
      }
    },
    methods: {

    },
    mounted() {

    }
  }
</script>

<style lang="scss" scoped>

</style>
