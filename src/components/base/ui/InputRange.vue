<template>
  <div class="range-input">
    <input type="range" :min="min" :max="max" :step="step" :value="value" :class="{ 'vertical-orient': vertical }">
  </div>
</template>

<script>
export default {
  name: "input-range",

  props: {
    min: {
      type: Number,
      default: 0
    },
    max: {
      type: Number,
      default: 10
    },
    step: {
      type: Number,
      default: 1
    },
    value: {
      type: Number,
      default: 0
    },
    vertical: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped lang="scss">
.range-input {
  max-width: 300px;
  display: flex;

  input {
    width: 100%;
  }

  .vertical-orient {
    -webkit-appearance: none;
    height: 200px;
    border-radius: 5px;
    background: #ccc;
    outline: none;
    writing-mode: bt-lr; /* IE */
    -webkit-appearance: slider-vertical; /* WebKit */
  }
}
</style>
