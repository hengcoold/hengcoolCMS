import request from '@/utils/request'
// 列表
export function userIndex(data) {
  return request({
    url: '/blog/user/index',
    method: 'get',
    params:data
  })
}
// 获取平台会员列表
export function getUserList(data) {
    return request({
        url: '/blog/user/getUserList',
        method: 'get',
        params:data
    })
}

// 调整状态
export function userStatus(data) {
    return request({
        url: '/blog/user/status',
        method: 'put',
        data:data
    })
}
// 添加
export function userStore(data) {
  return request({
      url: '/blog/user/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function userEdit(data) {
  return request({
      url: '/blog/user/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function userUpdate(data) {
  return request({
      url: '/blog/user/update',
      method: 'put',
      data:data
  })
}
// 会员关注
export function attentionIndex(data) {
  return request({
    url: '/blog/user/attentionIndex',
    method: 'get',
    params:data
  })
}