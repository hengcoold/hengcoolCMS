<template>
  <div class="app-container">
    <div class="filter-container">
        <el-button v-waves class="filter-item" type="primary" icon="el-icon-edit" @click="add()">
          添加模块
        </el-button>
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%" row-key="name">
      <el-table-column width="200px" label="权限组名称">
        <template slot-scope="{row}">
          <span>
            <span v-if="row.icon">
              <i :class="'e-icon ' + row.icon"></i>
            </span>
            {{ row.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column width="100px" label="标识">
        <template slot-scope="{row}">
          <span>{{ row.path }}</span>
        </template>
      </el-table-column>
      <el-table-column width="100px" label="路由文件">
        <template slot-scope="{row}">
          <span>{{ row.url }}</span>
        </template>
      </el-table-column>
      <el-table-column width="100px" label="重定向路径">
        <template slot-scope="{row}">
          <span>{{ row.redirect }}</span>
        </template>
      </el-table-column>
      <el-table-column width="80px" align="center" label="菜单类型">
        <template slot-scope="{row}">
          <span>{{ typeList[row.type-1] }}</span>
        </template>
      </el-table-column>
      <el-table-column label="排序" align="center" prop="sort" width="80" sortable>
        <template slot-scope="{row}">
          <el-input v-model="row.sort" @blur="setSorts(row)"></el-input>
        </template>
      </el-table-column>
      <el-table-column align="center" label="状态" prop="status" width="80px">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="row.status===1 ? '显示' : '隐藏'"
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
      <el-table-column align="center" label="验证权限" prop="auth_open" width="80px">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="row.auth_open===1 ? '是' : '否'"
            placement="top"
            :enterable="false">
            <el-switch
              v-model="row.auth_open"
              active-color="#5FB878"
              inactive-color="#d2d2d2"
              :active-value="1"
              :inactive-value="0"
              @change="setAuthOpen(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column align="center" label="固定面板" prop="affix" width="80px">
        <template slot-scope="{row}">
          <el-tooltip
            effect="dark"
            :content="row.affix===1 ? '是' : '否'"
            placement="top"
            :enterable="false">
            <el-switch
              v-model="row.affix"
              active-color="#5FB878"
              inactive-color="#d2d2d2"
              :active-value="1"
              :inactive-value="0"
              @change="setAffix(row)"
            ></el-switch>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="230px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="success" size="mini" @click="rulePidArr(row.id)">
            添加下级
          </el-button>
          <el-button v-waves type="primary" size="mini" @click="edit(row.id)">
            编辑
          </el-button>
          <el-button v-waves size="mini" type="danger" @click="ruleDestroy(row.id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table> 
    <!-- 添加编辑对话框 -->
    <el-dialog   :title="title" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
      <el-form label-width="100px" :model="form" :rules="rules" ref="ref">
        <el-form-item label="父级">
          <el-cascader :options="list"
            placeholder="默认顶级"
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
        
        <el-form-item label="图标">
          <e-icon-picker v-model="form.icon" :options="options"/>
        </el-form-item>
        <el-form-item label="权限名称" prop="name">
          <el-input placeholder="请输入权限名称" maxlength="100" clearable show-word-limit v-model="form.name"
          ></el-input>
        </el-form-item>
        <el-form-item label="菜单类型">
          <el-radio-group v-model="form.type" size="medium">
            <el-radio border :label="1">模块</el-radio>
            <el-radio border :label="2">目录</el-radio>
            <el-radio border :label="3">菜单</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="标识">
          <el-input placeholder="请输入标识" maxlength="100" clearable show-word-limit v-model="form.path"
          ></el-input>
        </el-form-item>
        <el-form-item label="路由文件">
          <el-input placeholder="请输入路由文件" maxlength="100" clearable show-word-limit v-model="form.url"
          ></el-input>
        </el-form-item>
        <el-form-item label="重定向路径">
          <el-input placeholder="请输入重定向路径" maxlength="100" clearable show-word-limit v-model="form.redirect"
          ></el-input>
        </el-form-item>
        
        <el-form-item label="显示状态">
          <el-radio-group v-model="form.status" size="medium">
            <el-radio border :label="0">隐藏</el-radio>
            <el-radio border :label="1">显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input type="number" placeholder="请输入排序" maxlength="10" clearable show-word-limit v-model="form.sort"></el-input>
        </el-form-item>
        <el-row :gutter="20">
          <el-col :span="10">
            <el-form-item label="验证权限">
              <el-radio-group v-model="form.auth_open" size="medium">
                <el-radio border :label="0">否</el-radio>
                <el-radio border :label="1">是</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item label="固定面板">
              <el-radio-group v-model="form.affix" size="medium">
                <el-radio border :label="0">否</el-radio>
                <el-radio border :label="1">是</el-radio>
              </el-radio-group>
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
import { ruleIndex,ruleStatus,ruleOpen,ruleAffix,ruleUpdate,ruleStore,ruleEdit,ruleDestroy,ruleSorts,rulePidArr } from '@/api/admin/rule.js'
import {EIconPicker} from 'e-icon-picker' 
export default {
  name: 'RuleIndex',
  components: { EIconPicker},
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
      typeList:[
        '模块',
        '目录',
        '菜单'
      ],
      value: [], // 权限选项数据选择的数据
      form: {
        id: "",
        pid: 0,
        path: '',
        url: '',
        redirect: '',
        name: '',
        type: 1,
        status: 1,
        auth_open: 1,
        level:1,
        affix:0,
        icon:'',
        sort:1,
      },
      rules: {
        name: [
          { required: true, message: "请输入权限名称！", trigger: "blur" }
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
    // 获取表格列表
    async getList() {
      this.listLoading = true
      ruleIndex().then(response => {
        if(response.status === 20000){
          this.list = response.data
        }
        this.listLoading = false
      })
    },
    // 监听添加编辑对话框的关闭事件
    dialogClose() {
      this.value = []
      this.form = {
        id: "",
        pid: 0,
        path: '',
        url: '',
        redirect: '',
        name: '',
        type: 1,
        status: 1,
        auth_open: 1,
        level:1,
        affix:0,
        icon:'',
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
            ruleUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }else{
            ruleStore(this.form).then(response => {
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
      ruleEdit({id:id}).then(response => {
        if(response.status === 20000){
          this.title = '编辑'
          this.dialogVisible = true
          this.form = response.data
          this.value = response.data.value
        }
      })
    },
    // 状态调整
    setStatus(info) {
      ruleStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    // 是否验证权限
    setAuthOpen(info) {
      ruleOpen({id:info.id,auth_open:info.auth_open}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.auth_open = info.auth_open?0:1
        }
      })
    },
    // 是否固定面板
    setAffix(info) {
      ruleAffix({id:info.id,affix:info.affix}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.affix = info.affix?0:1
        }
      })
    },
    // 删除
    ruleDestroy(id) {
      this.$base.confirm(
        { content: "确定要删除该项菜单吗（会同时删除子菜单）！" },
        () => {
          ruleDestroy({id:id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    setSorts(info){
      ruleSorts({id:info.id,sort:info.sort}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          this.getList()
        }
      })
    },
    rulePidArr(pid){
      rulePidArr({pid:pid}).then(response => {
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