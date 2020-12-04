<template>
<div class="ycms-table-headers">
  <div
    class="table-header"
    v-for="(margin, header, i) in filteredHeaders" :key="i"
    :style="{marginLeft: margin + 'px'}"
  >
    {{ header }}
  </div>
</div>
</template>

<script>
export default {
  name: 'ycms-table-headers',

  props: {
    headers: Object
  },

  data() {
    return {
      filteredHeaders: this.headers,
    }
  },

  mounted() {
    this.$root.$on('filter', val => {
      val == 'deleted'
        ? this.filteredHeaders = {'Name': this.headers['Name']}
        : this.filteredHeaders = this.headers
    })
  }
}
</script>

<style lang="scss" scoped>
.ycms-table-headers {
  display: flex;
  margin-bottom: 20.5px;

  .table-header {
    width: 114px;
    border-bottom: solid 1px #0997b1;
    font-size: 14px;
    color: #0997b1;
  }
}
</style>
