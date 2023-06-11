import { WOW } from 'wowjs'

window.WOW = WOW
new WOW({ //可以添加自定义内容
  live: false,
  offset: 0
}).init()