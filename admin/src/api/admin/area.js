import request from '@/utils/request'
// 地区列表
export function areaIndex(data) {
  return request({
    url: '/admin/area/index',
    method: 'get',
    params:data
  })
}
// 调整状态
export function areaStatus(data) {
    return request({
        url: '/admin/area/status',
        method: 'put',
        data:data
    })
}
// 添加
export function areaStore(data) {
  return request({
      url: '/admin/area/store',
      method: 'post',
      data:data
  })
}

// 编辑页面数据
export function areaEdit(data) {
  return request({
      url: '/admin/area/edit',
      method: 'get',
      params:data
  })
}

// 编辑提交
export function areaUpdate(data) {
  return request({
      url: '/admin/area/update',
      method: 'put',
      data:data
  })
}
// 排序
export function areaSorts(data) {
  return request({
      url: '/admin/area/sorts',
      method: 'put',
      data:data
  })
}
// 删除
export function areaDestroy(data) {
  return request({
      url: '/admin/area/cDestroy',
      method: 'delete',
      data:data
  })
}
// 导入服务器数据
export function areaImportData() {
  return request({
      url: '/admin/area/importData',
      method: 'get'
  })
}
// 写入地区缓存
export function areaSetAreaData() {
  return request({
      url: '/admin/area/setAreaData',
      method: 'post'
  })
}

