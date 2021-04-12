<template>
  <sweet-modal
    class="modal text-left"
    ref="newShopModal"
    width="915"
    overlay-theme="dark"
  >
    <div style="text-align: left">
      <h6>Please add a new store</h6>

      <div class="content">
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

            <the-mask :mask="['+# (###) ###-##-##']" class="required" ref="phone" placeholder="+* (***) ***-**-**"/>

          </div>
          <div class="col">
            <ycms-dropdown
              ref="currency"
              :options="$parent.currencies"
              placeholder="Currency"
              @pick="cur => shop.currency = cur.id"
            />
            <input
              ref="tax_rate"
              class="required"
              placeholder="Tax rate %"
              type="number"
              v-model="shop.tax_rate"
            >
            <input
              ref="tax_name"
              class="required"
              placeholder="Tax name"
              v-model.trim="shop.tax_name"
            >
          </div>
        </div>
        <ycms-address-searcher ref="addrSearcher" @pick="setAddrGetCoords"/>
        <p class="tip">
          * Please enter your full address in your native language and select
          one of suggested variants
        </p>
      </div>

      <br>

      <div class="steps-controls">
        <a
          class="small-rounded-btn arrow-left"
          @click="$refs.newShopModal.close()"
        >
          <img src="/img/dropleft-icon.svg">
          Cancel
        </a>
        <a class="small-rounded-btn active arrow-right" @click="createShop">
          <img src="/img/dropleft-icon.svg">
          Create
        </a>
      </div>
    </div>
  </sweet-modal>
</template>

<script>
import YcmsDropdown from "./YcmsDropdown"
import YcmsAddressSearcher from "./YcmsAddressSearcher"
import {TheMask} from 'vue-the-mask'

export default {
  name: 'new-shop-modal',

  components: {
    YcmsDropdown, YcmsAddressSearcher, TheMask
  },

  data() {
    return {
      shop: {}
    }
  },

  methods: {

    open() {
      this.$refs.newShopModal.open();
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

    verify() {
      let setInputValidity = (ref, message, isValid) => {
        let el = this.$refs[ref]
        let classes = type(el) == 'object' ? el.$el.classList : el.classList
        let operator = isValid ? 'remove' : 'add'
        classes[operator]('error')
        if (!isValid) this.notifier.warning(message)
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

            let ref = f.ref || f.subj
            setInputValidity(ref, f.message, valid)

            if (valid == false) throw('Not valid')
          })
        } catch (err) {
          return false
        }
        return true
      }

      return validate([
        {subj: 'name', message: 'Please fill shop name field'},
        {subj: 'email', message: 'Please fill email field'},
        {subj: 'phone', message: 'Please fill phone field'},
        {subj: 'currency', message: 'Please select currency for your shop'},
        {subj: 'tax_rate', message: 'Please fill tax rate field'},
        {subj: 'tax_name', message: 'Please fill tax name field'},
        {
          subj: ['address', 'city', 'country', 'lat', 'lon'],
          ref: 'addrSearcher',
          message: `Address is not valid. Try to fill it again and select one
          of suggested variants`
        }
      ])
    },
    createShop() {
      this.$emit('submitted', this.shop)
      this.$refs.newShopModal.close()
    },
  },
}
</script>

<style lang="scss" scoped>


$stepHeight: 430px;

.content {
  width: 770px;
  height: $stepHeight;
  margin: 0 auto;

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

.steps-controls {
  display: flex;
  justify-content: center;
}
</style>
