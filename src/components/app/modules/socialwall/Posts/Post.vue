<template>
  <div >
    <InnerTab :items="[
        { name: 'Blocks' },
        { name: 'Preview' },
        { name: 'Tags' },
    ]"
              @change="selectTab"
    />
      <blocks v-if="selectedTab === 0" />
      <preview v-if="selectedTab === 1" />
      <tags v-if="selectedTab === 2" />

  </div>
</template>
<script>
  import Preview from './Preview'
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
      'preview': Preview,
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
