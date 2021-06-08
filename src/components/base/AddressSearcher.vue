<template>
  <div class="container">
    <div
        class="dropdown"
        :style="{
        height: expanded ? 43 * (predictions.length + 1) + 'px' : '43px',
        zIndex: expanded ? 10 : '',
        overflowY: expanded ? 'scroll' : ''
      }"
    >
      <input
          class="transparent-bg required"
          v-debounce="getPredictions"
          placeholder="Country / City / Address"
          v-model="query"
      >
      <div
          v-for="(prediction, i) in predictions" :key="i"
          class="item option"
          @click="pick(prediction)"
      >
        {{ prediction.description }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'address-searcher',

  props: {
    address: {
      type: String,
      default: ''
    },
    injectScript: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {
      expanded: false,
      predictions: [],
      query: '',
    }
  },

  mounted() {
    if (this.injectScript) {
      window.injectMapSearcherScript(this.initLocationSearch)
    }
  },

  created() {
    this.query = this._.cloneDeep(this.address)
  },

  methods: {

    initLocationSearch() {
      window.gMaps = google.maps
      window.gSearcher = new google.maps.places.AutocompleteService()
    },

    pick(prediction) {
      this.query = prediction.description
      this.$nextTick(() => {
        this.expanded = false
      })
      this.$emit('pick', prediction)
    },

    getPredictions(input) {
      if (input) {
        gSearcher.getQueryPredictions({input}, res => {
              if (res && res.length) {
                this.predictions = res
                this.expanded = true
              }
            }
        )
      }
    },


  },
}
</script>

<style lang="scss" scoped>
$width: 732px;

.container {
  width: $width;
  height: 43px;
  margin-bottom: 14px;

  &.error {
    .dropdown {
      border-color: #b40000;
    }
  }
}

input {
  width: 100%;
  height: 43px;
  border-radius: 22px;
  font-size: 14px;
  font-weight: 300;
  color: #0997b1;
  padding: 0 22px;
}

.required {
  background: no-repeat url(~@/assets/img/required-star.svg) 710px 18px;
}

.dropdown {
  width: 100%;
  height: 43px;
  border-radius: 22px;
  border: solid 1px #868686;
  background-color: white;
  font-size: 14px;
  font-weight: 300;
  color: #0997b1;
  transition: height 0.5s;
  overflow: hidden;
  position: relative;

  .item {
    width: 100%;
    height: 43px;
    display: flex;
    align-items: center;
    padding-left: 22px;

    &.option {
      padding-left: 22px;
      border-top: 1px solid #e2e2e2b5;
      cursor: pointer;

      &:hover {
        background-color: #e2e2e295;
      }
    }
  }
}
</style>
