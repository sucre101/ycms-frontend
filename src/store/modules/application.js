export default {
  state: {
    application: localStorage.getItem('app'),
    phoneBlockSize: localStorage.getItem('phoneSize')
  },
  getters: {
    getApplication(state) {
      return state.application;
    },
    getSizePhone(state) {
      return state.phoneBlockSize
    }
  },
  mutations: {
    setApplication(state, payload) {
      localStorage.setItem('app', payload)
    },
    unsetApplication(state) {
      state.application = {}
      localStorage.removeItem('app')
    },
    setPhoneSize(state, payload) {
      localStorage.setItem('phoneSize', payload)
    }
  },
  actions: {
    setApp(context) {
      context.commit('setApplication')
    }
  }
}
