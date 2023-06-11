import request from '@/utils/request'
// 管理员列表
export function userIndex(data) {
  return request({
    url: '/admin/user/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function userStatus(data) {
    return request({
        url: '/admin/user/status',
        method: 'put',
        data:data
    })
}
// 添加管理员
export function userStore(data) {
  return request({
      url: '/admin/user/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function userEdit(data) {
  return request({
      url: '/admin/user/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function userUpdate(data) {
  return request({
      url: '/admin/user/update',
      method: 'put',
      data:data
  })
}
// 初始化密码
export function userUpdatePwd(data) {
  return request({
      url: '/admin/user/updatePwd',
      method: 'put',
      data:data
  })
}