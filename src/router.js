import Vue from 'vue'
import VueRouter from "vue-router"
import access from "./middleware/access"
import auth from "./middleware/auth"

Vue.use(VueRouter)

export default new VueRouter({

  mode: 'history',
  linkActiveClass: "active",
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
      name: 'dashboard',
      component: () => import('./components/dashboard/Index'),
      meta: {
        middleware: [auth],
        title: 'Dashboard'
      }
    },
    {
      path: '/apps',
      name: 'apps',
      component: () => import('./components/app/List'),
      meta: {
        middleware: [auth],
        title: 'Applications'
      }
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('./components/profile/Index'),
      meta: {
        middleware: [auth],
        title: 'Profile settings'
      }
    },
    {
      path: '/app',
      redirect: '/app/pages',
      name: 'app',
      component: () => import('./components/app/Index'),
      meta: {
        middleware: [auth],
      },
      children: [
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
                title: 'Your modules'
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
            title: 'List of page',
            middleware: [access]
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
    {
      path: '/marketplace',
      name: 'marketplace',
      component: () => import('./components/marketplace/Index'),
      meta: {
        middleware: [auth],
        title: 'Marketplace'
      }
    },
    {
      path: '/settings',
      name: 'settings',
      component: () => import('./components/settings/Index'),
      meta: {
        middleware: [auth],
        title: 'Settings'
      }
    },
  ]

})
