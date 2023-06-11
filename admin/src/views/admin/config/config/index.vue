<template>
  <div class="app-container">
    <el-form label-width="100px" :model="form" :rules="rules" ref="ref">
      <el-form-item label="系统名称" prop="name">
        <el-input placeholder="请输入系统名称" maxlength="30" clearable show-word-limit v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="图片储存">
        <el-radio-group v-model="form.image_status" size="medium">
          <el-radio border :label="1">本地</el-radio>
          <el-radio border :label="2">七牛云</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="logo图片">
        <s-file-image :image_list="logoList" @confirmImsge="confirmImsge"></s-file-image>
      </el-form-item>
      <el-form-item label="关于我们">
        <tinymce v-model="form.about_us" :height="300" />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button v-waves type="primary" @click="primary()" style="margin:50px">确 定</el-button>
    </span>
  </div>
</template>
<script>
import sFileImage from "@/components/common/sFileImage/sFileImage"
import Tinymce from '@/components/common/Tinymce'
import { configUpdate,configIndex } from '@/api/admin/config.js'
export default {
  name: 'configIndex',
  components: {
    "s-file-image": sFileImage,
    Tinymce
  },
  data() {
    return {
      form: {
        id: "",
        name:'',
        image_status: 0,
        logo_id:null
      },
      logoList:[],
      rules: {
        name: [
          { required: true, message: "请输入系统名称！", trigger: "blur" }
        ]
      }
    }
  },
  async created() {
    await this.configIndex()
  },
  methods: {
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
          configUpdate(this.form).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.configIndex()
            }
          })
        }
      })
    },
    // 打开编辑按钮对话框
    configIndex() {
      configIndex().then(response => {
        if(response.status === 20000){
          this.form = response.data
          this.logoList = [
            {
              id: response.data.logo_id,
              url: response.data.logo_url
            }
          ]
        }
      })
    },
    // 图片上传回调
    confirmImsge(res) {
      this.form.logo_id = res[0].id
    }
  }
}
</script>