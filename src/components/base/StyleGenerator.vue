<template>
  <div class="style-block">
    <div v-for="type in styles">
      <div v-if="value.hasOwnProperty(type.name)">
        <label>
          {{ type.title }}
          <template v-if="type.type !== 'select' && type.type !== 'color'">
            <input :type="type.type" :min="type.min" :max="type.max" step="1" :name="type.name"
                   :value="someFunc(value[type.name])" @change="change">
          </template>

          <template v-if="type.type === 'color'">
            <ColorPicker :return-element="type.name" style="width: 100px" :input="value[type.name]" @update="change"/>
          </template>

          <template v-if="type.type === 'select'">
            <select @change="change" :name="type.name" v-model="value[type.name]">
              <template v-for="element in type.variants" v-if="type.variants">
                <option :value="element">{{ element }}</option>
              </template>
            </select>
          </template>

        </label>
      </div>
    </div>
  </div>
</template>

<script>
import {styleTypes} from "@/store/getters"
import ColorPicker from "@/components/base/ui/ColorPicker";

export default {
  name: "style-generator",

  components: {
    ColorPicker
  },

  props: {

    input: {
      type: Object,
      default: () => {
        return {}
      }
    },

    rulesException: {
      type: Array,
      default: () => {
        return []
      }
    }

  },

  data() {
    return {
      stylesType: styleTypes,
      build: {}
    }
  },

  computed: {

    value() {
      return this.input
    },

    styles() {
      return this.stylesType.filter((val) => {
        return !this.rulesException.includes(val.name)
      })
    }

  },

  methods: {

    someFunc(arg) {
      return parseInt(arg)
    },

    change(val, extClass = false) {

      if (extClass) {
        this.value[extClass] = val
      } else {
        switch (val.target.name) {
          case 'width':
            this.value['width'] = `${val.target.value}px`
            break
          case 'height':
            this.value['height'] = `${val.target.value}px`
            break
          case 'border-radius':
            this.value['border-radius'] = `${val.target.value}px`
            break
          case 'border-width':
            this.value['border-width'] = `${val.target.value}px`
            break
          case 'justify-content':
            this.value['justify-content'] = `${val.target.value}`
            break
          case 'align-items':
            this.value['align-items'] = `${val.target.value}`
            break
          case 'font-size':
            this.value['font-size'] = `${val.target.value}px`
            break
        }
      }

      this.$emit('complete', this.value)
    },


  }
}
</script>

<style scoped lang="scss">
.style-block {
  width: 100%;
  label {
    display: flex;
    flex-direction: column;
  }
}
</style>
