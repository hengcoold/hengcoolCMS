import request from '@/utils/request'
// 数据表管理
// 列表
export function dataBaseIndex(data) {
  return request({
    url: '/admin/dataBase/index',
    method: 'get',
    params:data
  })
}
// 表详情
export function dataBaseTableData(data) {
  return request({
    url: '/admin/dataBase/tableData',
    method: 'get',
    params:data
  })
}
// 备份表
export function dataBaseBackUp(data) {
    return request({
        url: '/admin/dataBase/backUp',
        method: 'post',
        data:data
    })
}
// 备份管理
// 备份列表
export function dataBaseRestoreData() {
    return request({
        url: '/admin/dataBase/restoreData',
        method: 'get'
    })
}
// 查询文件详情
export function dataBaseGetFiles(data) {
  return request({
      url: '/admin/dataBase/getFiles',
      method: 'get',
      params:data
  })
}
// 删除备份文件
export function dataBaseDelSqlFiles(data) {
    return request({
        url: '/admin/dataBase/delSqlFiles',
        method: 'delete',
        data:data
    })
}



