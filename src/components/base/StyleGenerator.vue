<template>
  <div class="style-block">
    <div v-for="type in styles">
      <div>
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

        const numRules = ['width', 'height', 'border-radius', 'border-width', 'font-size', 'margin-left', 'margin-top']

        if (numRules.includes(val.target.name)) {

          if (['width', 'margin-left'].includes(val.target.name)) {
            this.value[val.target.name] = `${val.target.value}%`
          } else {
            this.value[val.target.name] = `${val.target.value}px`
          }

        } else {
          this.value[val.target.name] = `${val.target.value}`
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
