<template>
  <div class="enter-page">

    <h4>{{ enterTitle }}</h4>

    <InnerTab
        :items="tabs"
        @change="selectTab"
    />

    <div class="card">

      <template v-if="currentTab === 0 && !isAuth">
        <form @submit.prevent="authenticate">
          <div class="form-group">
            <input type="email" v-model="form.email" class="form-control required" placeholder="Email Address" autocomplete="off">
          </div>
          <div class="form-group">
            <input type="password" v-model="form.password" class="form-control required" placeholder="Password" autocomplete="off">
          </div>
          <div class="form-group">
            <input type="submit" value="Login" @keyup.enter="authenticate" class="ycms-button bg-green-gradient">
          </div>
          <div class="form-group" v-if="authError">
            <p class="error">
              {{ authError }}
            </p>
          </div>
        </form>
      </template>

      <template v-if="currentTab === 1 && !isAuth">

        REGISTER

      </template>

    </div>
  </div>
</template>

<script>
import {login} from '@/helpers/auth'
import InnerTab from "@/components/base/ui/InnerTab"

export default {

  name: "login",

  components: {
    InnerTab
  },

  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      error: null,
      currentTab: 0,
      tabs: [
        {name: 'Sign In'},
        {name: 'Sign Up'}
      ],
      isAuth: false,
    };
  },

  computed: {
    authError() {
      return this.$store.getters.authError;
    },

    enterTitle() {
      return this.currentTab === 0 ? 'Sign in to your account' : 'Registration account'
    },

    title() {
      return this.currentTab === 0 ? 'Login page' : 'Register page'
    }

  },

  created() {
    window.setTitle(this.title)
  },

  methods: {

    waitForLoad() {
      return new Promise(resolve => {
        resolve();
      })
    },

    authenticate() {
      this.$store.dispatch('login')
      login(this.$data.form)
          .then((res) => {

            this.$store.commit("loginSuccess", res)

            this.waitForLoad()
              .then(res => this.$router.push({ name: 'apps' }))

          })
          .catch((error) => {
            this.$store.commit("loginFailed", {error})
          });
    },

    selectTab(index) {
      this.currentTab = index
    }

  }
}
</script>

<style scoped lang="scss">
.error {
  text-align: center;
  color: red;
}
.card {
  .form-group {
    margin-bottom: 20px;
    input[type="text"], input[type="email"], input[type="password"] {
      border-radius: 22px;
      border: solid 1px #868686;
      background: #fff;
      filter: none;
      width: 100%;
      display: flex;
      height: 40px;
      padding: 0 25px;
      color: #0997b1;
      font-size: 14px;
    }
  }
  .ycms-button {
    margin-top: 35px;
    border: none;
    width: 100%;
  }
}
</style>
