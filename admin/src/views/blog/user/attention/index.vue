<template>
  <div class="app-container">
    <div class="filter-container"> 
      <el-input v-model="listQuery.nickname" placeholder="请输入用户昵称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-input v-model="listQuery.attention_nickname" placeholder="请输入关注者昵称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
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
      <el-table-column width="300px" align="center" label="昵称">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="'姓名:'+row.user_to.user_to.name+'；邮箱：'+row.user_to.user_to.email"
            placement="top"
            :enterable="false">
           <span class="s-pointer">{{ row.user_to.user_to.nickname }}</span>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column width="300px" align="center" label="关注者昵称">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="'姓名:'+row.user_attention_to.user_to.name+'；邮箱：'+row.user_attention_to.user_to.email"
            placement="top"
            :enterable="false">
           <span class="s-pointer">{{ row.user_attention_to.user_to.nickname }}</span>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column width="200px" align="center" label="关注时间">
        <template slot-scope="{row}">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import { attentionIndex } from '@/api/blog/user'
export default {
  name: 'AttentionIndex',
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
        attention_nickname:null,
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
      attentionIndex(this.listQuery).then(response => {
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
          attention_nickname:null,
          nickname:null,
          created_at:[]
        }
        this.getList()
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['编号', '昵称', '姓名', '邮箱','关注者昵称','关注者姓名','关注者邮箱','关注时间']
        const filterVal = ['id', 'nickname', 'name', 'email','attention_nickname','attention_name', 'attention_email','created_at']
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
        if (j === 'attention_nickname') {
          return v.user_attention_to.user_to.nickname
        }else if (j === 'attention_name') {
          return v.user_to.user_to.name
        }else if (j === 'attention_email') {
          return v.user_to.user_to.email
        }else if (j === 'nickname') {
          return v.user_to.user_to.nickname
        }else if (j === 'email') {
          return v.user_to.user_to.email
        }else if (j === 'name') {
          return v.user_to.user_to.name
        } else {
          return v[j]
        }
      }))
    }
  }
}
</script>