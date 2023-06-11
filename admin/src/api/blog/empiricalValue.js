import request from '@/utils/request'
// 列表
export function empiricalValueIndex(data) {
  return request({
    url: '/blog/empiricalValue/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function empiricalValueStatus(data) {
    return request({
        url: '/blog/empiricalValue/status',
        method: 'put',
        data:data
    })
}
// 添加
export function empiricalValueStore(data) {
  return request({
      url: '/blog/empiricalValue/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function empiricalValueEdit(data) {
  return request({
      url: '/blog/empiricalValue/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function empiricalValueUpdate(data) {
  return request({
      url: '/blog/empiricalValue/update',
      method: 'put',
      data:data
  })
}
// 排序
export function empiricalValueSorts(data) {
  return request({
      url: '/blog/empiricalValue/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function empiricalValueDestroy(data) {
  return request({
      url: '/blog/empiricalValue/cDestroy',
      method: 'delete',
      data:data
  })
}