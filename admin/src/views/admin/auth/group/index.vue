<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.name" placeholder="请输入权限组名称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-select v-model="listQuery.status" placeholder="请选择状态" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option label="禁用" value="0"></el-option>
        <el-option label="启用" value="1"></el-option>
      </el-select>
      <s-date-picker :date="listQuery.created_at" @changeDateTime="eventStartTime"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
        <el-button v-waves class="filter-item" type="primary" icon="el-icon-edit" @click="addDialogVisible = true">
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
      <el-table-column width="120px" align="center" label="权限组名称">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="250px" align="center" label="描述">
        <template slot-scope="{row}">
          <span>{{ row.content }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="创建时间">
        <template slot-scope="{row}">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="编辑时间">
        <template slot-scope="{row}">
          <span>{{ row.updated_at }}</span>
        </template>
      </el-table-column>
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
      <el-table-column label="操作" align="center" width="230px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="primary" size="mini" @click="edit(row.id)">
            编辑
          </el-button>
          <el-button v-waves type="success" size="mini" @click="groupAccess(row.id)">
            分配权限
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <!-- 添加对话框 -->
    <el-dialog   title="添加" :visible.sync="addDialogVisible" width="80%" @close="addDialogClose">
      <!-- 主体区 -->
      <el-form label-width="100px" :model="addForm" :rules="addRules" ref="addRef">
        <el-form-item label="权限组名称" prop="name">
          <el-input placeholder="请输入权限组名称" maxlength="20" clearable show-word-limit v-model="addForm.name"
          ></el-input>
        </el-form-item>
        <el-form-item label="描述">
          <el-input placeholder="请输入描述" type="textarea" maxlength="50" clearable show-word-limit v-model="addForm.content"
          ></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="addDialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="add()">确 定</el-button>
      </span>
    </el-dialog>  
    <!-- 编辑对话框 -->
    <el-dialog   title="编辑" :visible.sync="editDialogVisible" width="80%" @close="editDialogClose">
      <!-- 主体区 -->
      <el-form label-width="100px" :model="editForm" :rules="addRules" ref="editRef">
        <el-form-item label="权限组名称" prop="name">
          <el-input placeholder="请输入权限组名称" maxlength="20" clearable show-word-limit v-model="editForm.name"
          ></el-input>
        </el-form-item>
        <el-form-item label="描述">
          <el-input placeholder="请输入描述" type="textarea" maxlength="50" clearable show-word-limit v-model="editForm.content"
          ></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="editDialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="update()" >确 定</el-button>
      </span>
    </el-dialog>  
    </el-dialog>
    <!-- 分配权限对话框 -->
    <el-dialog   title="分配权限" :visible.sync="fpDialogVisible" width="80%" @close="fpDialogClose">
      <!-- 主体区 -->
      <el-tree show-checkbox :data="rightsList.ruleArr" :props="props" node-key="id" default-expand-all="true" :default-checked-keys="rightsList.defKeys" ref="fpRef"></el-tree>
      <!-- 按钮区 -->
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="fpDialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="groupAccessUpdate()" >确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { groupIndex,groupStatus,groupStore,groupEdit,groupUpdate,groupAccess,groupAccessUpdate } from '@/api/admin/group'
export default {
  name: 'GroupIndex',
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
      addDialogVisible:false,
      editDialogVisible:false,
      downloadLoading: false,
      fpDialogVisible: false,
      rightsList: [], // 所有权限的数据
      props: {
        // 所有权限的数据
        children: "children",
        label: "name"
      },
      addForm: {
        name: "",
        content:""
      },
      editForm: {
        id: "",
        name: "",
        content:""
      },
      addRules: {
        name: [
          { required: true, message: "请输入权限组名称！", trigger: "blur" }
        ]
      },
      editRules: {
        name: [
          { required: true, message: "请输入权限组名称！", trigger: "blur" }
        ]
      },

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
      groupIndex(this.listQuery).then(response => {
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
    addDialogClose() {
      this.addForm = {
        name: "",
        content:""
      }
      
      this.$refs.addRef.resetFields()
    },
    // 添加按钮
    add() {
      this.$refs.addRef.validate(valid => {
        if (valid) {
          groupStore(this.addForm).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.addDialogVisible = false
              this.getList()
            }
          })
        }
      })
    },
    // 状态调整
    setStatus(info) {
      groupStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    // 打开编辑按钮对话框
    edit(id) {
      groupEdit({id:id}).then(response => {
        if(response.status === 20000){
          this.editDialogVisible = true
          this.editForm = response.data
        }
      })
    },
    // 提交编辑数据
    update() {
      this.$refs.editRef.validate(valid => {
        if (valid) {
          groupUpdate(this.editForm).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.editDialogVisible = false
              this.getList()
            }
          })
        }
      })
    },
    // 监听编辑对话框的关闭事件
    editDialogClose() {
      this.editForm = {
        id: "",
        name: "",
        content:""
      }
      this.$refs.editRef.resetFields()
    },
    // 获取分配权限数据
    groupAccess(id){
      groupAccess({id:id}).then(response => {
        if(response.status === 20000){
          this.fpDialogVisible = true;
          this.rightsList = response.data;
        }
      })
    },
    // 监听分配权限对话框的关闭事件
    fpDialogClose() {
      this.rightsList = [];
    },
    // 提交分配权限数据
    groupAccessUpdate() {
      const keys = [
        ...this.$refs.fpRef.getCheckedKeys(),
        ...this.$refs.fpRef.getHalfCheckedKeys()
      ];
      if (keys.length > 0) {
        const data = {
          id: this.rightsList.id,
          rules: keys.join("|")
        }
        groupAccessUpdate(data).then(response => {
          if(response.status === 20000){
            this.$base.message({ message: response.message })
            this.fpDialogVisible = false;
          }
        })
      }else{
         this.$base.message({ message: '请选择配置项！' })
      }
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['编号', '权限组名称','描述','创建时间','编辑时间','状态']
        const filterVal = ['id', 'name','content','updated_at','created_at','status']
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