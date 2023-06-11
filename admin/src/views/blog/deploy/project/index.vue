<template>
  <div class="app-container">
    <el-form label-width="100px" :model="form" :rules="rules" ref="ref">
        <el-form-item label="项目名称" prop="name">
          <el-input placeholder="请输入项目名称" maxlength="20" clearable show-word-limit v-model="form.name"
          ></el-input>
        </el-form-item>
        <el-form-item label="项目地址" prop="url">
          <el-input placeholder="请输入项目地址" maxlength="50" clearable show-word-limit v-model="form.url"
          ></el-input>
        </el-form-item>
        <el-form-item label="站点logo" prop="logo_id">
          <s-file-image :image_list="logoList" @confirmImsge="logoConfirmImsge"></s-file-image>
        </el-form-item>
        <el-form-item label="站点标识" prop="ico_id">
          <s-file-image :image_list="icoList" @confirmImsge="icoConfirmImsge"></s-file-image>
        </el-form-item>
        <el-form-item label="项目描述" prop="description">
          <el-input placeholder="请输入项目描述" type="textarea" maxlength="200" clearable show-word-limit v-model="form.description"
          ></el-input>
        </el-form-item>
        <el-form-item label="项目关键词" prop="keywords">
          <el-input placeholder="请输入项目关键词" type="textarea" maxlength="200" clearable show-word-limit v-model="form.keywords"
          ></el-input>
        </el-form-item>
        <el-form-item label="显示状态">
          <el-radio-group v-model="form.status" size="medium">
            <el-radio v-for="(item, index) in statusList" :key="index" border :label="index">{{item}}</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button v-waves type="primary" @click="primary" style="margin:50px">确 定</el-button>
    </span>
  </div>
</template>
<script>
import sFileImage from "@/components/common/sFileImage/sFileImage"
import { projectUpdate,projectIndex } from '@/api/blog/project'
export default {
  name: 'ProjectIndex',
  components: {
    "s-file-image": sFileImage
  },
  data() {
    return {
      statusList:['禁用','启用'],
      form: {
        id:"",  
        name: "",
        url:"",
        status:1,
        logo_id:null,
        ico_id:null,
        description:"",
        keywords:"",
      },
      rules: {
        name: [
          { required: true, message: "请输入项目名称！", trigger: "blur" },
        ],
        url: [
          { required: true, message: "请输入项目地址！", trigger: "blur" }
        ],
        logo_id: [
          { required: true, message: "请上传站点logo！", trigger: "blur" }
        ],
        ico_id: [
          { required: true, message: "请上传站点标识！", trigger: "blur" }
        ],
        description: [
          { required: true, message: "请输入项目描述！", trigger: "blur" }
        ],
        keywords: [
          { required: true, message: "请输入项目关键词！", trigger: "blur" }
        ]
      },
      logoList:[],
      icoList:[]
    }
  },
  async created() {
    await this.projectIndex()
  },
  methods: {
     // 打开编辑按钮对话框
    async projectIndex() {
      projectIndex().then(response => {
        if(response.status === 20000){
          this.dialogVisible = true
          this.form = response.data
          this.logoList = [
            {
              id: response.data.logo_id,
              url: response.data.logo_url
            }
          ]
          this.icoList = [
            {
              id: response.data.ico_id,
              url: response.data.ico_url
            }
          ]
        }
      })
    },
      // 提交
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
            projectUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.projectIndex()
              }
            })
        }
      })
    },
    // 图片上传回调
    logoConfirmImsge(res) {
      this.form.logo_id = res[0].id
    },
    // 图片上传回调
    icoConfirmImsge(res) {
      this.form.ico_id = res[0].id
    }
  }
}
</script>