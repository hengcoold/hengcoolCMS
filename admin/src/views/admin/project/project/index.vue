<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.name" placeholder="请输入项目名称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-select v-model="listQuery.status" placeholder="请选择状态" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option label="禁用" value="0"></el-option>
        <el-option label="启用" value="1"></el-option>
      </el-select>
      <s-date-picker :date="listQuery.created_at" @changeDateTime="eventStartTime"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
        <el-button v-waves class="filter-item" type="primary" icon="el-icon-edit" @click="add()">
          添加
        </el-button>
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
          导出
        </el-button>
      </div>
      
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="编号" width="80px" prop="id" sortable></el-table-column>
      <el-table-column width="120px" align="center" label="项目名称">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="250px" align="center" label="项目地址">
        <template slot-scope="{row}">
            <el-link type="success" :href="row.url">{{ row.url }}</el-link>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="创建时间" prop="created_at" sortable></el-table-column>
      <el-table-column width="160px" align="center" label="编辑时间" prop="updated_at" sortable></el-table-column>
      <el-table-column align="center" label="状态" prop="status" width="100px">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="row.status===1 ? '启用' : '禁用'"
            placement="top"
            :enterable="false">
            <el-switch
              v-model="row.status"
              active-color="#5FB878"
              inactive-color="#d2d2d2"
              :active-value="1"
              :inactive-value="0"
              @change="setStatus(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="150px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="primary" size="mini" @click="edit(row.id)">
            编辑
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <!-- 添加编辑对话框 -->
    <el-dialog   :title="title" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
      <!-- 主体区 -->
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
            <el-radio border :label="0">禁用</el-radio>
            <el-radio border :label="1">启用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="dialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="primary()">确 定</el-button>
      </span>
    </el-dialog>  
  </div>
</template>

<script>
import { projectIndex,projectStatus,projectStore,projectEdit,projectUpdate } from '@/api/admin/project'
import sFileImage from "@/components/common/sFileImage/sFileImage"
export default {
  name: 'ProjectIndex',
  components: {
    "s-file-image": sFileImage
  },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        status:null,
        name:"",
        created_at:[],
        updated_at:[]
      },
      dialogVisible:false,
      downloadLoading: false,
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
      title:'',
      logoList:[],
      icoList:[]
    }
  },
  async created() {
    await this.getList()
  },
  methods: {
    eventStartTime(val){
      this.listQuery.created_at = val
      this.handleFilter()
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      projectIndex(this.listQuery).then(response => {
        if(response.status === 20000){
          this.list = response.data.list
          this.total = response.data.total
        }
        this.listLoading = false
      })
    },
    // 搜索
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    // 重置
    refresh(){
        this.listQuery = {
          page: 1,
          limit: 10,
          status:null,
          name:"",
          created_at:[],
          updated_at:[]
        }
        this.getList()
    },
    // 监听添加对话框的关闭事件
    dialogClose() {
      this.form = {
        id:"",  
        name: "",
        url:"",
        status:1,
        logo_id:null,
        ico_id:null,
        description:"",
        keywords:"",
      }
      this.logoList = []
      this.icoList = []
    },
    add(){
      this.dialogVisible = true
      this.title = '添加'
    },
    // 图片上传回调
    logoConfirmImsge(res) {
      this.form.logo_id = res[0].id
    },
    // 图片上传回调
    icoConfirmImsge(res) {
      this.form.ico_id = res[0].id
    },
    // 打开编辑按钮对话框
    edit(id) {
      projectEdit({id:id}).then(response => {
        if(response.status === 20000){
          this.title = '编辑'
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
      // 添加编辑按钮
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
          if(this.form.id){
            projectUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }else{
            projectStore(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }
        }
      })
    },
    // 状态调整
    setStatus(info) {
      projectStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['编号', '项目名称','项目地址','创建时间','编辑时间','状态']
        const filterVal = ['id', 'name','url','updated_at','created_at','status']
        const data = this.formatJson(filterVal)
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: this.$route.name
        })
        this.downloadLoading = false
      })
    },
    formatJson(filterVal) {
      return this.list.map(v => filterVal.map(j => {
        return v[j]
      }))
    }  
  }
}
</script>