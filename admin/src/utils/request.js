import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import { getToken,setToken,getTokenType,setTokenType } from '@/utils/auth'
import router from '@/router'
function getBreadcrumb() {
  let matched = router.history.current.matched.filter(item => item.meta && item.meta.title)

  matched = matched.filter(item => item.meta && item.meta.title && item.meta.breadcrumb !== false)
  if(matched.length>0){
    let arr = []
    for(var i=0;i<matched.length;i++){
      arr.push(matched[i].name)
    }
    return encodeURIComponent(arr.join('->'))
  }else{
    return ''
  }
}
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, 
  timeout: 1000000,
  validateStatus: status => {
    return status >= 200 && status < 600;
  }
})

// request interceptor
service.interceptors.request.use(
  config => {
    config.headers.apikey = process.env.VUE_APP_BASE_API_KEY
    if (store.getters.token) {
      config.headers['Authorization'] = getTokenType()+ ' ' + getToken()
    }
    const Breadcrumb = getBreadcrumb()
    if(Breadcrumb){
      config.headers['breadcrumb'] = Breadcrumb
    }
    return config
  },
  error => {
    console.log(error) 
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  response => {
    const res = response.data
    if (res.status !== 20000) {
      if(res.status === 70002 || res.status === 70003 || res.status === 70005 || res.status === 70006){
        MessageBox.confirm(res.message, '提示', {
          confirmButtonText: '确定',
          type: 'warning',
          showClose:false,
          showCancelButton:false,
          closeOnClickModal:false,
          closeOnPressEscape:false,
        }).then(() => {
          store.dispatch('user/resetToken').then(() => {
            location.reload()
          })
        })
        return
      }
      if(res.status === 70004){
        return againRequest(response,'refreshToken')
      }
      Message({
        message: res.message || 'Error',
        type: 'error',
        duration: 5 * 1000
      })
    }
    return res
  },
  error => {
    console.log('err' + error) // for debug
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)
/**
 * 刷新token同时请求上一次请求
 * @param error
 * @param handle
 * @returns {Promise<AxiosResponse<T>>}
 */
 async function againRequest (error,handle) {
  let response;
  switch (handle) {
    case 'refreshToken':
      response = await refreshToken()
      break;
  }
  if (response.data.status !== 20000) {
    if(response.data.status === 70002 || response.data.status === 70003 || response.data.status === 70005 || response.data.status === 70006){
      MessageBox.confirm(response.data.message, '提示', {
        confirmButtonText: '确定',
        type: 'warning',
        showClose:false,
        showCancelButton:false,
        closeOnClickModal:false,
        closeOnPressEscape:false,
      }).then(() => {
        store.dispatch('user/resetToken').then(() => {
          location.reload()
        })
      })
      return
    }
    Message({
      message: response.data.message || 'Error',
      type: 'error',
      duration: 5 * 1000
    })
  }
  if(!response.data.data.token){
    store.dispatch('user/resetToken').then(() => {
      location.reload()
    })
    return
  }
  let newToken = response.data.data.token
  let tokenType = response.data.data.token_type
  let config = error.config
  config.headers['Authorization'] = tokenType + ' ' + newToken
  config.headers['apikey'] = process.env.VUE_APP_BASE_API_KEY
  if(newToken){
    store.commit('user/SET_TOKEN', newToken)
    store.commit('user/SET_TOKEN_TYPE', tokenType)
    setTokenType(tokenType)
    setToken(newToken)
  }
  config.baseURL = ''
  const reslet  = await axios.request(config) //重新发送请求
  const res = reslet.data
    if (res.status !== 20000) {
      if(res.status === 70002 || res.status === 70003 || res.status === 70005 || res.status === 70006){
        MessageBox.confirm(res.message, '提示', {
          confirmButtonText: '确定',
          type: 'warning',
          showClose:false,
          showCancelButton:false,
          closeOnClickModal:false,
          closeOnPressEscape:false,
        }).then(() => {
          store.dispatch('user/resetToken').then(() => {
            location.reload()
          })
        })
        return
      }
      if(res.status === 70004){
        console.log('刷新token')
        againRequest(response,'refreshToken')
      }
      Message({
        message: res.message || 'Error',
        type: 'error',
        duration: 5 * 1000
      })
    }
    return res

}
/**
 * 刷新token
 * @returns {Promise<AxiosResponse<T> | never>}
 */
 async function refreshToken() {
  return axios.put(process.env.VUE_APP_BASE_API + '/admin/index/refreshToken',{},{
    headers:{
      'Authorization': getTokenType()+ ' ' + getToken(),
      'apikey' : process.env.VUE_APP_BASE_API_KEY
    },
    validateStatus: function(status) {
      return status >= 200 && status < 600
    },
    timeout: 1000000
  }).then(response => {
    return response;
  }).catch(error =>{
    console.log('err' + error)
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  });
}

export default service
