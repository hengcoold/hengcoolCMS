import request from '@/utils/request'
// 列表
export function dashboardIndex() {
  return request({
    url: '/blog/dashboard/index',
    method: 'get'
  })
}