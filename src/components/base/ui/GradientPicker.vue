<template>
  <div class="picker-block">
    <div
      class="gradient-selector"
      :style="{backgroundImage: newGradient, zIndex}"
      @mouseover="showControls = true"
      @mouseleave="onMouseLeave"
    >
      <input type="hidden" :name="name" v-model="newGradient">

      <template v-if="showControls">
        <div
          class="color-control"
          :style="{backgroundColor: newColor1}"
          @click="showPicker('colorPicker1')"
        ></div>
        <img
          class="direction-control"
          src="@/assets/img/strelka.svg"
          @click="showPicker('directionPicker')"
          :style="{transform: `rotate(${newDirection})`}"
        >
        <div
          class="color-control"
          :style="{backgroundColor: newColor2}"
          @click="showPicker('colorPicker2')"
        ></div>
      </template>

      <div class="color-picker first" v-if="colorPicker1Visible">
        <vue-color
          :value="newColor1"
          @input="updateColor($event, 1)"

        ></vue-color>
        <img
          src="@/assets/img/close-circular-button-symbol.svg"
          @click="closeAndUpdate('colorPicker1Visible')"
        >
      </div>

      <div class="color-picker second" v-if="colorPicker2Visible">
        <vue-color
          :value="newColor2"
          @input="updateColor($event, 2)"
        ></vue-color>
        <img
          src="@/assets/img/close-circular-button-symbol.svg"
          @click="closeAndUpdate('colorPicker2Visible')"
        >
      </div>

      <div class="direction-picker" v-if="directionPickerVisible">
        <div class="pickers">
          <img
            v-for="(direction, i) in directions"
            src="@/assets/img/strelka.svg"
            :style="{transform: `rotate(${direction}deg)`}"
            :key="i"
            @click="newDirection = direction + 'deg'"
          >
        </div>
        <img
          src="@/assets/img/close-circular-button-symbol.svg"
          @click="closeAndUpdate('directionPickerVisible')"
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'gradient-picker',

  data() {
    return {
      showControls: false,
      colorPicker1Visible: false,
      newColor1: this.color1,
      colorPicker2Visible: false,
      newColor2: this.color2,
      directionPickerVisible: false,
      newDirection: this.direction,
      directions: [0, 180, 45, 225, 90, 270, 135, 315], //degrees
    }
  },

  props: {
    name: {
      type: String,
      default: 'top-bar'
    },
    gradient: {
      type: String,
      default: "linear-gradient(90deg, rgba(214, 89, 23, 0.99), rgba(48, 91, 134, 0.99))"
    }
  },

  computed: {

    newGradient() {
      const parts = [this.newDirection, this.newColor1, this.newColor2]
      return `linear-gradient(${parts.join(', ')})`
    },

    pickerOpen() {
      return this.colorPicker1Visible
        ||
        this.colorPicker2Visible
        ||
        this.directionPickerVisible
    },

    zIndex() {
      return this.pickerOpen ? 10 : 'auto'
    },
  },

  watch: {
    pickerOpen(val) {
      this.showControls = val
    }
  },

  mounted() {
    this.parseGradient()
  },

  methods: {

    closeAndUpdate(element) {
      switch (element) {
        case 'directionPickerVisible':
          this.directionPickerVisible = false
          break
        case 'colorPicker1Visible':
          this.colorPicker1Visible = false
          break
        case 'colorPicker2Visible':
          this.colorPicker2Visible = false
          break
      }
      this.$emit('update', this.newGradient)
    },

    updateColor($event, colorNum) {
      this[`newColor${colorNum}`] = `rgba(${Object.values($event.rgba).join(', ')})`
    },

    onMouseLeave() {
      this.showControls = !!this.pickerOpen
    },

    showPicker(pickerToShow) {
      ['colorPicker1', 'colorPicker2', 'directionPicker'].forEach(picker => {
        this[`${picker}Visible`] = picker === pickerToShow
      })
    },

    parseGradient() {
      let regex = /.+gradient\((.+?),\s*(.+?\)),\s*(.+?\))/, fullMatch
      [fullMatch, this.newDirection, this.newColor1, this.newColor2]
        = regex.exec(this.gradient)
    },

  }
}
</script>

<style lang="scss" scoped>
.picker-block {
  border-radius: 15px;
  box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.16);
  border: solid 3px rgba(181, 181, 181, 0.47);
  background-image: linear-gradient(160deg, #a7a7a7 17%, #e5e5e5 43%, #a7a7a7 68%);
  display: inline-block;

  img {
    cursor: pointer;
  }
}

.gradient-selector {
  position: relative;
  width: 95px;
  height: 20px;
  border-radius: 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2px;
}

.color-control {
  width: 17px;
  height: 17px;
  border-radius: 50%;
  border: solid 1px rgba(255, 255, 255, 0.267);
}

.direction-control, .color-control {
  cursor: pointer;
}

.direction-control:hover {
  border: solid 4px #ecedef88;
  border-radius: 50%;
}

.color-picker {
  position: absolute;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  width: 249px;
  height: 244px;
  bottom: -250px;

  &.first {
    right: -47px;
  }

  &.second {
    right: -122px;
  }
}

.direction-picker {
  position: absolute;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  background: no-repeat url(/img/popup-gradient.svg);
  width: 118px;
  height: 146px;
  right: -108px;
  top: -41px;
  padding-left: 23px;

  > img {
    margin-top: 8px;
  }

  .pickers {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    align-items: baseline;
    margin-top: 10px;
    width: 67px;
    height: 125px;

    > img {
      border: solid 4px transparent;
      border-radius: 50%;
    }

    > img:hover {
      border-color: #ecedef;
    }
  }
}
</style>

