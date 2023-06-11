import { asyncRoutes,constantRoutes } from '@/router'
import Layout from '@/layout'
import { getModel,getMenu } from '@/api/admin/auth'
import { getMain,outCache } from '@/api/admin/config'
import { getModelId,setModelId,removeModelId,getMainInfo,setMainInfo,removeMainInfo,getModelName,setModelName,removeModelName} from '@/utils/auth'
/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}
/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
  const res = []

  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles)
      }
      res.push(tmp)
    }
  })
  return res
}
export function tree(arr, pid) {
  const treeArr = []
  arr.forEach((item,index) => {
    if(item.pid == pid){
      item.meta = {
        title:item.name,
        icon:item.icon,
        affix:(item.affix===1)?true:false,
        noCache: true 
      }
      item.hidden = (item.status===1)?false:true
      item.alwaysShow = false
      if(item.type === 2){
        item.component = Layout
        if(!item.redirect){
          item.redirect = 'noRedirect'
        }
      }else if(item.type === 3){
        item.component = (resolve) => require([`@/views${item.url}`],resolve)
      }
      item.children = tree(arr, item.id)
      if (item.children.length<=0) {
        item.children = undefined
      }
      treeArr.push(item)
    }
  })
  return treeArr
}
const state = {
  routes: [],
  addRoutes: [],
  modelArr: [],
  modelId:getModelId(),
  modelName:getModelName(),
  mainInfo:getMainInfo()?JSON.parse(getMainInfo()):undefined
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  },
  SET_MODELARR: (state, modelArr) => {
    state.modelArr = modelArr
  },
  SET_MODELID: (state, modelId) => {
    state.modelId = modelId
  },
  SET_MODELNAME: (state, name) => {
    state.modelName = name
  },
  SET_MAININFO: (state, mainInfo) => {
    state.mainInfo = mainInfo
  },
}

const actions = {
  generateRoutes({ commit }) {
    return new Promise(resolve => {
      getMenu({id:state.modelId}).then(response => { 
        if(response.status === 20000){
          const data = tree(response.data,state.modelId)
          commit('SET_ROUTES', data)
          resolve(data)
          return
        }
      })
    })
  },
    async generateModels({ commit }) {
      return new Promise(resolve => {
        getModel().then(response => { 
          if(response.status === 20000){
            const resModelArr = response.data
            commit('SET_MODELARR', resModelArr)
            resolve(resModelArr)
          }
        })  
      })
    },
    // dynamically modify permissions
  async changeRoles({ commit }) {
    commit('SET_ROUTES', [])
  },
  async changeModelId({ commit },modelId) {
    commit('SET_MODELID', modelId)
    setModelId(modelId)
  },
  async changeModelName({ commit },name) {
    commit('SET_MODELNAME', name)
    setModelName(name)
  },
  async generateMainInfo({ commit }) {
    return new Promise(resolve => {
      getMain().then(response => { 
        if(response.status === 20000){
          const mainInfo = response.data
          setMainInfo(mainInfo)
          commit('SET_MAININFO', mainInfo)
          resolve(mainInfo)
        }
      })  
    })
  },
  // 清除缓存
  outCache({ commit, state, dispatch }) {
    return new Promise((resolve, reject) => {
      outCache().then(() => {
        commit('SET_MAININFO', '')
        removeMainInfo()
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  }
}
export default {
  namespaced: true,
  state,
  mutations,
  actions
}
