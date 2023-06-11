<template>
  <div class="app-container">
    <div class="filter-container">
      
    <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        导出
    </el-button>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="文件名称" width="300px">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" prop="time" label="备份时间" sortable></el-table-column>
      <el-table-column width="120px" align="center" prop="sortSize" label="文件大小"></el-table-column>
      <el-table-column label="操作" align="center" width="250px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="primary" size="mini" :disabled="row.infoLoading" :loading="row.infoLoading" @click="getInfo(row)" icon="fa fa-info-circle">
            详情
          </el-button>
          <el-button v-waves type="success" size="mini" @click="downloadFiles(row.url)">
            下载
          </el-button>
          <el-button v-waves type="danger" size="mini" @click="dataBaseDelSqlFiles(row.name)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 查看表详情 -->
    <el-dialog   title="详情" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
        <el-input type="textarea"  v-model="info" rows="15"></el-input>
    </el-dialog>   
    </div>
  </div>
</template>

<script>
import { dataBaseRestoreData,dataBaseGetFiles,dataBaseDelSqlFiles } from '@/api/admin/dataBase'
export default {
  name: 'RestoreDataIndex',
  data() {
    return {
      list: null,
      listLoading: true,
      dialogVisible:false,
      downloadLoading:false,
      infoLoading:false,
      info:''
    }
  },
  async created() {
    await this.getList()
  },
  methods: {
    // 获取表格列表
    async getList() {
      this.listLoading = true
      dataBaseRestoreData().then(response => {
        if(response.status === 20000){
            this.list = response.data
        }
        this.listLoading = false
      })
    },
    // 关闭详情
    dialogClose() {
      this.info = ''
    },
    // 查看详情
    getInfo(row){
      row.infoLoading = true
      console.log(row)
      dataBaseGetFiles({name:row.name}).then(response => {
          row.infoLoading = false
          if(response.status === 20000){
              this.dialogVisible = true
              this.info = response.data.info
          }
      })
    },
    
    // 删除
    dataBaseDelSqlFiles(name) {
      this.$base.confirm(
        { content: "确定要删除当前文件吗（如果确认将无法恢复）！" },
        () => {
          dataBaseDelSqlFiles({name:name}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['表名', '数据条数','类型','排序规则','创建时间','创建时间','更新时间','备注','占用空间']
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
    },
    downloadFiles(url){
      const aTag = document.createElement('a')
      aTag.href = url
      aTag.click()
    }  
  }
}
</script>