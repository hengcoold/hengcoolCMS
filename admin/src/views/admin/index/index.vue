<template>
  <div>
    <panel-group/>
  </div>
</template>
<script>
import PanelGroup from './components/PanelGroup'
import {resetRouter} from '@/router'
export default { 
  name: 'Index',
  components: {PanelGroup},
  data(){
    return { 
    }
  },
  created() {
    resetRouter()
  },
  methods: {
   
  }
}
</script>