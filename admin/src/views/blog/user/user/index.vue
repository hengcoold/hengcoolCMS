<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.nickname" placeholder="请输入昵称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-cascader filterable :options="areaData"
            placeholder="请选择地址"
           :props="{
              expandTrigger: 'hover',
              value: 'id',
              label: 'name',
              checkStrictly: true
            }" 
            v-model="searchValue"
            @change="searchHandleChange"
            clearable style="width: 300px;"></el-cascader>
      <el-select v-model="listQuery.status" placeholder="请选择状态" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="(item,index) in statusList" :key="index" :label="item" :value="index"></el-option>
      </el-select>
      <el-select v-model="listQuery.sex" placeholder="请选择性别" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="(item,index) in sexList" :key="index" :label="item" :value="index"></el-option>
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
      <el-table-column align="center" label="编号" width="80px">
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column width="120px" align="center" label="姓名">
        <template slot-scope="{row}">
          <span>{{ row.user_to.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="120px" align="center" label="昵称">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="'邮箱：'+row.user_to.email"
            placement="top"
            :enterable="false">
            <span>{{ row.user_to.nickname }}</span>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column width="80px" align="center" label="性别">
        <template slot-scope="{row}">
          <span>{{ sexList[row.user_to.sex] }}</span>
        </template>
      </el-table-column>
      <el-table-column width="130px" align="center" label="手机号">
        <template slot-scope="{row}">
          <span>{{ row.user_to.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="邮箱">
        <template slot-scope="{row}">
          <span>{{ row.user_to.email }}</span>
        </template>
      </el-table-column>
      <el-table-column width="100px" align="center" label="经验值">
        <template slot-scope="{row}">
          <span>{{ row.empirical_value }}</span>
        </template>
      </el-table-column>
      <el-table-column width="150px" align="center" label="地址">
        <template slot-scope="{row}">
          <span>{{ row.user_to.province_to.name?row.user_to.province_to.name:'' }}{{ row.user_to.city_to.name?row.user_to.city_to.name:'' }}{{ row.user_to.county_to.name?row.user_to.county_to.name:'' }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="创建时间">
        <template slot-scope="{row}">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="状态" prop="status" width="80">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="statusList[row.status]"
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
      <el-table-column label="操作" align="center" width="120px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="primary" size="mini" @click="userEdit(row.id)">
            编辑
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" /> 
    <!-- 对话框 -->
    <el-dialog :title="title" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
      <!-- 主体区 -->
      <el-form label-width="100px" :model="form" :rules="rules" ref="ref">
        <el-form-item label="平台用户" prop="user_id" v-if="!form.id">
            <el-select v-model="form.user_id" filterable clearable remote
            reserve-keyword :remote-method="getUserList" :loading="userLoading"
             placeholder="请选择平台用户" style="width:100%">
                <el-option
                v-for="(item,index) in userList"
                :key="index"
                :label="item.nickname+'->'+item.email"
                :value="item.id">
                </el-option>
            </el-select>
        </el-form-item>  
        <el-form-item label="用户经验值">
          <el-input placeholder="请输入用户经验值"  maxlength="11" clearable show-word-limit v-model="form.empirical_value"
          ></el-input>
        </el-form-item>
        <el-form-item label="状态">
            <el-radio-group v-model="form.status" size="medium">
            <el-radio border v-for="(item,index) in statusList" :key="index" :label="index">{{item}}</el-radio>
            </el-radio-group>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="dialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="primary()" >确 定</el-button>
      </span>
    </el-dialog>  
  </div>
</template>

<script>
import { userIndex,userStatus,userStore,userEdit,userUpdate,getUserList } from '@/api/blog/user'
import { getAreaData } from '@/api/admin/config'
export default {
  name: 'UserIndex',
  data() {
    return {
      statusList:['拉黑','正常'],      
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        nickname:'',
        sex:null,
        province_id:null,
        city_id:null,
        county_id:null,
        status:null,
        created_at:[],
        updated_at:[]
      },
      dialogVisible:false,
      downloadLoading: false,
      form: {
        status: 1,
        empirical_value: 0,
        user_id: null
      },
      rules: {
        user_id: [
          { required: true, message: "请选择平台用户！", trigger: "change" }
        ]
      },
      areaData:[],
      searchValue:[],
      sexList:['未知','男','女'],
      title:'',
      userList:[],
      userLoading:false
    }
  },
  async created() {
    await this.getList()
    await this.getAreaData()
  },
  methods: {
    searchHandleChange() {
      if (this.searchValue.length > 0) {
        this.listQuery.province_id = this.searchValue[0]?this.searchValue[0]:null
        this.listQuery.city_id = this.searchValue[1]?this.searchValue[1]:null
        this.listQuery.county_id = this.searchValue[2]?this.searchValue[2]:null  
      } else {
        this.listQuery.province_id = null
        this.listQuery.city_id = null
        this.listQuery.county_id = null
        this.searchValue = []
      }
      this.handleFilter()
    },
    eventStartTime(val){
      this.listQuery.created_at = val
      this.handleFilter()
    },
    async getAreaData(){
      getAreaData().then(response => {
        if(response.status === 20000){
          this.areaData = response.data
        }
      })
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      userIndex(this.listQuery).then(response => {
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
            nickname:'',
            sex:null,
            province_id:null,
            city_id:null,
            county_id:null,
            status:null,
            created_at:[],
            updated_at:[]
        }
        this.getList()
    },
    // 监听添加编辑对话框的关闭事件
    dialogClose() {
      this.form = {
        status: 1,
        empirical_value: 0,
        user_id: null
      }
      this.userList = []
    },
    add(){
      this.dialogVisible = true
      this.title = '添加'  
    },
    getUserList(query){
      if (query !== '') {
        this.userLoading = true
        getUserList({nickname:query}).then(response => {
          if(response.status === 20000){
            this.userList = response.data
          }
          this.userLoading = false
        })
      }else{
        this.userList = []
      }
    },
    // 打开编辑按钮对话框
    userEdit(id) {
      userEdit({id:id}).then(response => {
        if(response.status === 20000){
          this.title = '编辑'
          this.dialogVisible = true
          this.form = response.data
        }
      })
    },
      // 添加编辑按钮
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
          if(this.form.id){
            userUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }else{
            userStore(this.form).then(response => {
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
      userStatus({id:info.id,status:info.status}).then(response => {
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
        const tHeader = ['编号', '姓名','昵称','手机号','邮箱','地址','创建时间','状态','性别','经验值','出生年月日']
        const filterVal = ['id', 'name','nickname','phone','email','addess','created_at','status','sex','empirical_value','birth']
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
        if (j === 'addess') {
          return (v.user_to.province_to.name?v.user_to.province_to.name:'')+(v.user_to.city_to.name?v.user_to.city_to.name:'')+(v.user_to.county_to.name?v.user_to.county_to.name:'')
        }else if(j === 'sex'){
            return this.sexList[v.user_to[j]]
        }else if(j === 'name'){
            return v.user_to.name
        }else if(j === 'phone'){
            return v.user_to.phone
        }else if(j === 'email'){
            return v.user_to.email
        }else if(j === 'nickname'){
            return v.user_to.nickname
        }else if(j === 'status'){
            return this.statusList[v[j]]
        }else if(j === 'birth'){
            return v.user_to.birth
        } else {
          return v[j]
        }
      }))
    }  
  }
}
</script>