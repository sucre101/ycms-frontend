<template>
  <div >
    <InnerTab :items="[
        { name: 'Blocks' },
        { name: 'Tags' },
    ]"
              @change="selectTab"
    />
      <blocks v-if="selectedTab === 0" />
      <tags v-if="selectedTab === 1" />

  </div>
</template>
<script>
  import Tags from './Tags'
  import Blocks from './../Blocks/List'
  import InnerTab from "@/components/base/ui/InnerTab";

  export default {
    name: 'post',
    props: {
    },
    components:{
      'tags': Tags,
      'blocks': Blocks,
      InnerTab
    },
    data() {
      return {
        module: {},
        selectedTab: 0,
      }
    },
    created() {
      this.module.id = this.$parent.moduleId
    },
    methods: {
      selectTab(index) {
        this.selectedTab = index
      },
    },
  }
</script>

<style scoped lang="scss">

</style>
