<template>
<div class="ycms-tabs" :style="{width}">
  <div
    class="tab"
    v-for="(header, i) in tabs" :key="i"
    v-text="header"
    @click="setActiveTab($event, header)"
  />
</div>
</template>

<script>
export default {
  name: 'ycms-tabs',

  data() {
    return {
      active: 0,
    }
  },

  props: {
    tabs: Array,
    width: String,
  },

  methods: {
    setActiveTab($event, header) {
      this.active = this.tabs.indexOf(header)
      this.$emit('selected', header)
      // l($event.target, header)
    }
  },

  watch: {
    example(newVal, oldVal) {
    }
  },

  computed: {
    example() {
      return 'example'
    },
  },

  mounted() {
  }
}
</script>

<style lang="scss" scoped>
.ycms-tabs {
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin-bottom: 31.5px;
}

.tab {
  width: 114px;
  border-bottom: solid 1px;
  border-bottom-color: #393c40;
  position: relative;
  color: #393c40;
  font-size: 14px;
  cursor: pointer;

  &.active {
    color: #0997b1;
    border-bottom-color: #0997b1;
  }

  &:after {
    content: "";
    position: absolute;
    display: block;
    width: 73px;
    height: 1px;
    border-bottom: solid 1px #393c40;
    bottom: -4px;
  }
}
</style>
