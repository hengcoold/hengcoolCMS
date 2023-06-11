import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/admin/login/login',
    method: 'post',
    data:data
  })
}
export function getMenu(data){
  return request({
    url: '/admin/index/getMenu',
    method: 'get',
    params:data
  })
}
// 获取模块
export function getModel(){
  return request({
    url: '/admin/index/getModel',
    method: 'get'
  })
}
// 获取管理员信息
export function getInfo() {
  return request({
    url: '/admin/admin/info',
    method: 'get'
  })
}
// 退出登录
export function logout() {
  return request({
    url: '/admin/index/logout',
    method: 'delete'
  })
}
// 修改密码
export function upadtePwdView(data) {
  return request({
    url: '/admin/index/upadtePwdView',
    method: 'put',
    data: data
  })
}

// 数据看板
export function dashboardIndex() {
  return request({
    url: '/admin/index/dashboard',
    method: 'get'
  })
}
// 接口请求图表数据
export function getLogCountList() {
  return request({
    url: '/admin/index/getLogCountList',
    method: 'get'
  })
}
