<template>
<div class="container">
  <div
    class="dropdown"
    :style="{
      height: expanded ? 43 * (options.length + 1) + 'px' : '43px',
      zIndex: expanded ? 10 : '',
    }"
  >
    <div class="item" @click="expanded = true">
      <img
        src="/img/angle-down.svg"
        :style="{ transform: expanded ? 'rotate(0deg)' : 'rotate(-90deg)' }"
      >
      {{ selected || placeholder }}
    </div>
    <div
      v-for="(opt, i) in options" :key="i"
      class="item option"
      @click="pick(opt)"
    >
      {{ opt.name ? opt.name : opt }}
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'ycms-dropdown',

  data() {
    return {
      expanded: false,
      selected: false,
    }
  },

  props: {
    options: Array,
    placeholder: String,
  },

  methods: {
    pick(option) {
      this.selected = option.name ? option.name : option
      this.expanded = false
      this.$emit('pick', option)
    }
  },
}
</script>

<style lang="scss" scoped>
.container {
  width: 346px;
  height: 43px;
  margin-bottom: 14px;

  &.error {
    .dropdown {
      border-color: #b40000;
    }
  }
}

.dropdown {
  width: 346px;
  height: 43px;
  border-radius: 22px;
  border: solid 1px #868686;
  background-color: white;
  font-size: 14px;
  font-weight: 300;
  color: #0997b1;
  transition: height 0.5s;
  overflow: hidden;
  position: relative;

  .item {
    width: 100%;
    height: 43px;
    display: flex;
    align-items: center;
    padding-left: 17px;

    &.option {
      padding-left: 35px;
      border-top: 1px solid #e2e2e2b5;
      cursor: pointer;

      &:hover {
        background-color: #e2e2e295;
      }
    }

    > img {
      margin-right: 10px;
      transform: rotate(-90deg);
      transition: transform 0.5s;
    }
  }
}
</style>
