import Vue from 'vue'
import VueRouter from "vue-router"

Vue.use(VueRouter)

export default new VueRouter({

  mode: 'history',

  routes: [
    {path: '*', component: () => import('./components/base/NotFound')},
    {
      path: '/',
      redirect: '/dashboard'
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./components/auth/Login')
    },
    {
      path: '/dashboard',
      name: 'apps',
      component: () => import('./components/app/List'),
      meta: {
        requiresAuth: true,
        title: 'Dashboard'
      }
    },
    {
      path: '/settings',
      name: 'settings',
      component: () => import('./components/app/settings/Index'),
      meta: {
        requiresAuth: true,
        title: 'Settings'
      }
    },
    {
      path: '/app/:slug',
      redirect: '/app/:slug/module',
      name: 'app',
      component: () => import('./components/app/Index'),
      meta: {
        requiresAuth: true,
      },
      children: [
        {
          path: 'shop',
          name: 'shop',
          redirect: 'shop/list',
          component: () => import('./components/app/shop/Index'),
          children: [
            {
              path: 'list',
              name: 'shop-list',
              component: () => import('./components/app/shop/stores/List')
            },
            {
              path: ':store',
              name: 'store',
              component: () => import('./components/app/shop/stores/Index')
            }
          ]
        },
        {
          path: 'settings',
          name: 'app-settings',
          component: () => import('./components/app/settings/Index')
        },
        {
          path: 'module',
          name: 'module',
          component: () => import('./components/app/modules/Index'),
          redirect: 'module/list',
          children: [
            {
              path: 'list',
              name: 'module-list',
              component: () => import('./components/app/modules/List'),
              meta: {
                title: 'List of page'
              }
            },
            {
              path: ':moduleId/:folder',
              name: 'module-edit',
              component: () => import('./components/app/modules/Edit')
            },
          ]
        },
        {
          path: 'pages',
          name: 'page-list',
          component: () => import('./components/app/page/List'),
          meta: {
            title: 'List of page'
          }
        },
        {
          path: 'publication',
          name: 'publication',
          component: () => import('./components/app/publication/List'),
          meta: {
            title: 'Publications'
          }
        }
      ]
    },
  ]

})
