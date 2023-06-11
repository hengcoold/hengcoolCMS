<template>
    <el-carousel :interval="5000" arrow="always">
        <el-carousel-item v-for="(item,index) in bannerList" :key="index">
            <a :href="item.url" target="_blank">
                <el-image :src="item.image_url"></el-image>
            </a>
        </el-carousel-item>
    </el-carousel>
</template>

<script>
export default {
  name: 'BannerList',
  props: {
    // 文章列表数据
    bannerList:{
        type: Array,
        default: function() {
            return []
        }
    }
  },
  data() {
    return {
    }
  }
   
}
</script>
