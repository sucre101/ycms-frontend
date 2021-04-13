<template>
  <div class="top-button-list">
    <template v-for="link in links">
      <a
        v-if="link.cond"
        @click.prevent="linkTo(link)"
        :class="link.class"
      >
        {{ link.name }}
      </a>
    </template>

    <YcmsDataSorter
      v-if="enableSort"
      :filter="{
        data: $parent.productList,
        params: [
          {name: 'Filter', value: null, filterBy: 'id'},
          {name: 'By product name', value: 1, filterBy: 'name'},
          {name: 'By category name', value: 2, filterBy: 'category.name'}
        ]
      }"
      @complete="complete"
    />

  </div>
</template>

<script>
  import YcmsDataSorter from "./YcmsDataSorter";
  export default {
    name: "ycms-top-buttons",

    components: {
      YcmsDataSorter
    },

    props: {
      links: {
        type: Array,
        default: () => {
          return [{class: 'small-rounded-btn content-top-button mr-15', href: '#', name: 'Go back', cond: true}]
        }
      },
      enableSort: {
        type: Boolean,
        default: false
      }
    },

    mounted() {
      this.$parent.$on('updateTop', this.update)
    },

    destroyed() {
      this.$parent.$off('updateTop')
    },

    methods: {
      update() {
        _.forEach(this.links, (val) => {
          if (val.hasOwnProperty('switched')) {
            val.cond = !!val.cond
          }
        })
      },

      linkTo(link) {
        if (link.name === 'Go back') {
          return window.history.back();
        } else {
          return location.href = link.href;
        }
      },

      complete(data) {
        this.$parent.productList = _.cloneDeep(data)
      }
    }
  }
</script>

<style scoped>
  .top-button-list {
    padding-bottom: 20px;
    display: flex;
    flex-direction: row;
    align-items: center;
  }
</style>
