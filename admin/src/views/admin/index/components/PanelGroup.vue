<template>
  <el-row :gutter="40" class="panel-group">
    <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col" v-for="(item,index) in modelArr" :key="index" >
      <div class="card-panel" @click="mainClick(item)">
        <div class="card-panel-icon-wrapper icon-people">
          <i :class="'card-panel-icon e-icon ' + item.icon"></i>
        </div>
        <div class="card-panel-description">
          <div class="card-panel-text">
            {{item.name}}
          </div>
        </div>
      </div>
    </el-col>
  </el-row>  
</template>
<script>
import { mapGetters } from 'vuex'
export default { 
  name: 'PanelGroup',
  data(){
    return {
    }
  },
  computed: {
    ...mapGetters([
      'modelArr'
    ])
  },
  created() {
    this.$store.dispatch('permission/changeRoles')
  },
  methods: {
    async mainClick(item){
      await this.$store.dispatch('permission/changeModelId',item.id)
      await this.$store.dispatch('permission/changeModelName',item.name)
      this.$router.push(item.path)  
    }
  }
}
</script>
<style lang="scss" scoped>
.panel-group {
  padding: 18px;
  box-sizing: border-box;
  .card-panel-col {
    margin-bottom: 32px;
  }
  .card-panel {
    height: 108px;
    cursor: pointer;
    font-size: 12px;
    position: relative;
    overflow: hidden;
    color: #666;
    background: #fff;
    box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
    border-color: rgba(0, 0, 0, .05);
    &:hover {
      .card-panel-icon-wrapper {
        color: #fff;
      }
      .icon-people {
        background: #40c9c6;
      }
      .icon-message {
        background: #36a3f7;
      }
      .icon-money {
        background: #f4516c;
      }
      .icon-shopping {
        background: #34bfa3
      }
    }
    .icon-people {
      color: #40c9c6;
    }
    .icon-message {
      color: #36a3f7;
    }
    .icon-money {
      color: #f4516c;
    }
    .icon-shopping {
      color: #34bfa3
    }
    .card-panel-icon-wrapper {
      float: left;
      margin: 14px 0 0 14px;
      padding: 16px;
      transition: all 0.38s ease-out;
      border-radius: 6px;
    }
    .card-panel-icon {
      float: left;
      font-size: 48px;
    }
    .card-panel-description {
      float: right;
      font-weight: bold;
      margin: 45px 30px;
      .card-panel-text {
        line-height: 18px;
        color: rgba(0, 0, 0, 0.45);
        font-size: 22px;
        margin-bottom: 12px;
      }

      .card-panel-num {
        font-size: 20px;
      }
    }
  }
}
@media (max-width:550px) {
  .card-panel-description {
    display: none;
  }
  .card-panel-icon-wrapper {
    float: none !important;
    width: 100%;
    height: 100%;
    margin: 0 !important;
    .svg-icon {
      display: block;
      margin: 14px auto !important;
      float: none !important;
    }
  }
}
</style>
