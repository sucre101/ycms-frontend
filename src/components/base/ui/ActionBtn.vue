<template>
  <div class="action-btn-item" :style="styles">

    <img v-if="image" :src="image" alt=""/>

    <template v-if="title">
      {{ title }}
    </template>

  </div>
</template>

<script>
export default {
  name: "action-btn",

  props: {
    title: {
      type: String,
      default: ''
    },
    styles: {
      type: String,
    },
    image: {
      type: String,
      default: ''
    }
  }
}
</script>

<style scoped lang="scss">
.action-btn-item {
  cursor: pointer;
}
</style>
