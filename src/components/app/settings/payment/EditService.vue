<template>
  <div class="table-list-items">

    <h4 v-if="!loading">{{ serviceGetter[service.payment_service_id].label }}</h4>

    <div class="logo" v-if="!loading">
      <img :src="serviceGetter[service.payment_service_id].logo" alt="">
    </div>

    <div class="form">

      <div class="input-group">
        <input type="text" v-model.trim="service.label" class="input-field" placeholder="Custom label">
      </div>

      <div class="input-group" v-for="field in fields">
        <input type="text" v-model.trim="field.value" class="input-field" :placeholder="field.title">
      </div>

      <div class="btn-action blue" @click="_save" v-if="activeForSave">
        Save
      </div>

    </div>
  </div>
</template>

<script>
import {paymentServices} from "@/store/getters"

export default {
  name: "edit-service",

  data() {
    return {
      serviceId: null,
      fields: [],
      serviceGetter: paymentServices,
      service: {},
      slug: this.$root.$children[0].app.slug,
      activeForSave: false,
      loading: true,
    }
  },

  created() {
    this.serviceId = Number(this.$route.query.service)
    this._loadServiceData()
  },

  watch: {

    $route(to, from) {

      if (!to.query.hasOwnProperty('service')) {
        this.$parent.editService = false
      }
    },

    fields: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    },

    service: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    }

  },

  methods: {

    _loadServiceData() {

      axios.get(`/${this.slug}/payment/${this.serviceId}`)
        .then((res) => {
          this.fields = [...JSON.parse(res.data.service.payment_data)]
          this.service = res.data.service
          this.loading = false
        })
        .then(res => this.activeForSave = false)
    },

    _save() {
      axios.patch(`/${this.slug}/payment/${this.serviceId}`, {fields: this.fields, label: this.service.label})
        .then((res) => {
          if (res.data.success) {
            this.notifier.success('Saving Data')
            this.activeForSave = false
          }
        })
    }

  }
}
</script>

<style scoped lang="scss">
.form {
  display: flex;
  flex-direction: column;
  width: 60%;
  margin: auto;
  margin-top: 30px;
}
.logo {
  display: flex;
  width: 200px;
  position: absolute;
  top: 100px;
  left: 0;
  img {
    width: 100%;
  }
}
</style>
