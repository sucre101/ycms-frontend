<template>
  <div class="filter" v-if="filter.data.length">
    <select
      v-model="filterSelect"
      @change="completeFilter"
    >

      <template v-for="param in filter.params">
        <option :value="param.value">{{ param.name }}</option>
      </template>

    </select>
  </div>
</template>

<script>
  export default {
    name: "ycms-data-sorter",

    data() {
      return {
        filterSelect: null,
      }
    },

    props: {
      filter: {
        type: Object,
        required: true
      }
    },

    methods: {
      completeFilter() {

        let data = _.orderBy(this.filter.data, [
          this.filter.params[_.findIndex(this.filter.params, {value: this.filterSelect})].filterBy
        ])

        this.$emit('complete', data)
      }
    }


  }
</script>

<style scoped>
  select {
    -webkit-appearance: none;
    -moz-appearance: none;
    padding: 4px 10px;
    background: #393c40;
    color: white;
    font-size: 13px;
    border-radius: 24px;
    font-weight: 300;
    cursor: pointer;
  }
</style>
