<template>
    <div class="container">
        <el-row :gutter="20" class="banner-box">
            <el-col :span="12" :offset="5">
                <banner-list :bannerList="bannerList"></banner-list>
            </el-col>
            <el-col :span="4" class="padding-left-10">
                <open-article-list :openArticleList="openArticleList"></open-article-list>    
            </el-col>
        </el-row>    
        <el-row :gutter="20">
            <el-col :span="12" :offset="5">
                <article-list :articleList="articleList" :page="page" :limit="limit" :noMore="noMore" :loading="loading"></article-list>
            </el-col>
        </el-row>
    </div>
</template>

<script>
import {mapActions,mapGetters,mapState,mapMutations} from 'vuex'
import ArticleList from '@/components/ArticleList'
import OpenArticleList from '@/components/OpenArticleList'
import BannerList from '@/components/BannerList'
export default {
    components: {ArticleList,OpenArticleList,BannerList},
    data() {
        return {
        }
    }, 
    // middleware:'auth' 
    // middleware(){
    //      console.log('middleware index')
    // },
    // validate(context){
    //     console.log('validate')
    //     return true
    // },
    // 
    async asyncData({$axios}){
        let bannerList = []
        await $axios({url:'/index/bannerList',params:{type:0}}).then(res=>{
            if(res.status === 20000){
                bannerList = res.data
            }
        })
        let articleList = []
        let page = 1
        let limit = 10
        let noMore = false
        let loading = false
        // ?page=1&limit=2&id=1&type_id=0
        await $axios({url:'/index/articleList',params:{page:page,limit:limit}}).then(res=>{
            if(res.status === 20000){
                if(Math.ceil(res.data.total/limit) <= page){
                    noMore = true
                }
                articleList = res.data.list
            }
        })
        let openArticleList = []
        await $axios({url:'/index/openArticleList'}).then(res=>{
            if(res.status === 20000){
                openArticleList = res.data
            }
        })
        return {  
            bannerList:bannerList,
            articleList:articleList,
            page:page,
            limit:limit,
            noMore:noMore,
            loading:loading,
            openArticleList:openArticleList
        }
    },
    // fetch(context){
    //     console.log('fetch')
    // },
    methods:{
        // btn(){
             // 调用actions   异步请求
            // this.$store.dispatch('UPDATE_TYPE_LIST',this.typeList)
            // 调用mutations 
            // this.$store.commit('SET_TYPE_LIST',this.typeList)
            // this.SET_TYPE_LIST(this.typeList)
        // },
        // ...mapActions(['UPDATE_TYPE_LIST']),
        // ...mapMutations(['SET_TYPE_LIST'])
    },
    mounted(){
       window.addEventListener('scroll', this.onScroll)
    },
    // 计算属性
    computed:{
        // ...mapGetters(['getTypeList']),
        // ...mapState(['typeList '])
    },
    // beforeCreated(){
    //    console.log('beforeCreated')
    // },
    
    // // 组件加载前 
    // beforeMount(){
    //     console.log('beforeMount')
    // },
    // // 组件加载完毕 
    // mounted(){
    //     console.log('mounted')
    // },
    // // 组件更新前 
    // beforeUpdate(){
    //     console.log('beforeUpdate')
    // },
    // // 组件更新完毕
    // updated(){
    //     console.log('updated')
    // },
    // // 组件卸载前 
    // beforeDestroy(){
    //     console.log('beforeDestroy')
    // },
    // // 组件卸载后 
    // destroyed(){
    //     console.log('destroyed')
    // }
}
</script>