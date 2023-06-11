<template>
    <div class="whitebg paihang">
        <h2 class="htitle">推荐文章</h2>
        <ul v-for="(item,index) in openArticleList" :Key="index">
            <li>
                <span :class="index<3?'action':''">{{index+1}}</span>
                <a href="">{{item.title}}</a>
            </li>    
        </ul>
    </div>
</template>

<script>
export default {
  name: 'OpenArticleList',
  props: {
    // 文章列表数据
    openArticleList:{
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

<style lang="scss" scoped>
.whitebg { 
    background: #fff; 
    border-radius: 3px; 
    padding: 6px 20px; 
    overflow: hidden;
    display: flex;
    flex-direction: column;
    .htitle { 
        font-size: 16px; 
        line-height: 40px; 
        border-bottom: 1px solid #eee; 
        color: #484848; 
        font-weight: normal; 
        position: relative; 
        margin-bottom: 10px; 
        
    }
    .htitle:after { 
        content: ""; 
        position: absolute; 
        width: 60px; 
        height: 2px; 
        content: ""; 
        background: #000; 
        left: 0; 
        bottom: 0; 
        -moz-transition: all .5s ease; 
        -webkit-transition: all .5s ease; 
        transition: all .5s ease; 
    }
    .htitle:hover:after { 
        width: 80px; 
    }
    ul{
        overflow: hidden;
        li{
            height: 30px; 
            line-height: 30px;
            margin-bottom: 10px;
            text-overflow: ellipsis; 
            white-space: nowrap; 
            overflow: hidden; 
            span{
                padding: 0 4px;
                background: #B1B1B1;
                color: #fff;
                font-size: 9px;
            }
            a{
                font-size: 14px;
                color: #333;
            }
            .action{
                background: #FF6600;
            }
            a:hover { color: #999999 }
        }
        
    }
}
</style>
