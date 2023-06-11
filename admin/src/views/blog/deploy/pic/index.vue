<template>
  <div class="app-container">
    <div class="filter-container">
      <el-select v-model="listQuery.status" placeholder="请选择状态" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="(item,index) in statusList" :label="item" :value="index" :key="index"></el-option>
      </el-select>
      <el-select v-model="listQuery.type" placeholder="请选择类型" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option label="首页轮播图" value="0"></el-option>
      </el-select>
      <s-date-picker :date="listQuery.created_at" @changeDateTime="eventStartTime"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
        <el-button v-waves class="filter-item" type="primary" icon="el-icon-edit" @click="add">
          添加
        </el-button>
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
          导出
        </el-button>
      </div>
      
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="编号" width="80px" prop="id" sortable></el-table-column>
      <el-table-column label="图片类型" align="center" prop="type" width="120">
        <template slot-scope="{row}">
          <span>{{typeList[row.type]}}</span>
        </template>
      </el-table-column>
      <el-table-column width="150px" align="center" label="图片描述" prop="content"></el-table-column>
      <el-table-column width="150px" align="center" label="跳转地址" prop="url"></el-table-column>
      <el-table-column label="图片" align="center" prop="image_url" width="80">
          <template slot-scope="{row}">
            <el-image class="list_image" :src="row.image_url" :preview-src-list="[row.image_url]">
              <div class="image-error">暂无图片</div>
            </el-image>
          </template>
      </el-table-column>
      <el-table-column label="排序" align="center" prop="sort" width="80" sortable>
        <template slot-scope="{row}">
          <el-input v-model="row.sort" @blur="picSorts(row)"></el-input>
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
              @change="picStatus(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="150px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="primary" size="mini" @click="picEdit(row.id)">
            编辑
          </el-button>
          <el-button v-waves size="mini" type="danger" @click="picDestroy(row.id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    <!-- 添加编辑对话框 -->
    <el-dialog   :title="title" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
      <!-- 主体区 -->
      <el-form label-width="100px" :model="form" :rules="rules" ref="ref">
        <el-form-item label="图片类型">
          <el-select v-model="form.type" placeholder="请选择类型" clearable style="width: 100%" class="filter-item">
            <el-option v-for="(item,index) in typeList" :key="index"  :label="item" :value="index"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="图片描述">
          <el-input placeholder="请输入图片描述" maxlength="200" clearable show-word-limit v-model="form.content"
          ></el-input>
        </el-form-item>
         <el-form-item label="跳转地址">
          <el-input placeholder="请输入跳转地址" maxlength="200" clearable show-word-limit v-model="form.url"
          ></el-input>
        </el-form-item>
        <el-form-item label="图片" prop="image_id">
            <s-file-image :image_list="imageList" @confirmImsge="confirmImsge"></s-file-image>
        </el-form-item>
        <el-form-item label="显示状态">
          <el-radio-group v-model="form.status" size="medium">
            <el-radio v-for="(item, index) in statusList" :key="index" border :label="index">{{item}}</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input type="number" placeholder="请输入排序" maxlength="10" clearable show-word-limit v-model="form.sort"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="dialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="primary">确 定</el-button>
      </span>
    </el-dialog>  
  </div>
</template>
<script>
import sFileImage from "@/components/common/sFileImage/sFileImage"
import { picIndex,picStatus,picStore,picEdit,picUpdate,picSorts,picDestroy } from '@/api/blog/pic'
export default {
  name: 'PicIndex',
  components: {
    "s-file-image": sFileImage
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
      typeList:['首页轮播图'],
      statusList:['禁用','启用'],
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        status:null,
        type:null,
        created_at:[],
        updated_at:[]
      },
      dialogVisible:false,
      downloadLoading: false,
      form: {
        id:null,  
        type:0,
        content:"",
        status:1,
        image_id:null,
        sort:1
      },
      imageList:[],
      rules: {
        image_id: [
          { required: true, message: "选择图片", trigger: "blur" },
        ],
        sort: [
          { required: true, message: "请输入排序！", trigger: "blur" },
          { validator: checkSort, trigger: "blur" }
        ]
      },
      title:''
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
    // 图片上传回调
    confirmImsge(res) {
      this.form.image_id = res[0].id
    },
    // 获取表格列表
    async getList() {
      this.listLoading = true
      picIndex(this.listQuery).then(response => {
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
          type:null,
          created_at:[],
          updated_at:[]
        }
        this.getList()
    },
    // 监听添加对话框的关闭事件
    dialogClose() {
      this.form = {
         id:null,  
        type:0,
        content:"",
        status:1,
        image_id:null,
        sort:1
      }
      this.imageList = []
    },
    add(){
      this.dialogVisible = true
      this.title = '添加'
    },
    // 打开编辑按钮对话框
    picEdit(id) {
      picEdit({id:id}).then(response => {
        if(response.status === 20000){
          this.title = '编辑'
          this.dialogVisible = true
          this.form = response.data
          this.imageList = [
            {
              id: response.data.image_id,
              url: response.data.image_url
            }
          ]
        }
      })
    },
      // 添加编辑按钮
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
          if(this.form.id){
            picUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }else{
            picStore(this.form).then(response => {
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
    picStatus(info) {
      picStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    // 删除
    picDestroy(id) {
      this.$base.confirm(
        { content: "确定要删除该项吗！" },
        () => {
          picDestroy({id:id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    picSorts(info){
      picSorts({id:info.id,sort:info.sort}).then(response => {
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
        const tHeader = ['编号', '图片类型','图片描述','跳转地址','状态','创建时间']
        const filterVal = ['id', 'type','content','url','status','created_at']
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
        if(j==='type'){
            return this.typeList[v[j]]
        }else if(j === 'status'){
          return this.statusList[v[j]]
        }else{
          return v[j]
        }
      }))
    }  
  }
}
</script>