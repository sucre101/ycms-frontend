<template>
  <div class="page-nav">
    <div class="intro-wrap">

      <template v-if="!tab">
        <div
          v-for="(btn, index) in list"
          :class="{ active: selectedIn === index }"
          @click="$router.push({ name: btn.path, params: { slug: $root.$children[0].app.slug } })"
        >
          {{ btn.title }}
        </div>
      </template>

      <template v-if="tab">
        <div
          v-for="(btn, index) in list"
          :class="{ active: selectedIn === index }"
          @click="select(btn, index)"
        >
          {{ btn.title }}
        </div>
      </template>

    </div>
  </div>
</template>

<script>
export default {
  name: "page-navigation",

  props: {
    list: {
      type: Array,
      default:() => {
        return []
      }
    },
    selected: {
      type: Number,
      default: 0
    },
    tab: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {
      selectedIn: this.selected
    }
  },

  updated() {
    this.selectedIn = this.selected
  },

  methods: {

    select(btn, index) {

      if (btn.title !== 'Back') {
        this.selectedIn = index;
        this.$emit('change', index)
      } else {
        this.$router.replace({'query': null});
        this.$root.$emit('navigator::setBack', false)
      }

    }

  }

}
</script>

<style scoped lang="scss">

.page-nav {
  width: 100%;
  display: flex;
  align-items: center;
  font-family: 'SFProText-Light', sans-serif;
  color: #687c97;
  height: 100%;
  font-size: 13px;
  .intro-wrap {
    display: flex;
    height: 100%;
    align-items: center;
    div {
      padding: 0 15px;
      cursor: pointer;
      position: relative;
      height: 100%;
      display: flex;
      align-items: center;
      &.active {
        color: #8674f4;
        &::after {
          content: "";
          width: 35%;
          height: 2px;
          background-color: #8674f4;
          display: block;
          position: absolute;
          bottom: -1px;
          left: 33%;
          border-radius: 50%;
        }
      }
    }
  }
}


</style>
