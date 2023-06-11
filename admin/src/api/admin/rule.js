import request from '@/utils/request'
// 权限组列表
export function ruleIndex() {
  return request({
    url: '/admin/rule/index',
    method: 'get'
  })
}
// 调整状态
export function ruleStatus(data) {
  return request({
      url: '/admin/rule/status',
      method: 'put',
      data:data
  })
}
// 是否验证权限
export function ruleOpen(data) {
  return request({
      url: '/admin/rule/open',
      method: 'put',
      data:data
  })
}
// 是否验证权限
export function ruleAffix(data) {
  return request({
      url: '/admin/rule/affix',
      method: 'put',
      data:data
  })
}
// 添加
export function ruleStore(data) {
  return request({
      url: '/admin/rule/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function ruleEdit(data) {
  return request({
      url: '/admin/rule/edit',
      method: 'get',
      params:data
  })
}
// 添加子级返回父级id
export function rulePidArr(data) {
  return request({
      url: '/admin/rule/pidArr',
      method: 'get',
      params:data
  })
}

// 编辑提交
export function ruleUpdate(data) {
  return request({
      url: '/admin/rule/update',
      method: 'put',
      data:data
  })
}
// 排序
export function ruleSorts(data) {
  return request({
      url: '/admin/rule/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function ruleDestroy(data) {
  return request({
      url: '/admin/rule/cDestroy',
      method: 'delete',
      data:data
  })
}


