import request from '@/utils/request'
// 列表
export function picIndex(data) {
  return request({
    url: '/blog/pic/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function picStatus(data) {
    return request({
        url: '/blog/pic/status',
        method: 'put',
        data:data
    })
}
// 添加
export function picStore(data) {
  return request({
      url: '/blog/pic/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function picEdit(data) {
  return request({
      url: '/blog/pic/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function picUpdate(data) {
  return request({
      url: '/blog/pic/update',
      method: 'put',
      data:data
  })
}
// 排序
export function picSorts(data) {
  return request({
      url: '/blog/pic/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function picDestroy(data) {
  return request({
      url: '/blog/pic/cDestroy',
      method: 'delete',
      data:data
  })
}