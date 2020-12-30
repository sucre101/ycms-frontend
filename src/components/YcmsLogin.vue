<template>
  <div class="ycms-login ">
    <h6>{{ header }}</h6>

    <ycms-tabs
      :tabs="['Sign In', 'Sign Up']"
      width="346px"
      @selected="setContext"
      ref="tabs"
    />
    <div class="name-block">
      <input
        class="rounded-input input-name"
        placeholder="First Name"
        v-model="data.name"
        v-gref:name
        v-if="context === 'Sign Up'"
      >
      <input
        class="rounded-input input-name"
        placeholder="Last Name"
        v-model="data.lastname"
        v-gref:lastname
        v-if="context === 'Sign Up'"
      >
    </div>
    <input
      class="rounded-input required"
      placeholder="your@email.com"
      type="email"
      v-model="data.email"
      v-gref:email
    >
    <input
      class="rounded-input required"
      type="password"
      v-model="data.password"
      placeholder="Password"
      @keyup.enter="submit"
      v-gref:password
      v-if="['Sign In', 'Sign Up', 'reset'].includes(context)"
    >
    <input
      class="rounded-input required"
      type="password"
      v-model="data.password_confirmation"
      placeholder="Password Confirmation"
      v-gref:passwordConfirmation
      v-if="['Sign Up', 'reset'].includes(context)"
    >

    <div style="height: 10px"></div>

    <div
      class="ycms-button bg-green-gradient w-346"
      @click="submit"
    >
      {{ buttonText }}
    </div>

    <br>

    <p class="blue-link" @click="setSignInContext" v-if="context !== 'Sign In'">
      {{ singInLinkText }}
    </p>
    <p class="blue-link" @click="setForgotContext" v-if="context === 'Sign In'">
      Forgot your password?
    </p>
    <div class="policy-notice" v-if="context === 'Sign Up'">
      By clicking “<b>GET STARTED</b>” I agree to YappiX’s
      <a class="blue-text" href="#">Terms of Service</a>,
      <a class="blue-text" href="#">Privacy Policy</a> and
      <a class="blue-text" href="#">Prohibited Product List</a>.
    </div>
    <p class="blue-link" @click="setSignUpContext" v-if="context !== 'Sign Up'">
      Not a YappiX client, yet?
    </p>
  </div>
</template>

<script>
import validation from '../v-alidation'
import YcmsTabs from "./YcmsTabs";
import {login} from "@/helpers/auth";

export default {
  name: 'ycms-login',

  components: {
    YcmsTabs
  },

  mixins: [validation],

  data() {
    return {
      context: this._context,
      data: {},
    }
  },

  props: {
    _context: {type: String, default: 'Sign In'},
    email: String,
    token: String,
  },

  computed: {
    header() {
      if (this.context === 'Sign In') {
        return 'Sign in to your account'
      }
      if (this.context === 'Sign Up') {
        return 'Sign Up for Free'
      }
      if (this.context === 'restore') {
        return 'Please enter new password'
      }
      if (this.context === 'reset') {
        return 'Please enter new password'
      }
      return 'Forgot your password?' // Forgot
    },
    buttonText() {
      if (this.context === 'Sign In') {
        return 'LOGIN'
      }
      if (this.context === 'Sign Up') {
        return 'GET STARTED'
      }
      if (this.context === 'restore') {
        return 'SEND EMAIL'
      }
      if (this.context === 'reset') {
        return 'RESET'
      }
      return 'SEND EMAIL' // Forgot
    },
    singInLinkText() {
      if (this.context === 'Sign Up') {
        return 'Already have an account?'
      }
      return 'Already have a password?' // Forgot
    },
    path() {
      if (this.context === 'Sign In') {
        return '/ycms/auth/login'
      }
      if (this.context === 'Sign Up') {
        return '/register'
      }
      if (this.context === 'reset') {
        return '/password/reset'
      }
      return '/password/email' // Forgot
    },
    verificationConfig() {
      let config = [
        {
          subj: 'grefs.email.value.length',
          ref: 'grefs.email',
          message: 'Please fill email field'
        },
      ]
      if (this.context === 'Sign In')
        config.push({
          subj: 'grefs.password.value.length',
          ref: 'grefs.password',
          message: 'Please fill password field'
        })

      return config
    },
    authError() {
      return this.$store.getters.authError;
    }
  },

  mounted() {
    if (!['Sign In', 'Sign up', 'forgot', 'reset'].includes(this.context)) {
      this.notifier.warning(`Context ${this.context} is under development`)
    }

    if (this.token) {
      this.data.token = this.token
    }
    if (this.token) {
      this.data.email = this.email
    }
    if (this.email) {
      grefs.email.value = this.email
    }
  },

  methods: {
    submit() {

      this.$store.dispatch('login');
      login(this.data)
        .then((res) => {
          this.$store.commit("loginSuccess", res);
          this.$router.push({path: '/'});
        })
        .catch((error) => {
          this.$store.commit("loginFailed", {error});
        });
    },

    setContext(context) {
      this.context = context
    },

    setSignInContext() {
      this.$refs.tabs.active = 0
      this.context = 'Sign In'
    },

    setSignUpContext() {
      this.$refs.tabs.active = 1
      this.context = 'Sign Up'
    },

    setForgotContext() {
      this.$refs.tabs.active = 3
      this.context = 'forgot'
    }
  }
}
</script>

<style lang="scss" scoped>


.ycms-login {
  width: 418px;
}

.blue-link {
  text-align: center;
  color: #0997b1;
  cursor: pointer;
  margin-bottom: 15px;
}

.name-block {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  padding: 0 2%;

  input {
    width: 48%;
  }
}

.policy-notice {
  text-align: center;
}

.blue-text {
  color: #0997b1
}
</style>
