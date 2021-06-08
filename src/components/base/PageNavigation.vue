<template>
  <div class="page-nav">
    <div class="intro-wrap">

      <template v-if="!tab">
        <div
          class="small-rounded-btn content-top-button"
          v-for="(btn, index) in list"
          :class="{ active: selectedIn === index }"
          @click="$router.push({ name: btn.path, params: { slug: $root.$children[0].app.slug } })"
        >
          {{ btn.title }}
        </div>
      </template>

      <template v-if="tab">
        <div
          class="small-rounded-btn content-top-button"
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
  height: 93px;
  width: 100%;
  display: flex;
  align-items: center;

  .intro-wrap {
    display: flex;

    .rnd-button {
      margin-right: 15px;
    }
  }
}
.small-rounded-btn {
  text-transform: capitalize;
}

</style>
