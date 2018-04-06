import router from './router'
import store from './store'
import NProgress from 'nprogress' // Progress bar
import 'nprogress/nprogress.css'// Progress bar style
import { Message } from 'element-ui'
import { getToken } from '@/utils/auth'

const whiteList = ['/login'] // Do not redirect whitelist
router.beforeEach((to, from, next) => {
  NProgress.start()
  if (getToken()) {
    if (to.path === '/login') {
      next({ path: '/' })
    } else {
      if (store.getters.role.length === 0) {
        store.dispatch('GetInfo').then(res => { // get user information
          next()
        }).catch(() => {
          store.dispatch('FedLogOut').then(() => {
            Message.error('Verification failed, please log in again')
            next({ path: '/login' })
          })
        })
      } else {
        next()
      }
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1) {
      next()
    } else {
      next('/login')
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  NProgress.done() // End Progress
})
