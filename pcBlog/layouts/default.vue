<template>
    <div class="container">
        <div class="container-header">
            <el-row :gutter="20">
                <el-col :span="5">
                    <logo v-if="showLogo" :collapse="true"/>
                </el-col>
                <el-col :span="14">
                    <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
                        <el-menu-item index="0">首页</el-menu-item>
                        <el-menu-item v-for="(item,index) in typeList" :key="index" :index="item.id+''">{{item.name}}</el-menu-item>
                    </el-menu>
                </el-col>
                <el-col :span="5">
                    <header-rigth/>
                </el-col>
            </el-row>
        </div>
        <div class="type-tag" v-if="items.length">
            <el-row :gutter="20">
                <el-col :span="14" :offset="5">
                    <div class="tag-list">
                        <el-tag class="tag-item" @click="typeItem(0)" :effect="type_id == 0 ? 'dark': 'light'">推荐</el-tag> 
                        <el-tag
                        class="tag-item"
                        v-for="item in items"
                        :key="item.id"
                        :effect="type_id == item.id ? 'dark': 'light'" @click="typeItem(item.id)">
                        {{ item.name }}
                        </el-tag> 
                    </div>
                </el-col>
            </el-row>
        </div>
        <div :style="{ height: items.length?'105px':'65px'}"></div>
        <el-tooltip placement="top" content="回到顶部">
            <back-to-top :visibility-height="300" :back-position="50" transition-name="fade" /> 
        </el-tooltip>
        <nuxt />
    </div>
</template>
<script>
import {mapState} from 'vuex'
import Logo from './components/Logo'
import HeaderRigth from './components/HeaderRigth'
import BackToTop from '@/components/BackToTop'
export default {
    components: { Logo ,HeaderRigth,BackToTop},
    // 计算属性
    computed:{
        ...mapState(['typeList'])
    },
    // middleware:'auth' 
    async middleware({$axios,store}){
        if(!store.getters.getTypeList.length){
            const res = await $axios({url:'/index/typeList'})
            store.dispatch('UPDATE_TYPE_LIST',res.data)
        }
    },
    data() {
      return {
        activeIndex: '0',
        showLogo:true,
        items: [],
        type_id:0
      }
    },
    watch:{
        $route:{
            immediate:true,
            handler(route){
                if(route.query.id){
                    this.activeIndex = route.query.id
                    this.type_id =  route.query.type_id
                    this.items = this.typeList.filter(item=>{
                        return item.id == route.query.id
                    })[0].children
                    if(!this.items){
                        this.items = []
                    }
                }else{
                    this.activeIndex = '0'
                    this.items = []

                }
            }
        }
    },
    methods: {
      handleSelect(key, keyPath) {
        if(key == this.activeIndex){
            return
        }
        if(key === '0'){
            this.$router.push('/')
        }else{
            this.items = []
            this.$router.push('/article?id='+key + '&type_id=0')
        }
      },
      typeItem(type_id){
          this.$router.push('/article?id='+this.$route.query.id + '&type_id='+type_id)
      }
    }
}
</script>
<style lang="scss" scoped>
    .el-menu.el-menu--horizontal{
        border-bottom: none;
    }
    .container-header{
        border-bottom: solid 1px #e6e6e6;
        position:fixed;
        background-color: #ffffff;
        width: 100%;
        top:0px;
        z-index:9999;
    }
    .type-tag{
        border-bottom: solid 1px #e6e6e6;
        position:fixed;
        background-color: #ffffff;
        width: 100%;
        top:61px;
        height: 45px;
        z-index:9999;
    }
    .tag-list{
        display: felx;
        flex-direction: row;
        line-height: 45px;
        .tag-item{
            margin:0px 10px;
            cursor:pointer;
        }
    }
</style>
