<template>
    <div class="container">
        <el-row :gutter="20">
            <el-col :span="12" :offset="5">
                <article-list :articleList="articleList" :page="page" :limit="limit" :noMore="noMore" :loading="loading" :id="id" :typeId="type_id"></article-list>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import ArticleList from '@/components/ArticleList'
export default {
    components: {ArticleList},
    data() {
        return {
        }
    }, 
    async asyncData({$axios,query}){
        let articleList = []
        let page = 1
        let limit = 10
        let noMore = false
        let loading = false
        // ?page=1&limit=2&id=1&type_id=0
        await $axios({url:'/index/articleList',params:{page:page,limit:limit,id:query.id,type_id:query.type_id}}).then(res=>{
            if(res.status === 20000){
                if(Math.ceil(res.data.total/limit) <= page){
                    noMore = true
                }
                articleList = res.data.list
            }
        })
        return {  
            articleList:articleList,
            page:page,
            limit:limit,
            noMore:noMore,
            loading:loading,
            id:query.id,
            type_id:query.type_id
        }
    },
    watch:{
        $route:{
            immediate:true,
            handler(route){
                if(this.id === route.query.id && this.type_id === route.query.type_id){
                    return
                }
                let articleList = []
                let page = 1
                let limit = 10
                let noMore = false
                let loading = false
                this.$axios({url:'/index/articleList',params:{page:page,limit:limit,id: route.query.id,type_id:route.query.type_id}}).then(res=>{
                    if(res.status === 20000){
                        if(Math.ceil(res.data.total/limit) <= page){
                            noMore = true
                        }
                        this.articleList = res.data.list
                        this.page = page
                        this.limit = limit
                        this.noMore = noMore
                        this.loading = loading
                        this.id = route.query.id
                        this.type_id = route.query.type_id
                    }
                })
                
            }
        }
    },

}    
</script>    