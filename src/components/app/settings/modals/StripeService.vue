<template>
  <div class="stripe-connected-form">

    <div class="input-group">
      <input v-model="service.pkey" type="text" class="input-field" placeholder="Publishable key">
    </div>
    <div class="input-group">
      <input v-model="service.skey" type="text" class="input-field" placeholder="Secret key">
    </div>

    <div class="connect" v-if="service.pkey !== '' && service.skey !== '' && !showSave" @click="sendTest">
      Try to connect
    </div>

    <div v-if="showSave" class="save-btn" @click="_saveService">
      SAVE
    </div>

  </div>
</template>

<script>
export default {
  name: "stripe-service",

  data() {
    return {
      service: {
        id: 1,
        pkey: '',
        skey: ''
      },
      showSave: false,
    }
  },

  methods: {

    sendTest() {

      let slug = this.$root.$children[0].app.slug

      axios.post(`${slug}/payment/test/stripe`, this.service)
        .then((res) => {

          if (res.data.success) {

            this.showSave = true

          } else {
            this.notifier.alert('Stripe Service ' + res.data.error)
          }

        })
    },

    _saveService() {

      let slug = this.$root.$children[0].app.slug

      axios.post(`${slug}/payment/save`, { id: this.service.id, data: [
          { title: 'secret key', value: this.service.skey },
          { title: 'publish key', value: this.service.pkey }
        ]})
        .then((res) => {
          console.log(res)
        })
    }

  }

}
</script>

<style scoped lang="scss">
.stripe-connected-form {
  display: flex;
  flex-direction: column;
  width: 60%;
  margin: auto;
  margin-top: 20px;
  .input-group {
    width: 100%;
  }
  .connect {
    font-size: 20px;
    cursor: pointer;
    &:hover {
      color: #5e99ad;
    }
  }
  .save-btn {
    display: flex;
    width: 150px;
    height: 50px;
    border-radius: 15px;
    color: #5E99AD;
    border: 1px solid #5E99AD;
    align-self: center;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    &:hover {
      background-color: #5E99AD;
      color: #ffffff;
    }
  }
}
</style>
