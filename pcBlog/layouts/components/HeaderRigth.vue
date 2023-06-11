<template>
    <div class="login-box">
        <img class="login-img" src="http://www.hengcool.com/upload/images/common/logo.png" alt="">
        <el-button type="primary" class="login-btn" size="mini">登录</el-button>
    </div>
</template>
<script>
export default {
  name: 'HeaderRigth',
  props: {

  },
  data() {
    return {
    }
  }
}
</script>
<style lang="scss" scoped>
    .login-box{
        display: flex;
        flex-direction: row;
        align-items:center;
        height: 60px;
        line-height: 60px;
        cursor:pointer;
        .login-img{
            width: 30px;
            height: 30px;
            border-radius:100%;
        }
        .login-btn{
            margin-left: 10px;
        }
    }


</style>
