import { login, logout, getInfo } from '@/api/admin/auth'
import { getToken, setToken, removeToken ,getTokenType, setTokenType, removeTokenType,getUserInfo,setUserInfo,removeUserInfo} from '@/utils/auth'
import router, { resetRouter } from '@/router'

const state = {
  token: getToken(),
  tokenType: getTokenType(),
  userInfo: getUserInfo()?JSON.parse(getUserInfo()):undefined
}

const mutations = {
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_TOKEN_TYPE: (state, tokenType) => {
    state.tokenType = tokenType
  },
  SET_USER_INFO: (state, userInfo) => {
    state.userInfo = userInfo
  }
}

const actions = {
  // user login
  login({ commit }, userInfo) {
    const { username, password } = userInfo
    return new Promise((resolve, reject) => {
      login({ username: username, password: password }).then(response => {
        const { data } = response
        commit('SET_TOKEN', data.token)
        commit('SET_TOKEN_TYPE', data.token_type)
        setToken(data.token)
        setTokenType(data.token_type)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },
  //  退出登录
  logout({ commit, state, dispatch }) {
    return new Promise((resolve, reject) => {
      logout().then(() => {
        commit('SET_TOKEN', '')
        commit('SET_TOKEN_TYPE', '') 
        commit('SET_USER_INFO', '')
        removeToken()
        removeTokenType()
        removeUserInfo()
        resetRouter()
        // reset visited views and cached views
        // to fixed https://github.com/PanJiaChen/vue-element-admin/issues/2485
        dispatch('tagsView/delAllViews', null, { root: true })
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },
  // 删除 token
  resetToken({ commit }) {
    return new Promise(resolve => {
      commit('SET_TOKEN', '')
      commit('SET_TOKEN_TYPE', '')
      commit('SET_USER_INFO', '')
      removeToken()
      removeTokenType()
      removeUserInfo()
      resolve()
    })
  },
  // 获取管理员信息
  getInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      getInfo().then(response => {
        const { data } = response
        commit('SET_USER_INFO', data)
        setUserInfo(data)
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  }
  


  

  // dynamically modify permissions
  // async changeRoles({ commit, dispatch }, role) {
  //   const token = role + '-token'

  //   commit('SET_TOKEN', token)
  //   setToken(token)

  //   const { roles } = await dispatch('getInfo')

  //   resetRouter()

  //   // generate accessible routes map based on roles
  //   const accessRoutes = await dispatch('permission/generateRoutes', roles, { root: true })
  //   // dynamically add accessible routes
  //   router.addRoutes(accessRoutes)

  //   // reset visited views and cached views
  //   dispatch('tagsView/delAllViews', null, { root: true })
  // }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
