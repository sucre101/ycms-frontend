<template>
  <div class="tab-select">
    <div class="item" :class="{ 'active': !value }" @click="select">
      Select
    </div>
    <template v-for="(item, key) in items">
      <div
        :key="key"
        class="item"
        :class="{ 'active': value === item.value }"
        @click="select(item.value)"
      >
        {{ item.name }}
      </div>
    </template>
  </div>
</template>

<script>
export default {
  name: "select-tab",

  props: {
    items: {
      type: Array,
      required: true,
      default: () => {
        return []
      }
    },
    selected: {
      type: Number,
      default: 0
    }
  },

  data() {
    return {
      value: this.selected
    }
  },

  methods: {
    select(value = 0) {
      this.value = value
      this.$emit('complete', value)
    }
  }
}
</script>

<style scoped lang="scss">
.tab-select {
  display: inline-block;
  height: 20px;
  border-radius: 3px;
  box-shadow: 0 0.5px 1px 0 rgba(0, 0, 0, 0.28);
  border: solid 0.5px rgba(0, 0, 0, 0.1);
  background-color: var(--white);
  font-size: 13px;
  overflow: hidden;

  .item {
    display: block;
    padding: 0 15px;
    float: left;
    cursor: pointer;
  }

  .item.active {
    background-color: #0989cc;
    color: white;
  }
}
</style>
