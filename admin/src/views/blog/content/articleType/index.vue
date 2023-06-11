<template>
  <div class="app-container">
      <div class="filter-container">
      <el-input v-model="listQuery.name" placeholder="请输入分类名称" style="width: 200px;" class="filter-item" clearable @change="handleFilter"/>
      <el-select v-model="listQuery.status" placeholder="请选择状态" clearable style="width: 200px" class="filter-item" @change="handleFilter">
        <el-option v-for="(item,index) in statusList" :key="index" :label="item" :value="index"></el-option>
      </el-select>
      <s-date-picker :date="listQuery.created_at" @changeDateTime="eventStartTime"></s-date-picker>
      <div>
        <el-button v-waves class="filter-item" type="info" icon="fa fa-refresh" @click="refresh">
          重置
        </el-button>
        <el-button v-waves class="filter-item" type="primary" icon="el-icon-edit" @click="add">
          添加
        </el-button>
      </div>
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%" row-key="name">
      <el-table-column minWidth="300px" label="分类名称">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="160px" align="center" label="创建时间" prop="created_at" sortable></el-table-column>
      <el-table-column label="排序" align="center" prop="sort" width="120" sortable>
        <template slot-scope="{row}">
          <el-input v-model="row.sort" @blur="setSorts(row)"></el-input>
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
              @change="setStatus(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="300px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="success" size="mini" @click="articleTypePidArr(row.id)">
            添加下级
          </el-button>
          <el-button v-waves type="primary" size="mini" @click="edit(row.id)">
            编辑
          </el-button>
          <el-button v-waves size="mini" type="danger" @click="articleTypeDestroy(row.id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table> 
    <!-- 添加编辑对话框 -->
    <el-dialog   :title="title" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
      <el-form label-width="100px" :model="form" :articleTypes="articleTypes" ref="ref">
        <el-form-item label="父级">
          <el-cascader :options="list"
            placeholder="默认顶级分类"
           :props="{
              expandTrigger: 'hover',
              value: 'id',
              label: 'name',
              checkStrictly: true
            }" 
            v-model="value"
            @change="handleChange"
            clearable style="width:100%"></el-cascader>
        </el-form-item>
        <el-form-item label="分类名称" prop="name">
          <el-input placeholder="请输入分类名称" maxlength="100" clearable show-word-limit v-model="form.name"
          ></el-input>
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
import { articleTypeIndex,articleTypeStatus,articleTypeUpdate,articleTypeStore,articleTypeEdit,articleTypeDestroy,articleTypeSorts,articleTypePidArr } from '@/api/blog/articleType.js'
export default {
  name: 'ArticleTypeIndex',
  data() {
    var checkSort = (articleType, value, callback) => {
      // 定义正则表达式
      const regSort = /^[1-9]{1}[0-9]{0,10}$/;
      if (regSort.test(value)) {
        return callback();
      }
      callback(new Error("请输入排序（大于0的数字）!"));
    };
    return {
      statusList:['禁用','启用'],
      icon: '',
      options: {
          FontAwesome: true,
          ElementUI: true,
          eIcon: true,//自带的图标，来自阿里妈妈
          eIconSymbol: true,//是否开启彩色图标
          addIconList: [],
          removeIconList: []
      },
      list: [],
      listLoading: true,
      dialogVisible:false,
      value: [], // 权限选项数据选择的数据
      form: {
        id: "",
        pid: 0,
        name: '',
        status: 1,
        level:1,
        sort:1,
      },
      articleTypes: {
        name: [
          { required: true, message: "请输入权限名称！", trigger: "blur" }
        ],
        sort: [
          { required: true, message: "请输入排序！", trigger: "blur" },
          { validator: checkSort, trigger: "blur" }
        ]
      },
      title:'',
      listQuery: {
        status:null,
        name:"",
        created_at:[],
        updated_at:[]
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
      articleTypeIndex(this.listQuery).then(response => {
        if(response.status === 20000){
          this.list = response.data
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
          status:null,
          name:"",
          created_at:[],
          updated_at:[]
        }
        this.getList()
    },
    // 监听添加编辑对话框的关闭事件
    dialogClose() {
      this.value = []
      this.form = {
        id: "",
        pid: 0,
        name: '',
        status: 1,
        level:1,
        sort:1,
      }
    },
    // 添加编辑获取选择器值
    handleChange() {
      if (this.value.length > 0) {
        this.form.pid = this.value[this.value.length - 1];
        this.form.level = this.value.length + 1;
      } else {
        this.form.pid = 0;
        this.form.level = 1;
      }
    },
    add(){
      this.dialogVisible = true
      this.title = '添加'
    },
    // 添加编辑按钮
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
          if(this.form.id){
            articleTypeUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }else{
            articleTypeStore(this.form).then(response => {
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
    // 打开编辑按钮对话框
    edit(id) {
      articleTypeEdit({id:id}).then(response => {
        if(response.status === 20000){
          console.log(response)
          this.title = '编辑'
          this.dialogVisible = true
          this.form = response.data
          this.value = response.data.value
        }
      })
    },
    // 状态调整
    setStatus(info) {
      articleTypeStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    // 删除
    articleTypeDestroy(id) {
      this.$base.confirm(
        { content: "确定要删除该项吗（会同时删除子级）！" },
        () => {
          articleTypeDestroy({id:id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    setSorts(info){
      articleTypeSorts({id:info.id,sort:info.sort}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          this.getList()
        }
      })
    },
    articleTypePidArr(pid){
      articleTypePidArr({pid:pid}).then(response => {
        if(response.status === 20000){
          this.value = response.data
          this.form.pid = pid
          this.dialogVisible = true
          this.title = '添加'
        }
      })
    }
  }
}
</script>