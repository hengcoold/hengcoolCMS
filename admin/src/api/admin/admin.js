import request from '@/utils/request'
// 管理员列表
export function adminIndex(data) {
  return request({
    url: '/admin/admin/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function adminStatus(data) {
    return request({
        url: '/admin/admin/status',
        method: 'put',
        data:data
    })
}
// 添加管理员
export function adminStore(data) {
  return request({
      url: '/admin/admin/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function adminEdit(data) {
  return request({
      url: '/admin/admin/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function adminUpdate(data) {
  return request({
      url: '/admin/admin/update',
      method: 'put',
      data:data
  })
}
// 初始化密码
export function adminUpdatePwd(data) {
  return request({
      url: '/admin/admin/updatePwd',
      method: 'put',
      data:data
  })
}

