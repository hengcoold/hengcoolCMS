<template>
  <div>
      <panel-group :info="info" @handleSetLineChartData="handleSetLineChartData" />
  </div>
</template>

<script>
import { dashboardIndex } from '@/api/blog/dashboard'
import PanelGroup from './components/PanelGroup'
export default {
  name: 'DashboardIndex',
  components: {
    PanelGroup
  },
  data() {
    return {
      info: {},
      list:[]
    }
  },
  async created() {
    await this.getList()
  },
  methods: {
    // 获取表格列表
    async getList() {
      dashboardIndex().then(response => {
        if(response.status === 20000){
            this.info = response.data
        }
      })
    },
    handleSetLineChartData(e){
      
    }
  }
}
</script>
