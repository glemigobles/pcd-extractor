<template>
  <div class="row">
    <div class="col-md-12">
      <el-card class="box-card" shadow="never">
        <el-upload
          class="avatar-uploader"
          action="/uploadfile"
          :multiple="false"
          :headers="{ 'X-CSRF-TOKEN': csrf}"
          :file-list="fileList"
          :limit="1"
          :on-exceed="handleExceed"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload"
        >
          <el-button size="large" type="primary">Kliknij by dodać plik</el-button>
          <div slot="tip" class="el-upload__tip">(xlsx poniżej 1024kb)</div>
        </el-upload>
        <el-button
          size="large"
          @click="createRaport()"
          type="danger"
          :disabled="this.fileUrl==null"
          style="margin-top:10px;"
        >Twórz raport</el-button>
      </el-card>
    </div>
  </div>
</template>


  <script>
export default {
  props: {
    csrf: {
      type: String
    }
  },

  created() {},

  data() {
    return {
      fileUrl: null,
      filename: null,
      fileList: [],
      fullscreenloading: "",
      displayReUpload: false
    };
  },
  methods: {
    createRaport() {
      //console.log(this.documents);
      this.fullscreenloading = this.$loading({ fullscreen: true });
      axios
        .post("/createraport", {
          fileUrl: this.fileUrl,
          filename: this.filename
        })
        .then(response => {
          this.onSuccesRes(response);
        });
    },
    onSuccesRes(response) {
      this.fullscreenloading.close();
      console.log(response.data);
      if (response.data == "blad/brak pliku") {
        this.$message({
          type: "warning",
          message: "Brak pliku"
        });
      } else {
        let link = document.createElement("a");
        link.href = response.data;
        //console.log(response.data);
        link.download = "raport.xlsx";
        link.click();
      }
      this.fileUrl = null;
      this.filename = null;
    },

    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePreview(file) {
      console.log(file);
    },
    beforeRemove(file, fileList) {
      return this.$confirm("Na pewno chcesz usunąć plik?");
    },
    handleExceed(files, fileList) {
      this.$message.warning("Dozwolony tylko 1 plik");
    },
    beforeAvatarUpload(file) {
      const isXLSX =
        file.type ===
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isXLSX) {
        this.$message.error("Plik musi być w formacie .xlsx");
      }
      if (!isLt2M) {
        this.$message.error("Rozmiar pliku nie może przekraczać 2MB!");
      }
      return isXLSX && isLt2M;
    },
    handleAvatarSuccess(res, file) {
      this.fileUrl = res.success;
      //console.log(file);
      this.filename = file.name;
    }
  }
};
</script>