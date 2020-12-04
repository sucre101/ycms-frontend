<template>
<!-- TODO: delete when blurry problem will be solved -->
  <!-- <sweet-modal
    class="modal text-left"
    ref="newShopModal"
    width="915"
    overlay-theme="dark"
  > -->
    <div style="text-align: left">
    <h6>Please add a new store</h6>

    <div class="tab" v-text="header" />
    <br>

    <div class="steps-slider">
      <div class="steps-container" :style="{ left: -851 * (step - 1) + 'px' }">
        <div class="step">
          <div class="col">
            <input
              class="required"
              placeholder="Name of the Shop"
              v-model="shop.name"
            >
            <input
              class="required"
              placeholder="Shop Email"
              v-model="shop.email"
            >
            <input
              class="required"
              placeholder="Shop Phone"
              v-model="shop.phone"
            >
            <ycms-dropdown
              :options="['EUR', 'USD', 'RUB']"
              placeholder="Currency"
              @pick="cur => shop.currency = cur"
            />
          </div>
          <div class="col">
            <input
              class="required"
              placeholder="Country"
              v-model="shop.country"
            >
            <input
              class="required"
              placeholder="City"
              v-model="shop.city"
            >
            <input
              class="required"
              placeholder="Address"
              v-model="shop.address"
            >
          </div>
        </div>
        <div class="step">
          2
        </div>
        <div class="step">
          3
        </div>
        <div class="step">
          4
        </div>
      </div>
    </div>

    <br>

    <div class="step-nums">
      <hr size="1">
      <div v-for="i in 4" :key="i">
        <div :class="{ blue: step == i }">{{ i }}</div>
      </div>
    </div>

    <br>

    <div class="steps-controls">
      <a
        class="small-rounded-btn back mr-15"
        @click="prevStep"
        :class="{disabled: step == 1}"
      >
        <img v-if="step != 1" src="/img/dropleft-icon.svg">
        Back
      </a>
      <a class="small-rounded-btn active next mr-15" @click="nextStep">
        Next
        <img src="/img/dropleft-icon.svg">
      </a>
    </div>
    </div>
  <!-- </sweet-modal> -->
</template>

<script>
export default {
  name: 'new-shop-modal',

  data() {
    return {
      step: 1,
      shop: {}
    }
  },

  methods: {
    open() {
      this.$refs.newShopModal.open()
    },
    prevStep() {
      if (this.step > 1) --this.step
    },
    nextStep() {
      if (this.step < 4) ++this.step
    },
  },

  computed: {
    header(){
      let headers = {
        1: 'General',
        2: 'General2',
        3: 'General3',
        4: 'General4',
      }
      return headers[this.step]
    },
  },
}
</script>

<style lang="scss" scoped>

.tab {
  width: 114px;
  border-bottom: solid 1px #0997b1;
  position: relative;
  color: #0997b1;
  font-size: 14px;
  cursor: pointer;

  &:after {
    content: "";
    position: absolute;
    display: block;
    width: 73px;
    height: 1px;
    border-bottom: solid 1px #393c40;
    bottom: -4px;
  }
}

.steps-slider {
  position: relative;
  overflow: hidden;
  height: $stepHeight;

  .steps-container {
    position: absolute;
    width: calc(851px * 4);
    transition: left 0.5s;
    display: flex;

    .step {
      display: flex;
      width: 851px;
      height: $stepHeight;

      .col {
        width: 385px;
      }

      input {
        width: 346px;
        height: 43px;
        border-radius: 22px;
        border: solid 1px #868686;
        outline: none;
        font-size: 14px;
        font-weight: 300;
        color: #0997b1;
        padding-left: 22px;
        margin-bottom: 14px;
      }

      .required {
        background: no-repeat url(/img/required-star.svg) 325px 18px;
      }
    }
  }
}

.step-nums {
  display: flex;
  justify-content: space-around;
  position: relative;

  > hr {
    position: absolute;
    width: 76%;
    top: 50%;
    margin: 0;
    border: 0 none;
    border-bottom: solid 1px black;
    height: 0px;
    color: #707070;
    background-color: #707070;
  }

  > div {
    background: white;
    padding: 20px;
    position: relative;

    > div {

      width: 49px;
      height: 49px;
      border: solid 1px #2b2b2b;
      border-radius: 50%;
      font-weight: 300;

      &.blue {
        color: #0997b1;
        border-color: #0997b1;
      }
    }
  }
}

.steps-controls {
  display: flex;
  justify-content: center;
}
</style>
