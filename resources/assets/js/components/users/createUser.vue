<template>
  <el-card class="box-card">
    <el-form
      :model="ruleForm"
      :rules="rules"
      ref="ruleForm"
      label-width="120px"
      class="demo-ruleForm"
    >
      <el-form-item label="Imię:" prop="firstname">
        <el-input v-model="ruleForm.firstname"></el-input>
      </el-form-item>
      <el-form-item label="Nazwisko:" prop="lastname">
        <el-input v-model="ruleForm.lastname"></el-input>
      </el-form-item>
      <el-form-item label="e-mail:" prop="email">
        <el-input v-model="ruleForm.email"></el-input>
      </el-form-item>
      <el-form-item label="Uprawnienia:" prop="roles">
        <el-checkbox-group v-model="ruleForm.roles" v-for="role in roleslist" v-bind:key="role.id">
          <el-checkbox :label="role.name" name="roles"></el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item size="large">
        <el-button type="primary" @click="submitForm('ruleForm')">Utwórz</el-button>
        <el-button @click="resetForm('ruleForm')">Wyczyść</el-button>
      </el-form-item>
    </el-form>
  </el-card>
</template>

<script>
import Errors from "../../Errors.js";
export default {
  props: ["roles"],

  data() {
    return {
      ruleForm: {
        firstname: "",
        lastname: "",
        email: "",
        roles: []
      },
      rules: {
        firstname: [
          { required: true, message: "Proszę wpisać imię", trigger: "blur" },
          {
            min: 2,
            max: 50,
            message:
              "długość wyrazu powinna zawierać się w przedziale od 2 do 50 znaków",
            trigger: "blur"
          }
        ],
        lastname: [
          {
            required: true,
            message: "Proszę wpisać nazwisko",
            trigger: "blur"
          },
          {
            min: 2,
            max: 50,
            message:
              "długość wyrazu powinna zawierać się w przedziale od 2 do 50 znaków",
            trigger: "blur"
          }
        ],
        email: [
          {
            required: true,
            message: "Proszę wpisać adres email",
            trigger: "blur"
          },
          {
            type: "email",
            message: "Proszę wpisać prawidłowy format adresu email",
            trigger: "blur"
          }
        ],
        roles: [
          {
            type: "array",
            required: true,
            message: "Proszę zaznaczyć przynajmniej jeden rodzaj uprawnień",
            trigger: "blur"
          }
        ]
      },
      roleslist: this.roles,
      errors: new Errors()
    };
  },

  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.onSubmit();
        } else {
          this.$message({
            type: "error",
            message: "Proszę o poprawne wypełnienie formularza."
          });
          return false;
        }
      });
    },

    onSubmit() {
      axios
        .post("/createuser", {
          firstname: this.ruleForm.firstname,
          lastname: this.ruleForm.lastname,
          email: this.ruleForm.email,
          role: this.ruleForm.roles
        })
        .then(response => this.onSuccess())
        .catch(error => this.errors.record(error.response.data.errors));
    },
    onSuccess() {
      this.ruleForm.firstname = "";
      this.ruleForm.lastname = "";
      this.ruleForm.email = "";
      this.ruleForm.roles = [];
      EventBus.$emit("refreshlist");
      this.$message({
        type: "success",
        message: "Użytkownik utworzony."
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    }
  }
};
</script>