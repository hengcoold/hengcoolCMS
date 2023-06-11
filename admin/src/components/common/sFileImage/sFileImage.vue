<template>
  <div>
    <div class="avatar_list" v-if="status===false">
      <div class="img-d" v-for="(item, index) in fileList" :key="index">
        <img :src="item.url" class="avatar" />
        <div class="operation">
          <div class="operation-is" @click="delPic(2, index)">删除</div>
          <div class="operation-is" @click="handlePictureCardPreview(item.url)">
            预览
          </div>
        </div>
      </div>
      <div class="img-d" v-if="countS">
        <img src="@/assets/images/upload.png" class="avatar" @click="upload" />
      </div>
    </div>
    <el-button v-else icon="el-icon-upload" size="mini" type="primary" @click="upload">
      上传图片
    </el-button>
    <el-dialog
      :title="'图片上传（还可上传' + countS + '张）'"
      :visible.sync="picture_bounced_status"
      width="60%"
      :append-to-body="true"
      @close="picture_bounced_status_close"
    >
      <el-tabs v-model="activeName" type="border-card">
        <el-tab-pane label="本地上传" name="1">
          <div class="avatar_list">
            <div class="img-d" v-for="(item, index) in uploadList" :key="index">
              <img :src="item.url" class="avatar1" />
              <div class="success">
                <img src="@/assets/images/success.png" alt="" />
              </div>
              <div class="operation">
                <div class="operation-is" @click="delPic(1, index)">删除</div>
                <div
                  class="operation-is"
                  @click="handlePictureCardPreview(item.url)"
                >
                  预览
                </div>
              </div>
            </div>
            <el-upload
              v-if="countS"
              class="avatar-uploader"
              action="fakeaction"
              :show-file-list="false"
              :http-request="uploadSectionFile"
            >
              <div class="img-d">
                <img src="@/assets/images/upload.png" class="avatar1" />
              </div>
            </el-upload>
          </div>
        </el-tab-pane>
        <el-tab-pane label="在线管理" name="2">
          <div class="avatar_list">
            <div class="img-d" v-for="(item, index) in onLineList" :key="index">
              <img
                :src="item.url"
                class="avatar1"
                @click="clickStatus(index)"
              />
              <div class="success" v-if="item.status">
                <img src="@/assets/images/success.png" alt="" />
              </div>
              <div class="operation">
                <div
                  class="operation-is"
                  @click="handlePictureCardPreview(item.url)"
                >
                  预览
                </div>
              </div>
            </div>
          </div>
          <!-- 页码区 -->
          <el-pagination
            @size-change="handle_size_change"
            @current-change="handle_current_change"
            :total="total"
            layout="total, sizes, prev, pager, next, jumper"
            :current-page="query_info.page"
            :page-sizes="[10, 20, 30, 50, 100, 200]"
            :page-size="query_info.limit"
          ></el-pagination>
        </el-tab-pane>
      </el-tabs>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="picture_bounced_status_close()">取消</el-button>
        <el-button v-waves type="primary" @click="primary()">确定</el-button>
      </span>
    </el-dialog>
    <!-- 图片预览 -->
    <el-dialog
      title="图片预览"
      :visible.sync="image_dialog_visible"
      width="60%"
      :append-to-body="true"
      @close="handlePictureCardPreviewClose"
    >
      <img :src="imageUrl" class="image_dialog" />
    </el-dialog>
  </div>
