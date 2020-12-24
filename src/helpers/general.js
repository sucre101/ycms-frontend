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
  //
  // axios.interceptors.request.use((res) => {
  //   console.log(res)
  // })

  axios.interceptors.response.use(null, (error) => {
    if (error.resposne.status === 401) {
      store.commit('logout');      router.push('/login');
    }

    return Promise.reject(error);
  });

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

    return  res
  }

}
