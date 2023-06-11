import request from '@/utils/request'
// 列表
export function labelIndex(data) {
  return request({
    url: '/blog/label/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function labelStatus(data) {
    return request({
        url: '/blog/label/status',
        method: 'put',
        data:data
    })
}
// 添加
export function labelStore(data) {
  return request({
      url: '/blog/label/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function labelEdit(data) {
  return request({
      url: '/blog/label/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function labelUpdate(data) {
  return request({
      url: '/blog/label/update',
      method: 'put',
      data:data
  })
}
// 排序
export function labelSorts(data) {
  return request({
      url: '/blog/label/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function labelDestroy(data) {
  return request({
      url: '/blog/label/cDestroy',
      method: 'delete',
      data:data
  })
}