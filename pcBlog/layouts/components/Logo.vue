<template>
  <div class="sidebar-logo-container" :class="{'collapse':collapse}">
    <transition name="sidebarLogoFade">
      <nuxt-link v-if="collapse" key="collapse" class="sidebar-logo-link" to="/">
        <img src="http://www.hengcool.com/upload/images/common/logo.png" class="sidebar-logo">
        <h1 class="sidebar-title">hengcool博客</h1>
      </nuxt-link>
      <nuxt-link v-else key="expand" class="sidebar-logo-link" to="/">
        <img src="http://www.hengcool.com/upload/images/common/logo.png" class="sidebar-logo">
        <h1 class="sidebar-title">hengcool博客</h1>
      </nuxt-link>
    </transition>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Logo',
  props: {
    collapse: {
      type: Boolean,
      required: true
    }
  },
//   computed: {
//     ...mapGetters([
//       'mainInfo'
//     ])
//   },
  data() {
    return {
      // title: '管理后台',
      // logo: 'favicon.ico'
    }
  }
}
</script>

<style lang="scss" scoped>
.sidebarLogoFade-enter-active {
  transition: opacity 1.5s;
}

.sidebarLogoFade-enter,
.sidebarLogoFade-leave-to {
  opacity: 0;
}

.sidebar-logo-container {
  position: relative;
  width: 100%;
  height: 60px;
  line-height: 60px;
  background: #ffffff;
  text-align: center;
  overflow: hidden;

  & .sidebar-logo-link {
    height: 100%;
    width: 100%;

    & .sidebar-logo {
      width: 32px;
      height: 32px;
      vertical-align: middle;
      margin-right: 12px;
    }

    & .sidebar-title {
      display: inline-block;
      margin: 0;
      color: #666666;
      font-weight: 700;
      line-height: 50px;
      font-size: 22px;
      font-family: Avenir, Helvetica Neue, Arial, Helvetica, sans-serif;
      vertical-align: middle;
    }
  }

  &.collapse {
    .sidebar-logo {
      margin-right: 0px;
    }
  }
  a:-webkit-any-link {
    text-decoration: none;
  }
}
</style>
