import request from '@/utils/request'
// 项目列表
export function getProjectList() {
  return request({
    url: '/admin/admin/getProjectList',
    method: 'get'
  })
}
// 项目列表
export function projectIndex(data) {
  return request({
    url: '/admin/project/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function projectStatus(data) {
    return request({
        url: '/admin/project/status',
        method: 'put',
        data:data
    })
}
// 添加管理员
export function projectStore(data) {
  return request({
      url: '/admin/project/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function projectEdit(data) {
  return request({
      url: '/admin/project/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function projectUpdate(data) {
  return request({
      url: '/admin/project/update',
      method: 'put',
      data:data
  })
}