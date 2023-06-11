export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'pcBlog',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  // 全局的css样式
  css: [
    "element-ui/lib/theme-chalk/index.css",
    './assets/css/base.scss',
    './assets/iconfont/iconfont.css',
    './assets/css/page-transletion.scss'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    {
      src: "~plugins/element-ui",
      ssr:true,
    // mode:'server'//client
    },
    // axios  拦截器
    {
      src:'~/plugins/axios',
      'ssr':true     //  服务端渲染
    }
  ],
  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  // unxt引入第三方模块
  modules: [
    '@nuxtjs/axios'
  ],
  axios:{
    proxy:true,//  开启跨域行为
    prefix:'/api/v1/blogApi',  //配置基本的url地址
  },
  proxy:{
    '/api':{
    target:'http://www.hengcool.com',  //  代理转发的地址
    pathRewrite:{
          // '^/api':''                // 找到地址中的api并替换成空
      }
    }
  },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    transpile:['/^element-ui/']
  },
  router:{
    middleware:'auth'
  },
  // loading:{color:'#399',height:'6px'}
  loading:'~/components/Loading/index.vue'
}
