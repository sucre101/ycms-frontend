<template>
  <ToggleButton
    switch-color="#FFFFFF"
    :width="30"
    :height="18"
    :margin="2"
    :color="{ checked: '#8674f4', unchecked: '#b8b8b8'}"
    @change="onChangeEventHandler"
    :value="value"
    :label="label"
    :disabled="disabled"
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
    },
    disabled: {
      type: Boolean,
      default: false,
    },
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
