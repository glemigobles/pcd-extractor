<template>
  <div class="row">
    <div class="col-md-12">
      <el-card class="box-card">
        <el-table :data="this.userslist" style="width: 100%">
          <el-table-column prop="id" label="Id" sortable width="80"></el-table-column>
          <el-table-column prop="firstname" label="Imię" sortable></el-table-column>
          <el-table-column prop="lastname" label="Nazwisko" sortable></el-table-column>
          <el-table-column prop="email" label="e-mail" sortable></el-table-column>
          <el-table-column prop="roles[0].name" label="Uprawnienia" sortable></el-table-column>
          <el-table-column label="Opcje">
            <template scope="scope">
              <el-button size="small" @click="handleEdit(scope.$index, scope.row)">Edytuj</el-button>
              <el-button
                size="small"
                type="danger"
                @click="handleDelete(scope.$index, scope.row)"
              >Usuń</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-card>
    </div>
    <el-dialog title="Edytuj użytkownika" :visible.sync="editVisible" width="90%">
      <edituser :user="editUser" :roleslist="roleslist"></edituser>
    </el-dialog>
  </div>
</template>

  <script>
export default {
  props: ["users", "roles"],

  created() {
    EventBus.$on("refreshlist", () => this.refreshTable());
    EventBus.$on("closeEdit", () => (this.editVisible = false));
  },

  data() {
    return {
      userslist: this.users,
      roleslist: this.roles,
      editUser: "",
      editVisible: false
    };
  },
  methods: {
    handleEdit($index) {
      this.editUser = this.users[$index];
      this.editVisible = true;
    },
    handleDelete($index) {
      this.$confirm(
        "Na pewno usunąć użytkownika?",
        "Potwierdź usunięcie konta",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Anuluj",
          type: "warning"
        }
      )
        .then(() => {
          this.deleteUser($index);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Anulowano działanie"
          });
        });
    },
    deleteUser($index) {
      axios
        .post("/deleteuser", {
          id: this.userslist[$index].id
        })
        .then(response => this.onSuccessDelete());
    },
    onSuccessDelete() {
      this.$message({
        type: "success",
        message: "Usunięto pracownika"
      });
      this.refreshTable();
    },
    refreshTable() {
      axios.get("/users").then(response => (this.userslist = response.data));
    },
    handleClose() {}
  }
};
</script>