<template>
<div class="shop-manager">
  <div class="context"
    v-for="cont in Object.keys(contexts)" :key="cont"
    @click="changeContext(cont)"
  >
    {{ cont }}
    <hr class="one">
    <hr class="two">
  </div>

  <categories-manager v-if="context == 'Categories'" />
  <products-manager v-if="context == 'Products'"/>
</div>
</template>

<script>
export default {
  name: 'shop-manager',

  data() {
    return {
      contexts: {
        Categories: `/shops/${this.shop.id}`,
        Products: '/shops/category',
        Options: `/shop/options`
      },
      path: location.pathname,
      categories: this._categories,
    }
  },

  props: {
    shop: Object,
    _categories: Array,
  },

  methods: {
    changeContext(cont) {
      switch(cont) {
        case 'Categories':
          pushPath(this.contexts[cont])
          break
        case 'Products':
          this.notifier.info('Select category in categories tab')
          break
        case 'Options':
          this.notifier.info('In development')
          break
      }
    },
  },

  computed: {
    context() {
      let contArr = Object.entries(this.contexts).find(c => this.path.match(c[1]))
      return contArr[0]
    },
  },

  mounted() {
    window.addEventListener('pushPath', () => this.path = location.pathname)
  },
}
</script>

<style lang="scss" scoped>

.context {

  display: inline-flex;
  width: 200px;
  height: 43px;
  border: solid 1px #707070;
  font-size: 14px;
  font-weight: 300;
  color: #393c40;
  margin-right: 18px;
  position: relative;
  cursor: pointer;
  margin-bottom: 27px;

  &.active {
    border: solid 1px #0997b1;
    color: #0997b1;
  }

  .one, .two {
    position: absolute;
    left: 7.5px;
  }

  .one {
    width: 24px;
    border: none;
    border-top: solid 1px #0997b1;
    bottom: 4px;
  }

  .two {
    width: 9px;
    border: none;
    border-top: solid 1px #707070;
    bottom: 1px;
  }
}
</style>
