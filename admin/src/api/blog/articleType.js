import request from '@/utils/request'
// 权限组列表
export function articleTypeIndex(data) {
  return request({
    url: '/blog/articleType/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function articleTypeStatus(data) {
  return request({
      url: '/blog/articleType/status',
      method: 'put',
      data:data
  })
}
// 添加
export function articleTypeStore(data) {
  return request({
      url: '/blog/articleType/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function articleTypeEdit(data) {
  return request({
      url: '/blog/articleType/edit',
      method: 'get',
      params:data
  })
}
// 添加子级返回父级id
export function articleTypePidArr(data) {
  return request({
      url: '/blog/articleType/pidArr',
      method: 'get',
      params:data
  })
}

// 编辑提交
export function articleTypeUpdate(data) {
  return request({
      url: '/blog/articleType/update',
      method: 'put',
      data:data
  })
}
// 排序
export function articleTypeSorts(data) {
  return request({
      url: '/blog/articleType/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function articleTypeDestroy(data) {
  return request({
      url: '/blog/articleType/cDestroy',
      method: 'delete',
      data:data
  })
}