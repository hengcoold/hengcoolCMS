import request from '@/utils/request'
// 列表
export function userLevelIndex(data) {
  return request({
    url: '/blog/userLevel/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function userLevelStatus(data) {
    return request({
        url: '/blog/userLevel/status',
        method: 'put',
        data:data
    })
}
// 添加
export function userLevelStore(data) {
  return request({
      url: '/blog/userLevel/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function userLevelEdit(data) {
  return request({
      url: '/blog/userLevel/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function userLevelUpdate(data) {
  return request({
      url: '/blog/userLevel/update',
      method: 'put',
      data:data
  })
}
// 排序
export function userLevelSorts(data) {
  return request({
      url: '/blog/userLevel/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function userLevelDestroy(data) {
  return request({
      url: '/blog/userLevel/cDestroy',
      method: 'delete',
      data:data
  })
}