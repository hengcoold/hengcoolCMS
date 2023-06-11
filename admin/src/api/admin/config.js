import request from '@/utils/request'
// 系统配置
export function configIndex() {
  return request({
      url: '/admin/config/index',
      method: 'get'
  })
}
// 提交
export function configUpdate(data) {
  return request({
      url: '/admin/config/update',
      method: 'put',
      data:data
  })
}
// 系统配置
export function getMain() {
  return request({
      url: '/admin/index/getMain',
      method: 'get'
  })
}

// 清除缓存
export function outCache() {
  return request({
      url: '/admin/index/outCache',
      method: 'delete'
  })
}
// 获取地区数据
export function getAreaData() {
  return request({
      url: '/admin/index/getAreaData',
      method: 'get'
  })
}