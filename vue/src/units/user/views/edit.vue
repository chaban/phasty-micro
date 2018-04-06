<template>
  <div class="app-container">
    <el-form ref="form" :model="formData" :rules="rules" label-width="120px">
      <el-form-item label="Name" prop="name">
        <el-input v-model="formData.name"></el-input>
      </el-form-item>
      
      <el-form-item label="Email" prop="email">
        <el-input v-model="formData.email"></el-input>
      </el-form-item>

      <el-form-item label="Role" >
        <el-select v-model="formData.role" placeholder="Select">
          <el-option
            v-for="item in roles"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>

      <div>
      <el-form-item label="Profile"></el-form-item>
      <el-form-item v-for="(item, index) in formData.profile_fields" :key="index">
    <el-col :span="7">
      <span>{{item}}</span>
    </el-col>
    <el-col class="line" :span="1">-</el-col>
    <el-col :span="14">
      <el-input placeholder="Value" v-model="formData.profile[item]"></el-input>
    </el-col>
  </el-form-item>
  
  <div class="clearfix"></div>
  </div>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')" :loading="dataLoading">Update</el-button>
        <el-button @click="onCancel">Go to list</el-button>
      </el-form-item>
    </el-form>
    <pre>{{formData}}</pre>
  </div>
</template>

<script>
/* eslint-disable */
import { updateItem, getItem, rules, roles } from "@/units/user/service";
export default {
  data() {
    return {
      dataLoading: false,
      formData: {
        name: "",
        email: "",
        role: "",
        profile: [],
        profile_fields: []
      },
      roles: roles,
      rules: rules
    };
  },
  created() {
    this.dataLoading = true;
    getItem(parseInt(this.$route.params.id)).then(response => {
      this.formData = response.data;
      this.formData.profile = JSON.parse(response.data.profile)
      this.dataLoading = false;
    });
  },
  methods: {
    saveData() {
      this.dataLoading = true;
      updateItem(this.formData.id, this.formData)
        .then(response => {
          this.formData = response.data;
          this.formData.profile = JSON.parse(response.data.profile)
          this.$message({
            type: "success",
            message: "Updated"
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Not updated",
            duration: 1 * 1000
          });
        });
        this.dataLoading = false;
    },
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.saveData();
        } else {
          return false;
        }
      });
    },
    onCancel() {
      this.$router.push("/users/index");
    }
  }
};
</script>

<style scoped>
.line {
  text-align: center;
}
</style>

