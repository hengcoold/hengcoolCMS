<template>
  <div>
      <div ref="LogCharts" :style="{height:height,width:width}" />
  </div>
</template>
<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme
import resize from './mixins/resize'
import { getLogCountList } from '@/api/admin/auth'
export default { 
  name: 'LogCharts',
  mixins: [resize],
  props: {
    width: {
      type: String,
      default: '100%'
    },
    height: {
      type: String,
      default: '400px'
    }
  },
  data(){
    return {
        chart:null
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.initChart()
    })
  },
  beforeDestroy() {
    if (!this.chart) {
      return
    }
    this.chart.dispose()
    this.chart = null
  },
  methods: {
    initChart() {
        this.chart = echarts.init(this.$refs.LogCharts)
        // 获取表格列表
        getLogCountList().then(response => {
            if(response.status === 20000){
                let xData = []
                let yData = []
                if(response.data.length){
                    xData = response.data.map((item)=>{
                        return item.date
                    })
                    yData = response.data.map((item)=>{
                        return item.value
                    })
                }
                this.setOptions(xData,yData)
            }
        })
    },
    setOptions(xData,yData) {
        var option = {
            title: { 
                text: '接口请求报表',// 标题 支持使用 \n 换行。 
                color:'#000000', // 标题颜色
                fontWeight:700,//主标题文字字体的粗细。 
                fontSize:18,//主标题文字的字体大小。
                lineHeight:56,//行高
                left:'10%',
                subtext:'能够快速查看后台的操作情况',//副标题文本，支持使用 \n 换行。 
                subtextStyle:{
                    color:'#999999', // 标题颜色
                    fontSize:12,//主标题文字的字体大小。
                    lineHeight:20,//行高
                    fontWeight:200,//主标题文字字体的粗细。 
                }
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross'
                },
                formatter: "时间：{b0}<br/>访问数量：{c0}"
                
            },
            toolbox: {
                feature: {
                    magicType:{
                        type: ['line', 'bar']
                    },
                    dataView: {show: true, readOnly: false},
                    restore: {show: true},
                    saveAsImage: {show: true}
                },
                right:'15%'
            },
            xAxis: {
                type: 'category',
                data: xData,
                boundaryGap: false
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    data: yData,
                    type: 'line',
                    smooth: true,
                    color:'#5470C6',
                    areaStyle: {
                        color:'#5470C6'
                    }
                }
            ]
        };
        this.chart.setOption(option)
    }
  }
}
</script>
