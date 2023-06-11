import request from '@/utils/request'
// 图片列表
export function getImageList(data) {
  return request({
    url: '/admin/upload/getImageList',
    method: 'get',
    params:data
  })
}
// 添加管理员
export function uploadImage(data) {
//   return request({
//       url: '/admin/upload/fileImage',
//       method: 'post',
//       data:data
//   },{},{headers: { 'Content-Type': 'multipart/form-data' }})
    return request({
        url: '/admin/upload/fileImage',
        method: 'post',
        data:data
    })
}

// 转换编辑器内容
export function setContent(data) {
  return request({
    url: '/admin/index/setContent',
    method: 'post',
    data:data
  })
}