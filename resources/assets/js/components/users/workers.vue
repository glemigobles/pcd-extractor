<template>
  <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Lista Użytkowników" name="first">
      <userstable :users="this.userslist" :roles="this.roleslist"></userstable>
    </el-tab-pane>
    <el-tab-pane label="Dodaj Użytkownika" name="second">
      <createuser :roles="this.roleslist"></createuser>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
export default {
  props: ["users", "roles"],

  // watch: {
  //     users: function(refreshedusers) {
  //         this.users = refreshedusers;
  //     }
  // },

  data() {
    return {
      userslist: this.users,
      roleslist: this.roles,
      activeName: "first"
    };
  },
  methods: {
    handleClick(tab, event) {
      //console.log(tab, event);
    }
  }
};
</script>