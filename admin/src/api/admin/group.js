import request from '@/utils/request'
// 权限组列表
export function getGroupList() {
  return request({
    url: '/admin/admin/getGroupList',
    method: 'get'
  })
}
// 权限组列表
export function groupIndex(data) {
  return request({
    url: '/admin/group/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function groupStatus(data) {
    return request({
        url: '/admin/group/status',
        method: 'put',
        data:data
    })
}
// 添加管理员
export function groupStore(data) {
  return request({
      url: '/admin/group/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function groupEdit(data) {
  return request({
      url: '/admin/group/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function groupUpdate(data) {
  return request({
      url: '/admin/group/update',
      method: 'put',
      data:data
  })
}

// 获取分配权限数据
export function groupAccess(data) {
  return request({
      url: '/admin/group/access',
      method: 'get',
      params:data
  })
}
// 分配权限提交
export function groupAccessUpdate(data) {
  return request({
      url: '/admin/group/accessUpdate',
      method: 'put',
      data:data
  })
}


