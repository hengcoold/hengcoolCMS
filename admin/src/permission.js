import router from './router'
import store from './store'
import { Message } from 'element-ui'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style
import { getToken } from '@/utils/auth' // get token from cookie
import getPageTitle from '@/utils/get-page-title'

NProgress.configure({ showSpinner: false }) // NProgress Configuration

const whiteList = ['/login', '/auth-redirect'] // no redirect whitelist

router.beforeEach(async(to, from, next) => {  
  NProgress.start()
  const hasToken = getToken()
  if(hasToken){
    if(to.path === '/login'){
      next({ path: '/' })
      NProgress.done()
    }else{
       const userInfo = store.getters.userInfo
       if(!userInfo || userInfo === undefined){
        try {
          await store.dispatch('user/getInfo')
        } catch (error) {
          await store.dispatch('user/resetToken')
          Message.error(error.message || 'Has Error')
          next(`/login?redirect=${to.path}`)
        }
      }
      const hasRoles = store.getters.addRoutes && store.getters.addRoutes.length > 0
      if (hasRoles || to.path == '/index') {
        const modelArr = store.getters.modelArr && store.getters.modelArr.length > 0
        if(!modelArr){
          await store.dispatch('permission/generateModels')
        }
        if(to.path == '/index'){
          await store.dispatch('tagsView/delAllCachedVisitedViews')
        }
        next()
      } else {
        try {
          const accessRoutes = await store.dispatch('permission/generateRoutes')
          accessRoutes.push({ path: '*', redirect: '/404', hidden: true })
          router.addRoutes(accessRoutes)
          next({ ...to, replace: true })
        } catch (error) {
          await store.dispatch('user/resetToken')
          Message.error(error || 'Has Error')
          next(`/login?redirect=${to.path}`)
          NProgress.done()
        }
      }
      
      NProgress.done()
    }
  }else{
    if (whiteList.indexOf(to.path) !== -1) {
      next()
    } else {
      next(`/login?redirect=${to.path}`)
    }
    NProgress.done()
  }
  const mainInfo = store.getters.mainInfo
  if(!mainInfo){
    await store.dispatch('permission/generateMainInfo')
  }
  document.title = (store.getters.mainInfo?store.getters.mainInfo.name:'') + '-'+(store.getters.modelName?store.getters.modelName:'')+'-' + (to.meta.title?to.meta.title:'')
})

router.afterEach(() => {
  // finish progress bar
  NProgress.done()
})
