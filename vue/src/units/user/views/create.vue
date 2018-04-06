<template>
  <div class="app-container">
    <el-form ref="form" :model="formData" :rules="rules" label-width="120px">
      <el-form-item label="Name" prop="name">
        <el-input v-model="formData.name"></el-input>
      </el-form-item>
      
      <el-form-item label="Email" prop="email">
        <el-input v-model="formData.email"></el-input>
      </el-form-item>

      <el-form-item label="Password" prop="password">
        <el-input v-model="formData.password"></el-input>
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
        <el-button type="primary" :disabled="!formData.role" @click="onSubmit('form')" :loading="dataLoading">Create</el-button>
        <el-button @click="onCancel">Go to list</el-button>
      </el-form-item>
    </el-form>
    <pre>{{formData}}</pre>
  </div>
</template>

<script>
/* eslint-disable */
import { createItem, rules, roles } from "@/units/user/service";
export default {
  data() {
    return {
      dataLoading: false,
      formData: {
        name: "",
        email: "",
        password: "",
        role: "",
        profile: {},
        profile_fields: ['phone', 'address', 'skype']
      },
      roles: roles,
      rules: rules
    };
  },
  
  methods: {
    saveData() {
      this.dataLoading = true;
      createItem(this.formData)
        .then(response => {
          this.formData = response.data;
          this.formData.profile = JSON.parse(response.data.profile)
          this.$message({
            type: "success",
            message: "Created"
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Not Created",
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

