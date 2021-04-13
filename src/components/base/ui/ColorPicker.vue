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
        :style="{display: pickerHidden ? 'none' : ''}"
        piker="chrome"
      />
      <input type="hidden" :name="name" v-model="selectedColor">
      <img
        id="close-picker"
        src="/img/close-circular-button-symbol.svg"
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
      selectedColor: this.color,
      pickerHidden: true,
    }
  },
  props: {
    color: {
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
    }
  },

  watch: {
    pickerHidden(newVal, oldVal) {
      this.$el.style.zIndex = newVal ? 'auto' : 10
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
      this.pickerHidden = true
      this.$emit('update', this.selectedColor)
    }
  },

}
</script>

<style lang="scss" scoped>

.picker-block {
  border-radius: 15px;
  box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.16);
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
      bottom: -251px;
      right: -63px;
    }
  }
}


#close-picker {
  width: 20px;
  position: absolute;
  right: -90px;
  bottom: -28px;
}
</style>
