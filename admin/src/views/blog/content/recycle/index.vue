<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="请输入文章标题" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <s-date-picker :date="listQuery.deleted_at" @changeDateTime="eventStartTime"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
        <el-button v-waves class="filter-item" type="success" :disabled="multipleSelection.length===0" icon="el-icon-delete" @click="articleRecycleAll">
          恢复
        </el-button>
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
          导出
        </el-button>
      </div>
      
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%"  @selection-change="handleSelectionChange">
      <el-table-column type="selection" align="center" width="55"></el-table-column>
      <el-table-column align="center" label="编号" width="80px" sortable>
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column width="400px" align="center" label="文章名称">
        <template slot-scope="{row}">
          <span>{{ row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column width="200px" align="center" prop="deleted_at" label="删除时间" sortable>
      </el-table-column>
      <el-table-column label="操作" align="center" width="120px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves size="mini" type="success" @click="articleRecycle(row.id)">
            恢复
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import { recycleIndex,articleRecycle,articleRecycleAll } from '@/api/blog/article'
export default {
  name: 'RecycleIndex',
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
        title:'',
        deleted_at:[]
      },
      multipleSelection:[]
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
      this.listQuery.deleted_at = val
      this.handleFilter()
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      recycleIndex(this.listQuery).then(response => {
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
          title:'',
          deleted_at:[]
        }
        this.getList()
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['编号', '文章名称','删除时间']
        const filterVal = ['id', 'title','deleted_at']
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
    // 删除
    articleRecycle(id) {
      this.$base.confirm(
        { content: "确定要恢复该项吗！" },
        () => {
          articleRecycle({id:id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    articleRecycleAll() {
      if(this.multipleSelection.length<=0){
          this.$base.message({ message: '请选择需要恢复的选项！' })
          return
      }  
        const idArr = this.multipleSelection.map((item, index) => {return item.id})  
      this.$base.confirm(
        { content: "确定要恢复所选项吗！" },
        () => {
          articleRecycleAll({idArr:idArr}).then(response => {
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