export default {
  state: {
    open: localStorage.getItem('drawerOpen'),
  },
  getters: {
    getOpen(state) {
      return state.open;
    }
  },
  mutations: {
    setOpen(state, payload) {
      localStorage.setItem('drawerOpen', payload)
    },
    unsetOpen(state, payload) {
      localStorage.setItem('drawerOpen', payload)
    },
  },
  actions: {
    setOpen(context) {
      context.commit('setOpen', { active: true })
    },
    unsetOpen(context) {
      context.commit('unsetOpen', { active: false })
    }
  }
}
