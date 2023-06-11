import request from '@/utils/request'
// 系统配置
export function projectIndex() {
  return request({
      url: '/blog/project/index',
      method: 'get'
  })
}
// 提交
export function projectUpdate(data) {
  return request({
      url: '/blog/project/update',
      method: 'put',
      data:data
  })
}