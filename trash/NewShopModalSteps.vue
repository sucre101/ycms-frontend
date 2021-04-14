<template>
  <sweet-modal
    class="modal text-left"
    ref="newShopModal"
    width="915"
    overlay-theme="dark"
  >
    <div style="text-align: left">
    <h6>Please add a new store</h6>

    <div class="tab" v-text="header" />
    <br>

    <div class="steps-slider">
      <div class="steps-container" :style="{ left: -851 * (step - 1) + 'px' }">
        <div class="step">
          <div class="cols">
            <div class="col">
              <input
                ref="name"
                class="required"
                placeholder="Name of the Shop"
                v-model.trim="shop.name"
              >
              <input
                ref="email"
                class="required"
                placeholder="Shop Email"
                v-model.trim="shop.email"
              >
              <input
                ref="phone"
                class="required"
                placeholder="Shop Phone"
                v-model.trim="shop.phone"
              >
            </div>
            <div class="col">
              <ycms-dropdown
                ref="currency"
                :options="['EUR', 'USD', 'RUB']"
                placeholder="Currency"
                @pick="cur => shop.currency = cur"
              />
              <input
                ref="taxRate"
                class="required"
                placeholder="Tax rate %"
                type="number"
                v-model="shop.taxRate"
              >
              <input
                ref="taxName"
                class="required"
                placeholder="Tax name"
                v-model.trim="shop.taxName"
              >
            </div>
          </div>
          <ycms-address-searcher ref="addrSearcher" @pick="setAddrGetCoords"/>
          <p class="tip">
            * Please enter your full address in your native language.
          </p>
        </div>
        <div class="step">
          <div class="payment-method">
            <toggle-button
              v-model="shop.payment['2checkout']"
              :color="{checked: '#0997B1', unchecked: '#868686'}"
              :width="51"
              :height="31"
              :margin="2"
            />
            2checkout
          </div>
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
  </sweet-modal>
</template>

<script>
export default {
  name: 'new-shop-modal',

  data() {
    return {
      step: 1,
      shop: {
        payment: {},
      }
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
      let stepIsVerified = this.verifyStep()
      if (this.step < 4 && stepIsVerified) ++this.step
    },
    setAddrGetCoords(addr) {
      this.shop.address = addr.description
      new gMaps.Geocoder().geocode(
        {'address': addr.description},
        res => {
          this.shop.country = res[0].address_components
            .find(c => c.types.includes('country')).long_name
          this.shop.city = res[0].address_components
            .find(c => c.types.includes('locality')).long_name
          this.shop.lat = res[0].geometry.location.lat()
          this.shop.lon = res[0].geometry.location.lng()
        }
      )
    },
    verifyStep() {
      let setInputValidity = (ref, message, isValid) => {
        let el = this.$refs[ref]
        let classes = type(el) == 'object' ? el.$el.classList : el.classList
        let operator = isValid ? 'remove' : 'add'
        classes[operator]('error')
        if (!isValid) notifier.warning(message)
      }

      let validate = fields => {
        try {
          fields.forEach(f => {
            let valid = false

            if (type(f.subj) == 'string') {
              valid = !!this.shop[f.subj]
            } else if (type(f.subj) == 'array') {
              valid = f.subj.every(el => !!this.shop[el])
            }

            let ref = type(f.subj) == 'string' ? f.subj : f.ref
            setInputValidity(ref, f.message, valid)

            if (valid == false) throw('Not valid')
          })
        } catch(err) {
          console.error(err);
          return false
        }
        return true
      }

      if(this.step == 1) {
        // return validate([
        //   {subj: 'name', message: 'Please fill shop name field'},
        //   {subj: 'email', message: 'Please fill email field'},
        //   {subj: 'phone', message: 'Please fill phone field'},
        //   {subj: 'currency', message: 'Please select currency for your shop'},
        //   {subj: 'taxRate', message: 'Please fill tax rate field'},
        //   {subj: 'taxName', message: 'Please fill tax name field'},
        //   {
        //     subj: ['address', 'city', 'country', 'lat', 'lon'],
        //     ref: 'addrSearcher',
        //     message: `Address is not valid. Try to fill it again and select one
        //     of suggested variants`
        //   }
        // ])
        return true
      }
      if(this.step == 2) {
        let isValid = Object.values(this.shop.payment).includes(true)
        if (!isValid)
          notifier.warning('Please choose at least one of payment method')
        return isValid
      }
    }
  },

  computed: {
    header(){
      let headers = {
        1: 'General',
        2: 'Payment methods',
        3: 'General3',
        4: 'General4',
      }
      return headers[this.step]
    },
  },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/mixins';
$stepHeight: 430px;

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
      width: 851px;
      height: $stepHeight;

      .cols {
        display: flex;

        .col {
          width: 385px;
        }
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
        padding: 0 22px;
        margin-bottom: 14px;

        &.error {
          border-color: #b40000;
        }
      }

      .required {
        background: no-repeat url(/img/required-star.svg) 325px 18px;
      }

      .tip {
        font-size: 14px;
        color: #b40000;
        padding-left: 15px;
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
      @include flex-center-all();
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

.tab {
  width: unset;
  display: inline-block;
  margin-bottom: 20px;
}

.payment-method {
  display: flex;
  align-items: center;
  font-weight: 300;
  color: #0997b1;

  .vue-js-switch {
    margin-bottom: 0;
    margin-right: 34px;
  }
}
</style>
