export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'pc',
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

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/assets/css/customBootStrap.scss', // 全局bootstrap样式
    '@/assets/css/animate.min.css',
    'aos/dist/aos.css',
    '@/assets/css/base.scss'
  ],
  bootstrapVue: {
    // 引入icon
    icons: true,
    // 自定义样式,以下两属性设置false
    bootstrapCSS: false,
    bootstrapVueCSS: false
  },
  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    // axios  拦截器
    {
      src: '~/plugins/axios',
      ssr: true //  服务端渲染
    },
    { src: '@/plugins/wow.js', ssr: false },
    { src: '@/plugins/aos.js', ssr: false },
    { src: '@/plugins/waypoint.js', ssr: false }
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    '@nuxtjs/eslint-module'
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxtjs/axios',
    'bootstrap-vue/nuxt'
  ],
  axios: {
    proxy: true, //  开启跨域行为
    prefix: '/api/v1/blogApi' // 配置基本的url地址
  },
  proxy: {
    '/api': {
      target: 'http://www.hengcool.com', //  代理转发的地址
      pathRewrite: {
        // '^/api':''                // 找到地址中的api并替换成空
      }
    }
  },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {}
};
