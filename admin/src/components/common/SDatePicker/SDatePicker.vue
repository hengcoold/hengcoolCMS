<template>
  <div style="display:inline-block">
      <el-date-picker v-model="innerDate" value-format="yyyy-MM-dd HH:mm:ss" type="datetimerange" :range-separator="rangeSeparator" :start-placeholder="startPlaceholder" :end-placeholder="endPlaceholder" @change="changeTime" :picker-options="pickerOptions" clearable></el-date-picker>
  </div>
</template>

<script>
export default {
  mixins: "SDatePicker",
  props: {
    date: {
      type: Array,
      default: function() {
        return []
      }
    },
    rangeSeparator:{
      type: String,
      default: "至"
    },
    startPlaceholder:{
      type: String,
      default: "开始创建时间"
    },
    endPlaceholder:{
      type: String,
      default: "结束创建时间"
    }
  },
  data() {
    return {
        innerDate:this.date,
        pickerOptions: {
          shortcuts: [{
            text: '最近一周',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近六个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 180);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近一年',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 360);
              picker.$emit('pick', [start, end]);
            }
          }]
        }
    }
  }, 
  watch: {
    date (value) {
        this.innerDate = value;
    }
  },
  methods: {
    changeTime(val){
        this.$emit('changeDateTime', val)
    }
  }
}
</script>
