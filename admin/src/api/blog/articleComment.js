import request from '@/utils/request'
// 列表
export function articleCommentIndex(data) {
  return request({
    url: '/blog/articleComment/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function articleCommentStatus(data) {
    return request({
        url: '/blog/articleComment/status',
        method: 'put',
        data:data
    })
}
// 排序
export function articleCommentSorts(data) {
  return request({
      url: '/blog/articleComment/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function articleCommentDestroy(data) {
  return request({
      url: '/blog/articleComment/cDestroy',
      method: 'delete',
      data:data
  })
}



