<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="请输入文章标题" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-cascader :options="articleTypeList"
        placeholder="请选择所属分类"
      :props="{
          expandTrigger: 'hover',
          value: 'id',
          label: 'name',
          checkStrictly: true
        }" 
        v-model="searchValue"
        @change="searchHandleChange"
        clearable filterable style="width:300px"></el-cascader>
      <el-input v-model="listQuery.nickname" placeholder="请输入用户昵称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-select v-model="listQuery.status" placeholder="请选择状态" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="(item,index) in statusList" :key="index" :label="item" value="index"></el-option>
      </el-select>
      <el-select v-model="listQuery.status" placeholder="请选择是否推荐" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="(item,index) in openList" :key="index" :label="item" value="index"></el-option>
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
        <el-button v-waves class="filter-item" type="danger" :disabled="multipleSelection.length===0" icon="el-icon-delete" @click="articlecDestroyAll">
          删除
        </el-button>
      </div>
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%" @selection-change="handleSelectionChange">
      <el-table-column type="selection" align="center" width="55"></el-table-column>
      <el-table-column align="center" label="编号" width="80px" prop="id" sortable fixed="left"></el-table-column>
      <el-table-column width="120px" align="center" label="文章标题">
        <template slot-scope="{row}">
          <span>{{ row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column width="120px" align="center" label="所属分类">
        <template slot-scope="{row}">
          <span>{{ row.type_to.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="140px" align="center" label="用户昵称">
        <template slot-scope="{row}">
           <el-tooltip
            effect="dark"
            :content="'邮箱：'+row.user_to.user_to.email"
            placement="top"
            :enterable="false">
           <span>{{ row.user_to.user_to.nickname }}</span>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column width="100px" align="center" prop="comment_many_count" label="评论" sortable></el-table-column>
      <el-table-column width="100px" align="center" prop="like_many_count" label="收藏" sortable></el-table-column>
      <el-table-column width="100px" align="center" prop="collect_many_count" label="点赞" sortable></el-table-column>
      <el-table-column width="100px" align="center" prop="pv_many_count" label="阅读" sortable></el-table-column>
      <el-table-column label="图片" align="center" prop="image_url" width="80">
          <template slot-scope="{row}">
            <el-image class="list_image" :src="row.image_url" :preview-src-list="[row.image_url]">
              <div class="image-error">暂无图片</div>
            </el-image>
          </template>
      </el-table-column>
      <el-table-column label="排序" align="center" prop="sort" width="80" sortable>
        <template slot-scope="{row}">
          <el-input v-model="row.sort" @blur="articleSorts(row)"></el-input>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="创建时间" prop="created_at" sortable></el-table-column>
      <el-table-column align="center" label="状态" prop="status" width="100px">
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
              @change="articleStatus(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column align="center" label="是否推荐" prop="open" width="100px">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="openList[row.open]"
            placement="top"
            :enterable="false">
            <el-switch
              v-model="row.open"
              active-color="#5FB878"
              inactive-color="#d2d2d2"
              :active-value="1"
              :inactive-value="0"
              @change="articleOpen(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="250px" class-name="small-padding fixed-width" fixed="right">
        <template slot-scope="{row}">
          <router-link :to="{path: '/blog/content/articleComment/index', query: {title: row.title}}">
            <el-button v-waves type="success" size="mini" style="margin-right: 10px;">评论详情</el-button>
          </router-link> 
          <el-button v-waves type="primary" size="mini" @click="articleEdit(row.id)">
            编辑
          </el-button>
          <el-button v-waves size="mini" type="danger" @click="articleDestroy(row.id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <!-- 添加编辑对话框 -->
    <el-dialog   :title="title" :visible.sync="dialogVisible" fullscreen @close="dialogClose">
      <!-- 主体区 -->
      <el-form label-width="100px" :model="form" :rules="rules" ref="ref">
        <el-row :gutter="20">
          <el-col :span="10">
            <el-form-item label="所属分类" prop="type_id">
              <el-cascader :options="articleTypeList"
                placeholder="请选择所属分类"
              :props="{
                  expandTrigger: 'hover',
                  value: 'id',
                  label: 'name',
                  checkStrictly: true
                }" 
                v-model="value"
                @change="handleChange"
                clearable filterable style="width:100%"></el-cascader>
            </el-form-item>
          </el-col>
          <el-col :span="10" v-if="!form.id">
            <el-form-item label="用户" prop="user_id">
                <el-select v-model="form.user_id" filterable clearable
                remote reserve-keyword :remote-method="getUserList" :loading="userLoading"
                 placeholder="请选择用户" style="width:100%">
                    <el-option
                    v-for="(item,index) in userList"
                    :key="index"
                    :label="item.user_to.nickname+'->'+item.user_to.email"
                    :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item> 
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="17">
            <el-form-item label="文章标题" prop="title">
              <el-input placeholder="请输入文章标题" maxlength="100" clearable show-word-limit v-model="form.title"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="3">
            <el-button v-waves class="filter-item" type="info" @click="generateContent(1)">
              生成标题
            </el-button>
          </el-col>
        </el-row>
        <el-form-item label="缩略图">
            <s-file-image :image_list="imageList" @confirmImsge="confirmImsge"></s-file-image>
        </el-form-item>
        <el-form-item label="文章内容" prop="content">
          <tinymce v-model="form.content" ref="content" :height="300" @confirmImsge="TConfirmImsge"/>
        </el-form-item>
        <el-row :gutter="20">
          <el-col :span="17">
            <el-form-item label="文章描述" prop="description">
              <el-input placeholder="请输入文章描述" type="textarea" maxlength="200" clearable show-word-limit v-model="form.description"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="3">
            <el-button v-waves class="filter-item" type="info" @click="generateContent(2)">
              生成描述
            </el-button>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="17">
            <el-form-item label="文章标签">
              <el-select
                v-model="form.labelArr"
                multiple
                filterable
                allow-create
                default-first-option
                placeholder="请选择文章标签" style="width:100%">
                <el-option
                  v-for="(item,index) in labelList"
                  :key="index"
                  :label="item.name"
                  :value="item.name">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="3">
            <el-button v-waves class="filter-item" type="info" @click="generateContent(3)">
              生成标签
            </el-button>
          </el-col>
        </el-row>
        <el-row :gutter="21">
          <el-col :span="7">
            <el-form-item label="显示状态">
              <el-radio-group v-model="form.status" size="medium">
                <el-radio border v-for="(item,index) in statusList" :key="index" :label="index">{{item}}</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="7">
            <el-form-item label="是否推荐">
              <el-radio-group v-model="form.open" size="medium">
                <el-radio border v-for="(item,index) in openList" :key="index" :label="index">{{item}}</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="7">
            <el-form-item label="排序" prop="sort">
              <el-input type="number" placeholder="请输入排序" maxlength="10" clearable show-word-limit v-model="form.sort"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="dialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="primary()">确 定</el-button>
      </span>
    </el-dialog>  
  </div>
</template>
<script>
import sFileImage from "@/components/common/sFileImage/sFileImage"
import Tinymce from '@/components/common/Tinymce'
import { articleIndex,getArticleTypeList,getLabelList,getUserList,articleStatus,articleStore,articleEdit,articleUpdate,articleSorts,articleDestroy,articleOpen,generateContent,articlecDestroyAll } from '@/api/blog/article'
export default {
  name: 'ArticleIndex',
  components: {
    "s-file-image": sFileImage,
    Tinymce
  },
  data() {
      var checkSort = (rule, value, callback) => {
      // 定义正则表达式
      const regSort = /^[1-9]{1}[0-9]{0,10}$/;
      if (regSort.test(value)) {
        return callback();
      }
      callback(new Error("请输入排序（大于0的数字）!"));
    };
    return {
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        status:null,
        title:"",
        open:null,
        nickname:'',
        type_id:null,
        created_at:[],
        updated_at:[]
      },
      dialogVisible:false,
      downloadLoading: false,
      form: {
        id:null,  
        type_id:null,
        user_id:null,
        title:"",
        description:"",
        content:"",
        image_id:null,
        status:1,
        open:0,
        sort:1,
        labelArr:[]
      },
      imageList:[],
      rules: {
        type_id: [
          { required: true, message: "请选择所属分类！", trigger: "blur" },
        ],
        user_id: [
          { required: true, message: "请选择会员！", trigger: "blur" },
        ],
        title: [
          { required: true, message: "请输入文章标题！", trigger: "blur" },
        ],
        description: [
          { required: true, message: "请输入文章描述！", trigger: "blur" },
        ],
        content: [
          { required: true, message: "请输入文章内容！", trigger: "blur" },
        ],
        sort: [
          { required: true, message: "请输入排序！", trigger: "blur" },
          { validator: checkSort, trigger: "blur" }
        ]
      },
      title:'',
      articleTypeList:[],
      value:[],
      userList:[],
      searchValue:[],
      labelList:[],
      userLoading:false,
      statusList:['隐藏','显示'],
      openList:['否','是'],
      multipleSelection:[]
    }
  },
  async created() {
    await this.getList()
    await this.getArticleTypeList()
  },
  methods: {
    handleSelectionChange(val) {
        this.multipleSelection = val
    }, 
    generateContent(status){
      if(this.form.content.length===0){
        this.$base.message({ message: '请填写文章内容！' })
        return
      }
      generateContent({status:status,content:this.form.content}).then(response => {
        if(response.status === 20000){
          this.form.labelArr = response.data
        }
      })
    },
    // 重置
    refresh(){
        this.listQuery = {
          page: 1,
          limit: 10,
          status:null,
          title:"",
          open:null,
          nickname:'',
          type_id:null,
          created_at:[],
          updated_at:[]
        }
        this.searchValue = []
        this.getList()
    },
    eventStartTime(val){
      this.listQuery.created_at = val
      this.handleFilter()
    },
    // 搜索获取选择器值
    searchHandleChange() {
      if (this.searchValue.length > 0) {
        this.listQuery.type_id = this.searchValue[this.searchValue.length - 1]
      } else {
        this.listQuery.type_id = null
      }
      this.handleFilter()
    },
    // 添加编辑获取选择器值
    handleChange() {
      if (this.value.length > 0) {
        this.form.type_id = this.value[this.value.length - 1]
      } else {
        this.form.type_id = null
      }
    },
    // 图片上传回调
    confirmImsge(res) {
      this.form.image_id = res[0].id
    },
    // 编辑器图片回调
    TConfirmImsge(res){
      this.form.image_id = res[0].id
      if(this.imageList.length===0){
        this.imageList = [
          {
            id: res[0].id,
            url: res[0].url
          }
        ]
      }
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      articleIndex(this.listQuery).then(response => {
        if(response.status === 20000){
          this.list = response.data.list
          this.total = response.data.total
        }
        this.listLoading = false
      })
    },
    async getArticleTypeList(){
      getArticleTypeList().then(response => {
        if(response.status === 20000){
          this.articleTypeList = response.data
        }
      })
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
    // 搜索
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    // 监听添加对话框的关闭事件
    dialogClose() {
      this.form = {
        id:null,  
        type_id:null,
        user_id:null,
        title:"",
        description:"",
        content:"",
        image_id:null,
        status:1,
        open:0,
        sort:1,
        labelArr:[]
      }
      this.imageList = []
      this.userList = []
      this.$refs.content.setContent("")
    },
    add(){
      this.dialogVisible = true
      this.title = '添加'
      this.form.type_id = this.articleTypeList[0].children[0].id
      this.value = [this.articleTypeList[0].id,this.articleTypeList[0].children[0].id]
      this.getLabelList([])
    },
    getLabelList(nameArr){
      getLabelList({nameArr:nameArr}).then(response => {
        if(response.status === 20000){
          this.labelList = response.data
        }
      })
    },
    // 打开编辑按钮对话框
    articleEdit(id) {
      articleEdit({id:id}).then(response => {
        if(response.status === 20000){
          this.title = '编辑'
          this.dialogVisible = true
          this.form = response.data
          
          this.value = response.data.value
          if(response.data.image_id){
            this.imageList = [
              {
                id: response.data.image_id,
                url: response.data.image_url
              }
            ]
          }
          this.$refs.content.setContent(response.data.content)
        }
      })
    },
      // 添加编辑按钮
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
          if(this.form.id){
            articleUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }else{
            articleStore(this.form).then(response => {
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
    articleStatus(info) {
      articleStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    articleOpen(info) {
      articleOpen({id:info.id,open:info.open}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.open = info.open?0:1
        }
      })
    },
    // 删除
    articleDestroy(id) {
      this.$base.confirm(
        { content: "确定要删除该项吗！" },
        () => {
          articleDestroy({id:id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    articlecDestroyAll() {
      if(this.multipleSelection.length<=0){
          this.$base.message({ message: '请选择需要删除的选项！' })
          return
      }  
        const idArr = this.multipleSelection.map((item, index) => {return item.id})  
      this.$base.confirm(
        { content: "确定要删除所选项吗！" },
        () => {
          articlecDestroyAll({idArr:idArr}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    articleSorts(info){
      articleSorts({id:info.id,sort:info.sort}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          this.getList()
        }
      })
    },
    // 导出数据
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['编号', '文章标题','所属分类','发布用户','评论','收藏','点赞','阅读','状态','是否推荐','创建时间']
        const filterVal = ['id', 'title','type_to','user_to','comment_many_count','like_many_count','collect_many_count','pv_many_count','status','open','created_at']
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
        if(j==='type_to'){
            return v.type_to.name
        }else if(j==='user_to'){
            return v.user_to.user_to.email
        }else if(j==='status'){
            return this.statusList[v.status]
        }else if(j==='open'){
            return this.openList[v.open]
        }else{
          return v[j]
        }
        
      }))
    }  
  }
}
</script>