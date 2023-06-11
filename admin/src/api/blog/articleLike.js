import request from '@/utils/request'
// 列表
export function articleLikeIndex(data) {
  return request({
    url: '/blog/articleLike/index',
    method: 'get',
    params:data
  })
}