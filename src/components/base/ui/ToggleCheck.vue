<template>
  <ToggleButton
    switch-color="#FFFFFF"
    :width="47"
    :height="30"
    :margin="2"
    :color="{ checked: '#0989cc', unchecked: '#b5b5b5'}"
    @change="onChangeEventHandler"
    :value="value"
    :label="label"
  />
</template>

<script>
import {ToggleButton} from "vue-js-toggle-button";

export default {
  name: "toggle-check",

  components: {
    ToggleButton
  },

  props: {
    value: {
      type: Boolean,
      default: false,
      required: true
    },
    label: {
      type: Boolean,
      required: false,
      default: false,
    }
  },

  methods: {
    onChangeEventHandler(val) {
      this.$emit('complete', val)
    }
  }
}
</script>

<style scoped>
label {
  margin: 0;
}
</style>
