<template>

  <div class="inner-page-tabs">

    <div
      v-for="(item, idx) in items"
      :class="{ selected: selectedTab === idx }"
      @click="select(idx)"
    >
      {{ item.name }}
    </div>

  </div>

</template>

<script>
export default {
  name: "inner-tab",

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
      selectedTab: this._.cloneDeep(this.selected)
    }
  },

  methods: {

    select(index) {
      this.selectedTab = index;
      this.$emit('change', index);
    }

  }

}
</script>

<style scoped lang="scss">

.inner-page-tabs {
  display: flex;
  flex-direction: row;
  div {
    border-bottom: 1px solid;
    padding-right: 15px;
    margin-right: 25px;
    cursor: pointer;
    min-width: 130px;
    position: relative;
  }
  div:before {
    content: '';
    position: absolute;
    bottom: -7px;
    border-bottom: 1px solid black;
    width: 50%;
  }
  div.selected {
    color: #0997b1;
  }

}

</style>
