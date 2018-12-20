<template>
   
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px" class="demo-ruleForm">
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
            <el-checkbox-group v-model="ruleForm.roles" v-for="role in roles" v-bind:key="role.id">
                <el-checkbox :label="role.name" name="roles"></el-checkbox>  
            </el-checkbox-group>
        </el-form-item>
         <el-form-item size="large">
            <el-button type="primary" @click="submitForm('ruleForm')">Zatwierdź</el-button>    
            <!-- <el-button @click="resetForm('ruleForm')">Wyczyść</el-button> -->
            <el-button type="danger" @click="handleCloseDialog()">Anuluj</el-button>
        </el-form-item>
    </el-form>

</template>

<script>
import Errors from '../../Errors.js';
export default {
    props: ['user','roleslist'],

    watch: {
        user: function(refreshedusers) {
            this.ruleForm.firstname = this.user.firstname;
            this.ruleForm.lastname = this.user.lastname;
            this.ruleForm.email = this.user.email;
            let rolesnames=[];
            this.user.roles.forEach(element => {
                rolesnames.push(element.name);
            });
            this.ruleForm.roles=rolesnames;
        }
    },

    mounted(){
        let rolesnames=[];
        this.user.roles.forEach(element => {
            rolesnames.push(element.name);
        });
        return this.ruleForm.roles=rolesnames;
    },

    data() {
        return {
            ruleForm: {
                firstname:this.user.firstname,
                lastname:this.user.lastname,
                email:this.user.email,
                roles:[],
            },
            rules: {
                firstname: [
                    { required: true, message: 'Proszę wpisać imię', trigger: 'blur' },
                    { min: 2, max: 50, message: 'długość wyrazu powinna zawierać się w przedziale od 2 do 50 znaków', trigger: 'blur' }
                ],
                lastname: [
                    { required: true, message: 'Proszę wpisać nazwisko', trigger: 'blur' },
                    { min: 2, max: 50, message: 'długość wyrazu powinna zawierać się w przedziale od 2 do 50 znaków', trigger: 'blur' }
                ],
                email: [
                    { required: true, message: 'Proszę wpisać adres email', trigger: 'blur' },
                    { type:'email', message: 'Proszę wpisać prawidłowy format adresu email', trigger: 'blur' }
                ],
                roles:[
                    { type: 'array', required: true, message: 'Proszę zaznaczyć przynajmniej jeden rodzaj uprawnień', trigger: 'blur' },
                ]
            },
            roles: this.roleslist,
            errors:new Errors()
        };
    },



    methods: {
        submitForm(formName){ 
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.onSubmit();
          } else {
            this.$message({
                    type: 'error',
                    message: 'Proszę o poprawne wypełnienie formularza.'
                });  
            return false;
          }
        });
        },

        onSubmit(){
            axios.post('/edituser',{
                id:this.user.id,
                firstname: this.ruleForm.firstname,
                lastname: this.ruleForm.lastname,
                email: this.ruleForm.email,
                role: this.ruleForm.roles,
            }).then(response =>this.onSuccess())
                .catch(error=>this.errors.record(error.response.data.errors));
        },
        onSuccess(){
            EventBus.$emit('refreshlist');
            this.$message({
                    type: 'success',
                    message: 'Dane użytkownika zaktualizowane.'
                }); 
        },
        resetForm(formName) {
        this.$refs[formName].resetFields();
        },
        handleCloseDialog(){
            //resetForm('ruleForm')
            EventBus.$emit('closeEdit');
        } 

    }
};
</script>