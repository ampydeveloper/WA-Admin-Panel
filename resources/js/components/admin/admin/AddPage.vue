<template>
  <v-app>
    <v-container>
      <v-row>
        <h4 class="main-title text-left">Add New Admin</h4>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="update">
            <v-col cols="12" md="12">
              <file-pond
                name="uploadImage"
                ref="pond"
                label-idle="Drop files here..."
                v-bind:allow-multiple="false"
                v-bind:server="serverOptions"
                v-bind:files="myFiles"
                v-on:addfilestart="setUploadIndex"
                accepted-file-types="image/jpeg, image/png"
                v-on:processfile="handleProcessFile"
                v-on:processfilerevert="handleRemoveFile"
              />
            </v-col>
            <v-col cols="12" md="12" class="pt-0">
              <v-col sm="2" class="label-align pt-0">
                <label>First name</label>
              </v-col>
              <v-col sm="4" class="pt-0">
              <v-text-field
                v-model="addForm.first_name"
                :rules="FnameRules"
                required
              ></v-text-field>
              </v-col>
            </v-col>
            <v-col cols="12" md="12" class="pt-0">
              <v-col sm="2" class="label-align pt-0">
                <label>Last name</label>
              </v-col>
              <v-col sm="4" class="pt-0">
              <v-text-field
                v-model="addForm.last_name"
                :rules="LnameRules"
                required
              ></v-text-field>
              </v-col>
            </v-col>

            <v-col cols="12" md="12" class="pt-0">
              <v-col sm="2" class="label-align pt-0">
                <label>E-mail</label>
              </v-col>
              <v-col sm="4" class="pt-0">
              <v-text-field
                v-model="addForm.email"
                :rules="emailRules"
                name="email"
                required
              ></v-text-field>
              </v-col>
            </v-col>
            <v-btn type="submit" :loading="loading" :disabled="loading" color="success" class="mr-4 custom-save-btn" @click="update">Submit</v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { adminService } from "../../../_services/admin.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";

export default {
  components: {
    //      'image-component': imageVUE,
  },
  data() {
    return {
      loading: false,
      valid: true,
      avatar: null,
      uploadInProgress: false,
      apiUrl: environment.apiUrl,
      addForm: {
        first_name: "",
        last_name: "",
        email: "",
        user_image: null,
        phone: "",
        role_id: 1
      },
      FnameRules: [v => !!v || "First name is required"],
      LnameRules: [v => !!v || "Last name is required"],
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+/.test(v) || "E-mail must be valid"
      ],
      rules: [
        value =>
          !value ||
          value.size < 2000000 ||
          "Avatar size should be less than 2 MB!"
      ],
      myFiles: []
    };
  },
  computed: {
    serverOptions() {
      const currentUser = JSON.parse(localStorage.getItem("currentUser"));
      return {
        url: this.apiUrl,
        withCredentials: false,
        process: {
          url: "uploadImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token
          }
        },
        revert: {
          url: "deleteImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token
          }
        }
      };
    },
    url() {
      if (this.file) {
        let parsedUrl = new URL(this.file);
        return [parsedUrl.pathname];
      } else {
        return null;
      }
    }
  },
  created() {
    this.avatar = "/images/avatar.png";
  },
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function(error, file) {
      this.addForm.user_image = file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function(file) {
      this.addForm.user_image = "";
      this.avatar = "/images/avatar.png";
    },
    update: function(e) {
      //stop page to reload
      e.preventDefault();

      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Profile image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      }
      if (this.$refs.form.validate()) {
        if(this.loading) {
          return false;
        }
        //start loading
        this.loading = true;
        adminService.add(this.addForm).then(response => {
          //stop loading
          this.loading = false;
          
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            router.push("/admin/admin");
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
          this.loading = false;
        });
      }
    }
  }
};
</script>
<style>
.wizard-footer-left {
    display: none;
}
</style>
