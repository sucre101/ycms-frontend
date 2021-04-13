<template>
  <div class="table-list-items">

    <h4>Payment services</h4>

    <div class="payment-list">

      <div class="service" v-for="(service, index) in services">
        <div class="service-label">{{ service.label || localServices[service.payment_service_id].label }}</div>
        <div class="actions">
          <ToggleCheck :value="service.active" @complete="changeActive($event, service)"/>
          <div class="btn-action blue" @click="_editService(service.id)">Edit</div>
          <div class="btn-action delete"></div>
        </div>
      </div>

      <div class="create-button" @click="$refs.payment.open()">Add</div>

      <payment-service-modal ref="payment"/>

    </div>
  </div>
</template>

<script>
import PaymentServiceModal from "../modals/PaymentServiceModal"
import {paymentServices} from "@/store/getters";
import ToggleCheck from "@/components/base/ui/ToggleCheck"

export default {
  name: "list-service",

  components: {
    PaymentServiceModal, ToggleCheck
  },

  data() {
    return {
      services: [],
      localServices: paymentServices,
      slug: this.$root.$children[0].app.slug
    }
  },

  created() {
    this._getPaymentServicesList()
  },

  methods: {

    changeActive($event, service) {

      let $filterActive = this._.filter(this.services, (val) => {
        return val.active === true
      })

      if ($event.value === false && !$filterActive.length) {
        this.notifier.success('ajskldjaslda')
        service.active = !$event.value
        return false
      }

      if ($event.value === true && $filterActive.length) {
        this.notifier.success('1323')
        this.$set(service, 'active', !$event.value)
        return false
      }

      // console.log($filterActive)

      // return false

      axios.patch(`/${this.slug}/payment/${service.id}/update-list`, { active: $event.value })
          .then((res) => {
            if (res.data.success) {
              this.services = res.data.services
            }
          })

    },

    _editService(id) {
      this.$router.replace({ query: { tab: 'payment', service: id } })
      this.$parent.editService = true
    },

    _getPaymentServicesList() {

      axios.get(`/${this.slug}/payment`)
          .then((res) => {
            if (res.data.success) {
              this.services = [ ...res.data.services]
            }
          })

    }

  }
}
</script>

<style scoped lang="scss">
.payment-list {
  display: flex;
  flex-direction: column;

  .service {
    padding: 10px 5px;
    margin-bottom: 10px;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    .service-label {
      //width: 50%;
    }
    .actions {
      width: 35%;
      display: flex;
      justify-content: space-between;
    }
  }
  .create-button {
    background-image: linear-gradient(250deg, #50b109, #0997b1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 600;
    color: #ffffff;
    width: 136px;
    height: 50px;
    border-radius: 5px;
    box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
    cursor: pointer;
    align-self: flex-end;
    margin-top: 50px;
  }
}
</style>
