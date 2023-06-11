<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.username" placeholder="请输入管理员账号" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-input v-model="listQuery.url" placeholder="请输入操作路由" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-input v-model="listQuery.method" placeholder="请输入请求方式" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <s-date-picker :date="listQuery.created_at" @changeDateTime="eventStartTime" startPlaceholder="开始操作时间" endPlaceholder="结束操作时间"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
        <el-button v-waves class="filter-item" type="danger" :disabled="multipleSelection.length===0" icon="el-icon-delete" @click="operationLogDestroyAll">
          删除
        </el-button>
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
          导出
        </el-button>
      </div>
      
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%"  @selection-change="handleSelectionChange">
      <el-table-column type="selection" align="center" width="55"></el-table-column>
      <el-table-column align="center" label="编号" width="80px">
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column width="120px" align="center" label="管理员">
        <template slot-scope="{row}">
          <span>{{ row.admin_one.username }}</span>
        </template>
      </el-table-column>
      <el-table-column width="300px" align="center" label="操作路由">
        <template slot-scope="{row}">
          <span>{{ row.url }}</span>
        </template>
      </el-table-column>
      <el-table-column width="150px" align="center" label="操作描述">
        <template slot-scope="{row}">
          <span>{{ row.content }}</span>
        </template>
      </el-table-column>
      <el-table-column width="120px" align="center" label="请求方式">
        <template slot-scope="{row}">
          <el-link :type="typeList[row.method]" @click="getinfo(row)">{{ row.method }}</el-link>
        </template>
      </el-table-column>
      <el-table-column width="120px" align="center" label="IP">
        <template slot-scope="{row}">
          <span>{{ row.ip }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="操作时间">
        <template slot-scope="{row}">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="120px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves size="mini" type="danger" @click="operationLogDestroy(row.id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <el-dialog   title="请求详情" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
      <el-form label-width="100px">
        <el-form-item label="请求参数">
          <json-editor ref="jsonEditor" v-model="info.data" />
        </el-form-item>
        <el-form-item label="header头信息">
          <json-editor ref="jsonEditor" v-model="info.header" />
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { operationLogIndex,operationLogDestroy,operationLogDestroyAll } from '@/api/admin/operationLog'
import JsonEditor from '@/components/JsonEditor'
export default {
  name: 'OperationLogIndex',
  components: { JsonEditor },
  data() {
    return {
      list: [],
      total: 0,
      listLoading: true,
      downloadLoading:false,
      dialogVisible:false,
      listQuery: {
        page: 1,
        limit: 10,
        url:null,
        method:null,
        username:null,
        url:null,
        created_at:[]
      },
      multipleSelection:[],
      info:{
          data:{},
          header:{}
      },
      typeList:{
        'GET':'primary',
        'POST':'success',
        'PUT':'warning',
        'DELETE':'danger'
      }
        
      
    }
  },
  async created() {
    await this.getList()
  },
  methods: {
    handleSelectionChange(val) {
        this.multipleSelection = val
    },  
    eventStartTime(val){
      this.listQuery.created_at = val
      this.handleFilter()
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      operationLogIndex(this.listQuery).then(response => {
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
          url:null,
          method:null,
          username:null,
          url:null,
          created_at:[]
        }
        this.getList()
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['编号', '管理员','操作描述','操作路由','请求方式','IP','操作时间']
        const filterVal = ['id', 'admin_one','content','url','method','ip','created_at']
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
        if (j === 'admin_one') {
          return v[j].username
        } else {
          return v[j]
        }
      }))
    },
    // 删除
    operationLogDestroy(id) {
      this.$base.confirm(
        { content: "确定要删除该项吗！" },
        () => {
          operationLogDestroy({id:id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    operationLogDestroyAll() {
      if(this.multipleSelection.length<=0){
          this.$base.message({ message: '请选择需要删除的选项！' })
          return
      }  
        const idArr = this.multipleSelection.map((item, index) => {return item.id})  
      this.$base.confirm(
        { content: "确定要删除所选项吗！" },
        () => {
          operationLogDestroyAll({idArr:idArr}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    getinfo(info){
      this.dialogVisible = true
      info.data = JSON.parse(info.data)
      info.header = JSON.parse(info.header)
      this.info = info
    },
    // 监听添加编辑对话框的关闭事件
    dialogClose() {
      this.info = {
        data: "",
        header: ""
      }
    }  
  }
}
</script>