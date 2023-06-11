<template>
  <div class="navbar">
    <hamburger id="hamburger-container" :is-active="sidebar.opened" class="hamburger-container" @toggleClick="toggleSideBar" />

    <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />

    <div class="right-menu">
      <template v-if="device!=='mobile'">
        <!-- <search id="header-search" class="right-menu-item" /> -->

        <!-- <error-log class="errLog-container right-menu-item hover-effect" /> -->

        <screenfull id="screenfull" class="right-menu-item hover-effect" />

        <!-- <el-tooltip :content="$t('navbar.size')" effect="dark" placement="bottom">
          <size-select id="size-select" class="right-menu-item hover-effect" />
        </el-tooltip> -->

        <!-- <lang-select class="right-menu-item hover-effect" /> -->

      </template>

      <el-dropdown class="avatar-container right-menu-item hover-effect" trigger="click">
        <div class="avatar-wrapper">
          <div>{{userInfo.username}}</div>
          <i class="el-icon-caret-bottom" />
        </div>
        <el-dropdown-menu slot="dropdown">
          
          <router-link to="/">
            <el-dropdown-item>
              首页
            </el-dropdown-item>
          </router-link>
          <el-dropdown-item divided @click.native="editAdminDialogVisible = true">
            <span style="display:block;">修改密码</span>
          </el-dropdown-item>
          <el-dropdown-item divided @click.native="outCache">
            <span style="display:block;">清楚缓存</span>
          </el-dropdown-item>
          <el-dropdown-item divided @click.native="logout">
            <span style="display:block;">退出登录</span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
    <el-dialog title="修改密码" append-to-body :visible.sync="editAdminDialogVisible" width="80%" @close="editAdminDialogClose">
      <!-- 主体区 -->
      <el-form label-width="100px" :model="editAdminForm" :rules="editAdminRules" ref="editAdminRef">
        <el-form-item label="账号">
          <el-input disabled v-model="userInfo.username"></el-input> 
        </el-form-item>
        <el-form-item label="原密码" prop="y_password">
          <el-input type="password" placeholder="请输入原密码" maxlength="14" clearable show-word-limit v-model="editAdminForm.y_password"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input type="password" placeholder="请输入密码" maxlength="14" clearable show-word-limit v-model="editAdminForm.password"></el-input>
        </el-form-item>
        <el-form-item label="确认密码" prop="password_confirmation">
          <el-input type="password" placeholder="请输入确认密码" maxlength="14" clearable show-word-limit v-model="editAdminForm.password_confirmation"></el-input>
        </el-form-item>
      </el-form>
      <!-- 按钮区 -->
      <span slot="footer" class="dialog-footer">
        <el-button v-waves @click="editAdminDialogVisible = false">取 消</el-button>
        <el-button v-waves type="primary" @click="upadtePwdView()">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Breadcrumb from '@/components/Breadcrumb'
import Hamburger from '@/components/Hamburger'
import ErrorLog from '@/components/ErrorLog'
import Screenfull from '@/components/Screenfull'
import SizeSelect from '@/components/SizeSelect'
import LangSelect from '@/components/LangSelect'
import Search from '@/components/HeaderSearch'
import { upadtePwdView } from '@/api/admin/auth'
export default {
  components: {
    Breadcrumb,
    Hamburger,
    ErrorLog,
    Screenfull,
    SizeSelect,
    LangSelect,
    Search
  },
  computed: {
    ...mapGetters([
      'sidebar',
      'userInfo',
      'device'
    ])
  },
  data(){
    var checkYpassword = (rule, value, callback) => {
      // 定义正则表达式
      const regYpassword = /^[a-zA-Z0-9]{4,14}$/;
      if (regYpassword.test(value)) {
        return callback();
      }
      callback(new Error("原密码必须4到14位的数字或字母!"));
    };
    var checkPassword = (rule, value, callback) => {
      // 定义正则表达式
      const regPassword = /^[a-zA-Z0-9]{4,14}$/;
      if (regPassword.test(value)) {
        return callback();
      }
      callback(new Error("密码必须4到14位的数字或字母!"));
    };
    var checkPasswordConfirmation = (rule, value, callback) => {
      if (value !== this.editAdminForm.password) {
        callback(new Error("两次输入密码不一致!"));
      } else {
        return callback();
      }
    };
    return{
      // 修改密码
      editAdminDialogVisible: false,
      editAdminForm: {
        // 修改密码数据
        y_password: "",
        password: "",
        password_confirmation: ""
      },
      editAdminRules: {
        y_password: [
          { required: true, message: "请输入原密码！", trigger: "blur" },
          { validator: checkYpassword, trigger: "blur" }
        ],
        password: [
          { required: true, message: "请输入密码！", trigger: "blur" },
          { validator: checkPassword, trigger: "blur" }
        ],
        password_confirmation: [
          { required: true, message: "请输入确认密码！", trigger: "blur" },
          { validator: checkPasswordConfirmation, trigger: "blur" }
        ]
      }
    }
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar')
    },
    async logout() {
      // await this.$base.confirm(
      //   { content: "确定要退出登录吗？" },
      //   () => {
      //     this.$store.dispatch('user/logout')
      //     this.$router.push(`/login?redirect=${this.$route.fullPath}`)
      //   }
      // )
      await this.$store.dispatch('user/logout')
      this.$router.push(`/login?redirect=${this.$route.fullPath}`)
    },
    async outCache() {
       this.$base.confirm(
        { content: "确定要清除缓存吗？" },
        () => {
          this.$store.dispatch('permission/outCache')
          this.$router.push(`/index`)
        }
      )
    },
    // 监听修改密码对话框的关闭事件
    editAdminDialogClose() {
      this.$refs.editAdminRef.resetFields()
    },
    // 修改密码提交
    upadtePwdView(){
      upadtePwdView(this.editAdminForm).then(response => {
        if(response.status === 20000){
          this.$base.message({ message: response.message })
          this.editAdminDialogVisible = false
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.navbar {
  height: 50px;
  overflow: hidden;
  position: relative;
  background: #3c8dbc;
  box-shadow: 0 1px 4px rgba(0,21,41,.08);

  .hamburger-container {
    line-height: 46px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: background .3s;
    -webkit-tap-highlight-color:transparent;

    &:hover {
      background: rgba(0, 0, 0, .025)
    }
  }

  .breadcrumb-container {
    float: left;
  }

  .errLog-container {
    display: inline-block;
    vertical-align: top;
  }

  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;

    &:focus {
      outline: none;
    }

    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      color: #eee;
      vertical-align: text-bottom;

      &.hover-effect {
        cursor: pointer;
        transition: background .3s;

        &:hover {
          background: rgba(0, 0, 0, .025)
        }
      }
    }

    .avatar-container {
      margin-right: 30px;

      .avatar-wrapper {
        margin-top: 5px;
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 10px;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: -20px;
          top: 25px;
          font-size: 12px;
        }
      }
    }
  }
}
</style>
