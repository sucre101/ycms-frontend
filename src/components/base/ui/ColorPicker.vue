<template>
  <div class="picker-block">
    <div
      class="ycms-colorpicker ml-auto"
      :style="{backgroundColor: selectedColor}"
      @click="pickerHidden = false"
    >
      <vue-color
        :value="selectedColor"
        @input="updateValue"
        piker="chrome"
        v-if="!pickerHidden"
      />
      <input type="hidden" :name="name" v-model="selectedColor">
      <img
        id="close-picker"
        src="@/assets/img/close-circular-button-symbol.svg"
        alt="close"
        :style="{ display: pickerHidden ? 'none' : '' }"
        @click.stop="closeAndUpdate"
      >
    </div>
  </div>
</template>

<script>
export default {
name: 'color-picker',

  data() {
    return {
      pickerHidden: true,
      selectedColor: this.input
    }
  },

  props: {
    input: {
      type: String,
      default: '#FFFFFF'
    },
    name: {
      type: String,
      default: ''
    },
    colorType: {
      type: String,
      default: 'hex'
    },
    returnElement: {
      type: String,
      default: null
    }
  },

  watch: {
    pickerHidden(newVal, oldVal) {
      this.$el.style.zIndex = newVal ? 'auto' : 100
    }
  },

  methods: {

    updateValue($event) {

      if (!this.colorType === 'hex') {
        this.selectedColor = `rgba(${Object.values($event.rgba).join(', ')})`
      } else {
        this.selectedColor = $event.hex
      }

    },

    closeAndUpdate() {
      this.pickerHidden = !this.pickerHidden
      this.$emit('update', this.selectedColor, this.returnElement)
    }
  },

}
</script>

<style lang="scss" scoped>

.picker-block {
  border-radius: 15px;
  border: solid 3px rgba(181, 181, 181, 0.47);
  background-image: linear-gradient(160deg, #a7a7a7 17%, #e5e5e5 43%, #a7a7a7 68%);
  display: inline-block;

  .ycms-colorpicker {
    position: relative;
    width: 95px;
    height: 20px;
    border-radius: 24px;
    cursor: pointer;

    .vc-chrome {
      position: absolute;
      top: 30px;
    }
  }
}


#close-picker {
  width: 20px;
  position: absolute;
  right: -152px;
  bottom: -13px;
}
</style>
