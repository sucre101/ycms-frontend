import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import application from "./modules/application"
import reference from "./modules/reference/index"
import sidebar from "./modules/sidebar";

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    auth,
    application,
    reference,
    sidebar
  }
})
