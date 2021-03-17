<template>
  <sweet-modal
      class="modal text-left"
      ref="paymentModal"
      width="915"
      overlay-theme="dark"
  >

    <div>

      <select v-model="currentService" class="service-select" :class="{ active: currentService !== null}">

        <option :value="null">Select Payment Service</option>

        <template v-for="service in services">
          <option :value="service.id">{{ service.title }}</option>
        </template>

      </select>

    </div>

    <div v-for="(tab, index) in listServices">
      <component :is="tab" v-show="index === currentService" :ref="'tab' + index"/>
    </div>

  </sweet-modal>
</template>

<script>
import StripeService from "./StripeService";

export default {
  name: "payment-service-modal",

  components: {
    StripeService
  },

  data() {
    return {
      currentService: null,
      listServices: [
        null, StripeService
      ],
      services: [
        { id: 1, title: 'Stripe' },
        { id: 2, title: 'PayPal' }
      ]
    }
  },

  methods: {

    open() {
      this.$refs.paymentModal.open()
    },

  }

}
</script>

<style scoped lang="scss">
.service-select {
  background-color: white;
  padding: 15px 5px;
  text-align: center;
  width: 300px;
  outline: none;
  border-radius: 50px;
  border: solid 1px #256474;
  cursor: pointer;
  &.active {
    background-color: #5e99ad;
  }
}
.service-select:hover {
  background-color: #5e99ad;
}
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  text-indent: 1px;
  text-overflow: '';
}
</style>
