import axios from "axios";

export function initialize(store, router) {

  router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const currentUser = store.state.auth.currentUser

    if (requiresAuth && !currentUser) {
      next('/login');
    } else if (to.path === '/login' && currentUser) {
      next('/');
    } else {
      next();
    }
  });

  if (process.env.NODE_ENV !== 'development') {
    axios.interceptors.response.use((response) => {
      return response
    }, (error) => {
      if (error) {
        store.commit('logout')
        router.push('/login')
      }

      return Promise.reject(error);
    });
  }

  if (store.getters.currentUser) {
    setAuthorization(store.getters.currentUser.token);
  }
}

export function setAuthorization(token) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}


export const switcher = (res = null) => {

  if (res === null) {
    return localStorage.getItem('drawerOpen')
  } else {
    localStorage.setItem('drawerOpen', res)
    return res
  }

}

export const moduleUrl = route => {
  return `/${route.params.folder.toLowerCase()}/${route.params.moduleId}`
}

export const imageUrl = path => {
  return process.env.VUE_APP_URI + path
}

export let changeView = (currentIndex, $router) => {
  console.log(currentIndex, $router)
}