</template>
<script>
import { getImageList,uploadImage } from '@/api/admin/image'
export default {
  name: "sFileImage",
  data() {
    return {
      countS: 1, // 当前可上传图片数量
      query_info: {
        page: 1, // 当前页码
        limit: 12 // 每页显示条数
      },
      total: 0, // 总数据条数
      onLineList: [], // 在线管理列表
      yesOnLineList: [], // 在线管理列表中选中的
      uploadList: [], // 上传的图片列表
      fileList: [], // 图片列表
      activeName: "1",
      picture_bounced_status: false, // 图片上传弹框
      image_dialog_visible: false, // 图片预览
      imageUrl: "" // 图片路径
    };
  },
  props: {
    // 图片列表
    image_list: {
      type: Array,
      default: function() {
        return []
      }
    },
    // 可上传数量
    count: {
      type: Number,
      default: 1
    },
    status:{
      type:Boolean,
      default:false
    }
  },
  watch: {
    image_list: {
      deep: true,
      immediate: true,
      handler(cval) {
        this.fileList = cval
        this.getCountS()
      }
    }
  },
  methods: {
    clickStatus(index) {
      if (this.countS === 0 && this.onLineList[index].status !== true) {
        return false
      }
      this.onLineList[index].status = !this.onLineList[index].status
      this.getCountS()
    },
    getCountS() {
      this.yesOnLineList = this.onLineList.filter(item => {
        return item.status === true
      });
      this.countS = this.count - this.yesOnLineList.length - this.uploadList.length - this.fileList.length
    },
    picture_bounced_status_close() {
      this.yesOnLineList = []
      this.uploadList = []
      this.onLineList = []
      this.getCountS()
      this.picture_bounced_status = false
    },
    primary() {
      this.fileList = [
        ...this.fileList,
        ...this.yesOnLineList,
        ...this.uploadList
      ];
      this.yesOnLineList = []
      this.uploadList = []
      this.onLineList = []
      this.total = 0
      this.picture_bounced_status = false
      this.$emit("confirmImsge", this.fileList)
    },
    upload() {
      this.picture_bounced_status = true
      this.getList();
    },
    getList() {
      getImageList(this.query_info).then(response => {
        if(response.status === 20000){
          this.onLineList = response.data.list
          this.total = response.data.total
          this.getCountS()
        }
      })
    },
    // 当前每页多少条选择
    handle_size_change(pagesize) {
      this.query_info.pagesize = pagesize
      this.onLineList = []
      this.total = 0
      this.getList()
    },
    // 当前页码
    handle_current_change(page) {
      this.query_info.page = page
      this.onLineList = []
      this.total = 0
      this.getList()
    },
    handlePictureCardPreview(url) {
      this.imageUrl = url;
      this.image_dialog_visible = true
    },
    handlePictureCardPreviewClose() {
      this.imageUrl = "";
      this.image_dialog_visible = false
    },
    delPic(i, index) {
      if (i === 1) {
        this.uploadList.splice(index, 1)
      } else {
        this.fileList.splice(index, 1)
      }
      this.getCountS()
    },
    uploadSectionFile(params) {
      const file = params.file
      const fileType = file.type
      const isImage = fileType.indexOf("image") !== -1
      const isLt2M = file.size / 1024 / 1024 < 10
      // 这里常规检验，看项目需求而定
      if (!isImage) {
        return this.$base.message({
          type: "error",
          that: this,
          message: "只能上传图片格式png、jpg、gif！"
        });
      }
      if (!isLt2M) {
        return this.$base.message({
          type: "error",
          that: this,
          message: "只能上传图片大小小于10M！"
        });
      }
      const data = new FormData()
      data.append("file", file)
      uploadImage(data).then(response => {
        if(response.status === 20000){
          this.uploadList.push({
            id: response.data.image_id,
            url: response.data.url
          })
          this.getCountS()
        }
      })
    }
  }
};
</script>
<style lang="scss" scoped>
.avatar_list {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  // height: 300px;
  overflow: auto;
  align-content: flex-start;
  .img-d {
    border: 1px solid #d9d9d9;
    position: relative;
    margin: 10px;
    .avatar {
      width: 120px;
      height: 120px;
    }
    .avatar1 {
      width: 100px;
      height: 100px;
    }
    .success {
      position: absolute;
      right: 0;
      top: 0;
      width: 50px;
      height: 50px;
      img {
        width: 50px;
        height: 50px;
      }
    }
    .operation {
      position: absolute;
      left: 0;
      bottom: 0;
      background-color: #009688;
      color: #ffffff;
      display: flex;
      flex-direction: row;
      justify-content: space-around;
      width: 100%;
      cursor: pointer;
    }
  }
}
.image_dialog {
  width: 100%;
  height: 100%;
}
</style>