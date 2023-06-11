<template>
  <div class="app-container">
    <div class="filter-container">
        <el-button v-waves class="filter-item" type="primary" icon="el-icon-edit" @click="add()">
          添加
        </el-button>
        <el-button v-waves :loading="importloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="areaImportData">
          导入地址数据
        </el-button>
        <el-button v-waves :loading="areaSetAreaDataLoading" class="filter-item" type="primary" icon="fa fa-stack-exchange" @click="areaSetAreaData">
          写入地区缓存
        </el-button>
    </div>
    <el-table
    v-loading="listLoading" :data="list"
    style="width: 100%"
    row-key="name"
    border
    lazy
    :load="load"
    :tree-props="{children: 'children', hasChildren: 'hasChildren'}">  

      <el-table-column width="200px" label="名称">
        <template slot-scope="{row}">
          <span>
            {{ row.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column width="100px" label="简称">
        <template slot-scope="{row}">
          <span>{{ row.short_name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="100px" label="区号">
        <template slot-scope="{row}">
          <span>{{ row.city_code }}</span>
        </template>
      </el-table-column>
      <el-table-column width="100px" label="邮编">
        <template slot-scope="{row}">
          <span>{{ row.zip_code }}</span>
        </template>
      </el-table-column>
       <el-table-column width="100px" label="拼音">
        <template slot-scope="{row}">
          <span>{{ row.pinyin }}</span>
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
            :content="row.status===1 ? '启用' : '禁用'"
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
      <el-table-column label="操作" align="center" width="230px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-waves type="success" size="mini" @click="areaPidArr(row)">
            添加下级
          </el-button>
          <el-button v-waves type="primary" size="mini" @click="edit(row)">
            编辑
          </el-button>
          <el-button v-waves size="mini" type="danger" @click="areaDestroy(row.id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table> 
    <!-- 添加编辑对话框 -->
    <el-dialog   :title="title" :visible.sync="dialogVisible" width="80%" @close="dialogClose">
      <el-form label-width="100px" :model="form" :rules="rules" ref="ref">
        <el-form-item label="上级">
          <el-input disabled v-model="form.pid_name"
          ></el-input>
        </el-form-item>
        <el-row :gutter="24">
          <el-col :span="12">
            <el-form-item label="名称" prop="name">
              <el-input placeholder="请输入名称" maxlength="30" clearable show-word-limit v-model="form.name"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="简称">
              <el-input placeholder="请输入简称" maxlength="30" clearable show-word-limit v-model="form.short_name"></el-input>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="24">
          <el-col :span="12">
            <el-form-item label="拼音">
              <el-input placeholder="请输入拼音" maxlength="50" clearable show-word-limit v-model="form.pinyin"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="区号">
              <el-input placeholder="请输入区号" maxlength="20" clearable show-word-limit v-model="form.city_code"></el-input>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="24">
          <el-col :span="12">
            <el-form-item label="经度">
              <el-input placeholder="请输入经度" maxlength="20" clearable show-word-limit v-model="form.lng"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="纬度">
              <el-input placeholder="请输入纬度" maxlength="20" clearable show-word-limit v-model="form.lat"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="24">
          <el-col :span="12">
            <el-form-item label="邮编">
              <el-input placeholder="请输入邮编" maxlength="20" clearable show-word-limit v-model="form.zip_code"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="显示状态">
              <el-radio-group v-model="form.status" size="medium">
                <el-radio border :label="1">启用</el-radio>
                <el-radio border :label="0">禁用</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="排序" prop="sort">
          <el-input type="number" placeholder="请输入排序" maxlength="10" clearable show-word-limit v-model="form.sort"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="dialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="primary()">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { areaIndex,areaStatus,areaUpdate,areaStore,areaEdit,areaDestroy,areaSorts,areaImportData,areaSetAreaData } from '@/api/admin/area.js'
export default {
  name: 'areaIndex',
  data() {
    var checkSort = (area, value, callback) => {
      // 定义正则表达式
      const regSort = /^[1-9]{1}[0-9]{0,10}$/;
      if (regSort.test(value)) {
        return callback();
      }
      callback(new Error("请输入排序（大于0的数字）!"));
    };
    return {
      importloadLoading:false,
      list: [],
      listLoading: true,
      dialogVisible:false,
      areaSetAreaDataLoading:false,
      value: [], // 权限选项数据选择的数据
      form: {
        id: "",
        pid_name:'',
        pid: 0,
        name: '',
        short_name: '',
        level_type: '',
        city_code: null,
        zip_code: null,
        lng: '',
        lat: '',
        pinyin:'',
        status:1,
        sort:1,
      },
      rules: {
        name: [
          { required: true, message: "请输入名称！", trigger: "blur" }
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
      areaIndex({pid:0}).then(response => {
        if(response.status === 20000){
          this.list = response.data
        }
        this.listLoading = false
      })
    },
    load(tree, treeNode, resolve) {
      console.log(tree, treeNode, resolve)
      areaIndex({pid:tree.id}).then(response => {
        if(response.status === 20000){
          resolve(response.data)
        }
      })
    },
    // 监听添加编辑对话框的关闭事件
    dialogClose() {
      this.value = []
      this.form = {
        id: "",
        pid_name:'',
        pid: 0,
        name: '',
        short_name: '',
        level_type: '',
        city_code: null,
        zip_code: null,
        lng: '',
        lat: '',
        pinyin:'',
        status:1,
        sort:1,
      }
    },
    add(){
      this.dialogVisible = true
      this.title = '添加'
      this.form.pid = 0
      this.form.pid_name = '默认添加省份'
      this.form.level_type = 1
    },
    areaPidArr(data){
      this.dialogVisible = true
      this.title = '添加'
      this.form.pid = data.id
      this.form.pid_name = data.name
      this.form.level_type = data.level_type + 1
    },
    // 添加编辑按钮
    primary() {
      this.$refs.ref.validate(valid => {
        if (valid) {
          if(this.form.id){
            areaUpdate(this.form).then(response => {
              if(response.status === 20000){
                this.$base.message({ message: response.message })
                this.dialogVisible = false
                this.getList()
              }
            })
          }else{
            areaStore(this.form).then(response => {
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
    edit(info) {
      areaEdit({id:info.id}).then(response => {
        if(response.status === 20000){
          this.title = '编辑'
          this.dialogVisible = true
          this.form = response.data
          if(info.pid === 0){
            this.form.pid_name = '默认添加省份'
          }else{
             this.form.pid_name = response.data.pid_name
          }
        }
      })
    },
    // 状态调整
    setStatus(info) {
      areaStatus({id:info.id,status:info.status}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          info.status = info.status?0:1
        }
      })
    },
    // 删除
    areaDestroy(id) {
      this.$base.confirm(
        { content: "确定要删除该项地区吗（会同时删除子级）！" },
        () => {
          areaDestroy({id:id}).then(response => {
            if(response.status === 20000){
              this.$base.message({ message: response.message })
              this.getList()
            }
          })
        }
      )
    },
    setSorts(info){
      areaSorts({id:info.id,sort:info.sort}).then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }else{
          this.getList()
        }
      })
    },
    areaImportData() {
      this.importloadLoading = true
      areaImportData().then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
          this.getList()
        }
        this.importloadLoading = false
      })
    },
    // 写入地区缓存
    areaSetAreaData() {
      this.areaSetAreaDataLoading = true
      areaSetAreaData().then(response => {
        if(response.status === 20000){
          this.$base.message({message:response.message})
        }
        this.areaSetAreaDataLoading = false
      })
    }
  }
}
</script>