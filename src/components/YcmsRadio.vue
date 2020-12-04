<template>
  <div class="flex-center-all">
    <div class="ycms-radio" @click="check">
      <input
        ref="radio"
        type="radio"
        :name="name"
        :value="value"
        :id="radioId"
      >
      <div class="outer">
        <div v-if="isChecked" class="inner"></div>
      </div>
    </div>
    <label v-if="labelTxt" :for="radioId">{{ labelTxt }}</label>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isChecked: false,
    }
  },
  props: {
    name: String,
    value: String,
    checked: Boolean,
    radioId: String,
    showsBrowse: {
      type: Boolean,
      default: false
    },
    labelTxt: String,
  },
  methods: {
    check() {
      this.$root.$emit('radio:clicked', {
        name: this.name, value: this.value
      })
    },
    onChange() {
      console.log(this.$refs.radio.checked)
    },
  },
  watch: {
    isChecked(newVal, oldVal) {
      // console.log(`Changed to ${newVal}`)
    }
  },
  computed: {
    example() {
      return 'example'
    },
  },
  mounted() {
    this.$root.$on('radio:clicked', data => {
      if (this.name == data.name && this.value == data.value) {
        this.$refs.radio.checked = true
      } else if (this.name == data.name && this.value != data.value) {
        this.$refs.radio.checked = false
      }

      this.isChecked = this.$refs.radio.checked

      if (this.showsBrowse) {
        this.$el.closest('form')
          .getElementsByClassName('browse')[0]
          .hidden = !this.isChecked
      }
    })

    if (this.checked) {
      this.$root.$emit('radio:clicked', {
        name: this.name, value: this.value
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.ycms-radio {
  display: inline-block;
  margin-right: 10px;
}

label {
  margin: 0;
  font-size: 12px;
  font-weight: 300;
  color: #393c40;
}

input {
  display: none;
}

.outer {
  width: 20px;
  height: 20px;
  border-radius: 20px;
  border: solid 1px #707070;
  padding: 4px;

  .inner {
    width: 10px;
    height: 10px;
    border-radius: 10px;
    background-image: linear-gradient(225deg, #1de9b6, #1dc4e9);
  }
}
</style>
