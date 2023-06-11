import { MessageBox, Message } from 'element-ui'
export default {
  // 提示信息
  message(params) {
    Message({
        message: params.message || "加载中...",
        type: params.type || "success",
        duration: params.duration || 2000,
        center: params.center !== false,
        offset: params.offset || 50
    })
  },
  // 弹框
  confirm(params, callBack, catchBack) {
    MessageBox.confirm(params.content || "确定要删除吗?", params.title || "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        callBack && callBack();
      }).catch(() => {
        catchBack && catchBack()     
      })
  }
}