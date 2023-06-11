import request from '@/utils/request'
// 列表
export function articleCollectIndex(data) {
  return request({
    url: '/blog/articleCollect/index',
    method: 'get',
    params:data
  })
}