<template>
  <div class="container">
<!--    <div class="dropdown">-->
<!--      <input v-bind="{name}" type="hidden" v-model="checked">-->
<!--      <div class="item" @click="expanded = !expanded">-->
<!--        <img-->
<!--          src="@/assets/img/angle-down.svg"-->
<!--          :style="{ transform: expanded ? 'rotate(0deg)' : 'rotate(-90deg)' }"-->
<!--        >-->
<!--        {{ placeholder }}-->
<!--      </div>-->
<!--      <tree-category-->
<!--        v-for="opt in options" :key="opt.id"-->
<!--        :cat="opt"-->
<!--      />-->
<!--      <div class="action">-->
<!--        <span class="done" @click="expanded = false">Done</span>-->
<!--      </div>-->
<!--    </div>-->
  </div>
</template>

<!--<script>-->

<!--let TreeCategory = Vue.component('tree-category', {-->
<!--  props: {-->
<!--    cat: Object,-->
<!--  },-->
<!--  template: `-->
<!--    <div class="tree-cat">-->
<!--      <div @click="onClick(cat.id)">-->
<!--        <ycms-check ref="check" :checkedInParent="[cat.id, 'grefs.catTree']" />-->
<!--        {{ cat.name }}-->
<!--      </div>-->
<!--      <div class="children" :style="{paddingLeft: '14px'}">-->
<!--        <tree-category-->
<!--          v-for="child in cat.children" :key="child.id"-->
<!--          :cat="child"-->
<!--        />-->
<!--      </div>-->
<!--    </div>-->
<!--  `,-->
<!--  methods: {-->
<!--    onClick(catId) {-->
<!--      if (this.treeDrop.onlyLeaf && this.cat.children.length) {-->
<!--        this.notifier.warning('Select leaf category, please')-->
<!--        return-->
<!--      }-->
<!--      if (this.treeDrop.singleValue) {-->
<!--        this.treeDrop.checked = [catId]-->
<!--        return-->
<!--      }-->
<!--      this.$root.$emit('categoryChecked', this.cat.id)-->
<!--    }-->
<!--  },-->
<!--  mounted() {-->
<!--    this.$root.$on('setChecked', arr => {-->
<!--      this.checked = arr-->
<!--      let checkBox = this.$refs.check-->
<!--      // Reset checkboxes by clicking on checked boxes-->
<!--      if (checkBox.isChecked) checkBox.$el.click()-->
<!--      // Check again if category id in array-->
<!--      // if (arr.includes(this.cat.id)) checkBox.$el.click()-->
<!--    })-->
<!--  },-->
<!--  computed: {-->
<!--    treeDrop() {-->
<!--      if (this.$parent.$vnode.tag.includes('tree-drop')) return this.$parent-->
<!--      else return this.$parent.treeDrop-->
<!--    }-->
<!--  }-->
<!--})-->

<!--export default {-->
<!--  name: 'ycms-tree-drop',-->

<!--  components: { TreeCategory },-->

<!--  data() {-->
<!--    return {-->
<!--      expanded: false,-->
<!--      gref: this.$vnode.data.directives.find(d => d.name == 'gref').arg,-->
<!--      checked: [],-->
<!--    }-->
<!--  },-->

<!--  props: {-->
<!--    options: Array,-->
<!--    name: { type: String, default: 'parentCategories' },-->
<!--    placeholder: String,-->
<!--    singleValue: Boolean,-->
<!--    onlyLeaf: Boolean,-->
<!--  },-->

<!--  watch: {-->
<!--    expanded() {-->
<!--      let categoriesCount = flatTree(this.options).length-->
<!--      let el = this.$el.firstElementChild.style-->
<!--      let expand = () => {-->
<!--        el.height = 43 + categoriesCount * 21 + 45 + 'px'-->
<!--        el.zIndex = 10-->
<!--      }-->
<!--      let shrink = () => {-->
<!--        let resetZIndex = () => el.zIndex = ''-->
<!--        el.height = '43px'-->
<!--        setTimeout(resetZIndex, 500)-->
<!--      }-->
<!--      this.expanded ? expand() : shrink()-->
<!--    }-->
<!--  },-->

<!--  methods: {-->
<!--    pick(option) {-->
<!--      this.selected = option-->
<!--      this.expanded = false-->
<!--      this.$emit('pick', option)-->
<!--    }-->
<!--  },-->

<!--  mounted() {-->
<!--    this.$root.$on('categoryChecked', id => {-->
<!--      let checked = this.checked-->
<!--      checked.includes(id) ? checked.pull(id) : checked.push(id)-->
<!--    })-->
<!--  }-->
<!--}-->
<!--</script>-->

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
    cursor: pointer;

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

.tree-cat {
  padding-left: 16px;
  text-align: left;
  cursor: pointer;
}

.action {
  display: flex;
  justify-content: space-around;
  align-items: center;
  height: 45px;

  .cancel, .done {
    text-decoration: underline;
    cursor: pointer;
  }

  .cancel {
    color: #393c40;
  }
  .done {
    color: #50b109;
  }
}
</style>
