<template>
<div
  class="curly-check small"
  @click="handleClick"
>
  <input type="hidden" :name="name" :value="isChecked">
  <div class="inner" :class="{ checked: isChecked }"></div>
</div>
</template>

<script>
export default {
  name: 'ycms-check',

  data() {
    return {
      isChecked: !!this.checked,
      parent: {}
    }
  },

  props: {
    checked: {
      type: Boolean,
      default: false
    },
    name: {
      type: String,
      default: 'set-name'
    },
    clickHandler: {
      type: String,
      default: 'toggleChecked'
    },
    otherRadios: {
      type: Array,
      default() { return [] },
    },
    checkedInParent: {
      type: Array,
      default() { return [0, ''] },
    }
  },

  methods: {
    handleClick() {
      this[this.clickHandler]()
    },
    toggleChecked() {
      if(!this.$parent.$vnode.tag.includes('tree-category'))
        this.isChecked = !this.isChecked
    },
    toggleRadio() {
      this.toggleChecked()
      this.$root.$emit('curly-check-toggle', this.otherRadios)
    },
  },

  computed: {
    arr() {
      return empty(this.parent) ? [] : this.parent.checked
    },
  },

  watch: {
    arr(arr) {
      if (arr.length) {
        this.isChecked
        =
        arr.map(i => parseInt(i)).includes(this.checkedInParent[0])
      }
    }
  },

  mounted() {
    if (this.checkedInParent[1]) {
      waitDefined(this.checkedInParent[1]).then(r => this.parent = r)
    }

    this.$root.$on('curly-check-toggle', names => {
      if (names.includes(this.name)) {
        this.toggleChecked()
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.curly-check {
  width: 29px;
  height: 29px;
  border: solid 1px #707070;
  display: inline-block;
  padding: 5px;
  cursor: pointer;
}

.curly-check .inner {
  width: 17px;
  height: 17px;
}

.curly-check .inner.checked {
  background-color: #0997b1;
}

.curly-check.small {
  width: 11px;
  height: 11px;
  padding: 1px;
  border: solid 1px #868686;
}

.curly-check.small .inner {
  width: 7px;
  height: 7px;
}
</style>
