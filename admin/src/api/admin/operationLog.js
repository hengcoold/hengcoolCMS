import request from '@/utils/request'
// 操作日志
export function operationLogIndex(data) {
  return request({
    url: '/admin/operationLog/index',
    method: 'get',
    params:data
  })
}
// 删除
export function operationLogDestroy(data) {
    return request({
        url: '/admin/operationLog/cDestroy',
        method: 'delete',
        data:data
    })
}
// 批量删除
export function operationLogDestroyAll(data) {
    return request({
        url: '/admin/operationLog/cDestroyAll',
        method: 'delete',
        data:data
    })
}

