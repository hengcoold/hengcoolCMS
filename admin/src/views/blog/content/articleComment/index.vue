<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="请输入文章标题" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-input v-model="listQuery.nickname" placeholder="请输入昵称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-select v-model="listQuery.status" placeholder="请选择状态" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="(item,index) in statusList" :key="index" :label="item" :value="index"></el-option>
      </el-select>
      <s-date-picker :date="listQuery.created_at" @changeDateTime="eventStartTime"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
      </div>
    </div>
    <el-table
    v-loading="listLoading" :data="list"
    style="width: 100%"
    row-key="content"
    border
    lazy
    :load="load"
    :tree-props="{children: 'children', hasChildren: 'hasChildren'}">  
      <el-table-column width="200px" label="评论内容">
        <template slot-scope="{row}">
           <span>{{ row.content }}</span>
        </template>
      </el-table-column>
      <el-table-column width="200px" label="昵称">
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
      <el-table-column width="200px" label="文章标题">
        <template slot-scope="{row}">
           <span>{{ row.article_to.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="排序" align="center" prop="sort" width="80" sortable>
          <template slot-scope="{row}">
            <el-input v-model="row.sort" @blur="articleCommentSorts(row)"></el-input>
          </template>
        </el-table-column>  
      <el-table-column align="center" label="状态" prop="status" width="80px">
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
              @change="articleCommentStatus(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="230px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves size="mini" type="danger" @click="articleCommentDestroy(row.id,row.article_id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { articleCommentIndex,articleCommentStatus,articleCommentSorts,articleCommentDestroy } from '@/api/blog/articleComment'
export default {
  name: 'ArticleCommentIndex',
  data() {
    return {
      statusList:['禁用','启用'],
      list: [],
      listLoading: true,
      listQuery: {
        title:"",
        pid:0,
        status:null,
        nickname:"",
        created_at:[],
        updated_at:[]
      },
    }
  },
  async created() {
    await this.getList()
  },
  mounted() {
    if(this.$router.currentRoute.query){
      this.listQuery.title = this.$router.currentRoute.query.title
    }
  },
  methods: {
    eventStartTime(val){
      this.listQuery.created_at = val
      this.handleFilter()
    },
    // 搜索
    handleFilter() {
      this.getList()
    },
     // 重置
    refresh(){
        this.listQuery = {
          title:"",
          pid:0,
          status:null,
          nickname:"",
          created_at:[],
          updated_at:[]
        }
        this.getList()
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      articleCommentIndex(this.listQuery).then(response => {
        if(response.status === 20000){
          this.list = response.data
        }
        this.listLoading = false
      })
    },
    load(tree, treeNode, resolve) {
      this.listQuery.pid = tree.id
      articleCommentIndex(this.listQuery).then(response => {
        if(response.status === 20000){
          resolve(response.data)
        }
      })
    },
    // 状态调整
    articleCommentStatus(info) {
      articleCommentStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    // 删除
    articleCommentDestroy(id,article_id) {
      this.$base.confirm(
        { content: "确定要删除该项吗（会同时删除子级）！" },
        () => {
          articleCommentDestroy({id:id,article_id:article_id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    articleCommentSorts(info){
      articleCommentSorts({id:info.id,sort:info.sort}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          this.getList()
        }
      })
    }
  }
}
</script>