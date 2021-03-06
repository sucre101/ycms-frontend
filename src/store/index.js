import Vue from 'vue'
import Vuex from 'vuex'
import auth from '@/store/modules/auth'
import application from "@/store/modules/application"
import reference from "@/store/modules/reference/index"

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    auth,
    application,
    reference,
  }
})
