export default {
  state: {
    sidebar: localStorage.getItem('sidebar')
  },
  getters: {
    getSidebar(state) {
      if (state.sidebar === 'true') {
        return true
      } else if (state.sidebar === 'undefined') {
        return false
      } else {
        return false
      }
    }
  }
}