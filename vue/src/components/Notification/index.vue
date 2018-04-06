<template>
 <div class="notification-item">
    <el-dropdown trigger="click">
  <span class="el-dropdown-link"><span>
      <el-badge :value="12" class="item"></el-badge></span>
      <el-button size="small">Messages</el-button>
    <i class="el-icon-caret-bottom el-icon--right"></i>
  </span>
  <el-dropdown-menu slot="dropdown">
    <el-dropdown-item class="clearfix">
      comments
      <el-badge class="mark" :value="12" />
    </el-dropdown-item>
    <el-dropdown-item class="clearfix">
      replies
      <el-badge class="mark" :value="3" />
    </el-dropdown-item>
  </el-dropdown-menu>
</el-dropdown>
  </div>
</template>

<script>
/* eslint-disable */
import { CentManager, conf } from './../../plugins/Centfifugo'
export default {
  created() {
    const cent = new CentManager(conf())
     cent.subscribe('test', message => console.log(message))
     cent.connect()
  },
  data() {
    return {
      levelList: null
    }
  },
  methods: {}
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.notification-item {
  display: inline-block;
  position: absolute;
  margin-left: 10px;
  right: 110px;
  height: 50px;
  .el-badge{
    float: left;
  }
}
</style>
