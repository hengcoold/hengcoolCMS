import request from '@/utils/request'
// 列表
export function articleIndex(data) {
  return request({
    url: '/blog/article/index',
    method: 'get',
    params:data
  })
}
// 获取文章分类列表
export function getArticleTypeList() {
  return request({
    url: '/blog/article/getArticleTypeList',
    method: 'get'
  })
}
// 获取文章标签列表
export function getLabelList() {
  return request({
    url: '/blog/article/getLabelList',
    method: 'get'
  })
}
// 获取用户列表
export function getUserList(data) {
  return request({
    url: '/blog/article/getUserList',
    method: 'get',
    params:data
  })
}
// 调整状态
export function articleStatus(data) {
    return request({
        url: '/blog/article/status',
        method: 'put',
        data:data
    })
}
// 是否推荐
export function articleOpen(data) {
  return request({
      url: '/blog/article/open',
      method: 'put',
      data:data
  })
}
// 添加
export function articleStore(data) {
  return request({
      url: '/blog/article/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function articleEdit(data) {
  return request({
      url: '/blog/article/edit',
      method: 'get',
      params:data
  })
}
// 编辑提交
export function articleUpdate(data) {
  return request({
      url: '/blog/article/update',
      method: 'put',
      data:data
  })
}
// 排序
export function articleSorts(data) {
  return request({
      url: '/blog/article/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function articleDestroy(data) {
  return request({
      url: '/blog/article/cDestroy',
      method: 'delete',
      data:data
  })
}
// 批量恢复
export function articlecDestroyAll(data) {
  return request({
      url: '/blog/article/cDestroyAll',
      method: 'delete',
      data:data
  })
}
// 生成标题 摘要 标签
export function generateContent(data) {
  return request({
      url: '/blog/article/generateContent',
      method: 'post',
      data:data
  })
}
// 文章回收站列表
export function recycleIndex(data) {
  return request({
    url: '/blog/article/recycleIndex',
    method: 'get',
    params:data
  })
}
// 恢复
export function articleRecycle(data) {
  return request({
      url: '/blog/article/recycle',
      method: 'put',
      data:data
  })
}
// 批量恢复
export function articleRecycleAll(data) {
  return request({
      url: '/blog/article/recycleAll',
      method: 'put',
      data:data
  })
}