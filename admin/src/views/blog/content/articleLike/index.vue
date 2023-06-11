<template>
  <div class="app-container">
    <div class="filter-container"> 
      <el-input v-model="listQuery.nickname" placeholder="请输入用户昵称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-input v-model="listQuery.title" placeholder="请输入文章标题" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <s-date-picker :date="listQuery.created_at" @changeDateTime="eventStartTime"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
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
      <el-table-column width="260px" align="center" label="文章标题">
        <template slot-scope="{row}">
          <span>{{ row.article_to.title }}</span>
        </template>
      </el-table-column>
      <el-table-column width="200px" align="center" label="用户昵称">
        <template slot-scope="{row}">
          <span>{{ row.user_to.user_to.nickname }}</span>
        </template>
      </el-table-column>
      <el-table-column width="200px" align="center" label="用户姓名">
        <template slot-scope="{row}">
          <span>{{ row.user_to.user_to.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="200px" align="center" label="用户邮箱">
        <template slot-scope="{row}">
          <span>{{ row.user_to.user_to.email }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="点赞时间">
        <template slot-scope="{row}">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import { articleLikeIndex } from '@/api/blog/articleLike'
export default {
  name: 'ArticleLikeIndex',
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
        title:null,
        nickname:null,
        created_at:[]
      }
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
      articleLikeIndex(this.listQuery).then(response => {
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
          title:null,
          nickname:null,
          created_at:[]
        }
        this.getList()
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['编号', '文章标题','用户昵称','用户姓名','用户邮箱','点赞时间']
        const filterVal = ['id', 'title','nickname','name','email','created_at']
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
        if (j === 'title') {
          return v.article_to[j]
        }else if (j === 'nickname') {
          return v.user_to.user_to[j]
        }else if (j === 'name') {
          return v.user_to.user_to[j]
        }else if (j === 'email') {
          return v.user_to.user_to[j]
        } else {
          return v[j]
        }
      }))
    }
  }
}
</script>