<template>
<div>
   <el-card class="box-card" shadow="never"> 
    <div slot="header" class="clearfix">
        <span>Dane użytkownika</span>
    </div>
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
         <el-form-item size="large">
            <el-button type="primary" @click="submitForm('ruleForm')">Zatwierdź</el-button>    
        </el-form-item>
    </el-form>
    </el-card>

    <el-card class="box-card" style="margin-top:10px;" shadow="never"> 
    <div slot="header" class="clearfix">
        <span>Zmiana hasła</span>
    </div>
    <el-form :model="ruleForm2" :rules="rules2" ref="ruleForm2" label-width="120px" class="demo-ruleForm">
        <!-- <el-form-item label="Stare hasło:" prop="oldpass">
            <el-input v-model="ruleForm2.oldpass"></el-input>
        </el-form-item> -->
        <el-form-item label="Nowe hasło" prop="newpass">
            <el-input v-model="ruleForm2.newpass"></el-input>
        </el-form-item>
        <el-form-item label="Potwierdź hasło" prop="confirm">
            <el-input v-model="ruleForm2.confirm"></el-input>
        </el-form-item>
         <el-form-item size="large">
            <el-button type="primary" @click="submitForm2('ruleForm2')">Zatwierdź</el-button>    
        </el-form-item>
    </el-form>
    </el-card>
</div>
</template>

<script>
import Errors from '../../Errors.js';
export default {
    props: ['user'],

    data() {

        var validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('Proszę powtórzyć nowe hasło'));
        } else if (value !== this.ruleForm2.newpass) {
          callback(new Error('Wpisane hasła nie pokrywają się'));
        } else {
          callback();
        }
      };

        return {
            ruleForm: {
                firstname:this.user.firstname,
                lastname:this.user.lastname,
                email:this.user.email,
                roles:[],
            },
            ruleForm2: {
                oldpass:'',
                newpass:'',
                confirm:'',
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
                ],
                
            },
            rules2:{
                oldpass:[
                    {required:true, message:'Proszę podać aktualne hasło', trigger:'blur'},
                ],
                newpass:[
                    {required:true, message:'Proszę podać nowe hasło', trigger:'blur'},
                    { min: 8, max: 50, message: 'długość wyrażenia powinna zawierać się w przedziale od 8 do 50 znaków', trigger: 'blur' },
                ],
                confirm:[
                    { validator: validatePass2, trigger: 'blur' }
                ]


            },
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
        submitForm2(formName){ 
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.onSubmit2();
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
            }).then(response =>this.onSuccess())
                .catch(error=>this.errors.record(error.response.data.errors));
        },
        onSuccess(){
            this.$message({
                    type: 'success',
                    message: 'Dane użytkownika zaktualizowane.'
                }); 
        },

        onSubmit2(){
            axios.post('/editpass',{
                newpass: this.ruleForm2.newpass,
                confirm: this.ruleForm2.confirm,
            }).then(response =>this.onSuccess2())
                .catch(error=>this.errors.record(error.response.data.errors));
        },
        onSuccess2(){
            this.$message({
                    type: 'success',
                    message: 'Hasło użytkownika zaktualizowane.'
                }); 
        },

    }
};
</script>