<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.name" placeholder="请输入表名" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
    <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
    <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        导出
    </el-button>
    <el-button v-waves class="filter-item" type="success" :disabled="multipleSelection.length===0" icon="fa fa-window-restore" @click="dataBaseBackUp" :loading="dataBaseBackUpLoading">
          备份
        </el-button>
    </div>
    <el-button v-waves class="filter-item" type="success" disabled icon="fa fa-calendar-minus-o">
        占用总空间（{{total}}）
    </el-button>
    <el-button v-waves class="filter-item" type="success" disabled icon="fa fa-window-maximize">
        数据总条数（{{tableNum}}）条
    </el-button>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%" @selection-change="handleSelectionChange">
        <el-table-column type="selection" align="center" width="55"></el-table-column>
      <el-table-column align="center" label="表名" width="200px">
        <template slot-scope="{row}">
          <span>{{ row.Name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="80px" align="center" label="数据条数">
        <template slot-scope="{row}">
          <span>{{ row.Rows }}</span>
        </template>
      </el-table-column>
      <el-table-column width="80px" align="center" label="类型">
        <template slot-scope="{row}">
          <span>{{ row.Engine }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="排序规则">
        <template slot-scope="{row}">
          <span>{{ row.Collation }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="创建时间">
        <template slot-scope="{row}">
          <span>{{ row.Create_time }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="更新时间">
        <template slot-scope="{row}">
          <span>{{ row.Update_time }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="备注">
        <template slot-scope="{row}">
          <span>{{ row.Comment }}</span>
        </template>
      </el-table-column>
      <el-table-column width="120px" align="center" label="占用空间">
        <template slot-scope="{row}">
          <span>{{ row.size }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="150px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="primary" size="mini" @click="dataBaseTableData(row.Name)">
            详情
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 查看表详情 -->
    <el-dialog   title="详情" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
        <el-button v-waves type="primary" size="mini" disabled>
            字段数量（{{info.tableNum}}）
        </el-button>
      <el-table :data="info.data" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="字段名" width="100px">
        <template slot-scope="{row}">
          <span>{{ row.COLUMN_NAME }}</span>
        </template>
      </el-table-column>
      <el-table-column width="200px" align="center" label="数据类型">
        <template slot-scope="{row}">
          <span>{{ row.COLUMN_TYPE }}</span>
        </template>
      </el-table-column>
      <el-table-column width="80px" align="center" label="默认值">
        <template slot-scope="{row}">
          <span>{{ row.COLUMN_DEFAULT }}</span>
        </template>
      </el-table-column>
      <el-table-column width="100px" align="center" label="允许非空">
        <template slot-scope="{row}">
          <span>{{ row.IS_NULLABLE }}</span>
        </template>
      </el-table-column>
      <el-table-column width="100px" align="center" label="自动递增">
        <template slot-scope="{row}">
          <span>{{ row.EXTRA }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="备注">
        <template slot-scope="{row}">
          <span>{{ row.COLUMN_COMMENT }}</span>
        </template>
      </el-table-column>
    </el-table>
    </el-dialog>   
  </div>
</template>

<script>
import { dataBaseIndex,dataBaseTableData,dataBaseBackUp } from '@/api/admin/dataBase'
export default {
  name: 'DataBaseIndex',
  data() {
    return {
      list: null,
      listLoading: true,
      tableNum:0, // 表数量
      total:'',  // 总大小
      listQuery: {
        name:""
      },
      dialogVisible:false,
      downloadLoading:false,
      info:{
          data:[],
          tableNum:0
      },
      multipleSelection:[],
      dataBaseBackUpLoading:false
    }
  },
  async created() {
    await this.getList()
  },
  methods: {
    handleSelectionChange(val) {
        this.multipleSelection = val
    },
    dataBaseBackUp(){
        if(this.multipleSelection.length<=0){
            this.$base.message({ message: '请选择需要需要备份的数据表！' })
            return
        }  
        
        const tables = this.multipleSelection.map((item, index) => {return item.Name})  
        this.$base.confirm(
            { content: "确定要备份所选数据表吗（备份完成请在备份管理中进行查看）！" },
            () => {
                this.dataBaseBackUpLoading = true
                dataBaseBackUp({tables:tables}).then(response => {
                    if(response.status === 20000){
                        this.$base.message({ message: response.message })
                    }
                    this.dataBaseBackUpLoading = false
                })
            }
        )
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      dataBaseIndex(this.listQuery).then(response => {
        if(response.status === 20000){
            this.list = response.data.data
            this.total = response.data.total
            this.tableNum = response.data.tableNum
        }
        this.listLoading = false
      })
    },
    // 搜索
    handleFilter() {
      this.getList()
    },
     // 重置
    refresh(){
        this.listQuery = {
          name:""
        }
        this.getList()
    },
    // 表详情
    dialogClose() {
      this.info = {
          data:[],
          tableNum:0
      }
    },
    // 详情
    dataBaseTableData(table) {
        dataBaseTableData({table:table}).then(response => {
            if(response.status === 20000){
                this.dialogVisible = true
                this.info = response.data
                
            }
        })
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['表名', '数据条数','类型','排序规则','创建时间','更新时间','备注','占用空间']
        const filterVal = ['Name', 'Rows','Engine','Collation','Create_time','Update_time','Comment','size']
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