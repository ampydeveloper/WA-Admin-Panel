<template>
  <v-app>
    <v-container fluid class="pt-0">
      <v-row>
        <div class="bread_crum">
          <ul>
            <li>
              <h4 class="main-title text-left top_heading">
                Edit Admin
                <span class="right-bor"></span>
              </h4>
            </li>
            <li>
              <router-link to="/admin/dashboard" class="home_svg">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24px"
                  height="24px"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-home h-5 w-5 mb-1 stroke-current text-primary"
                >
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                  <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16px"
                    height="16px"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="feather feather-chevrons-right w-4 h-4"
                  >
                    <polyline points="13 17 18 12 13 7" />
                    <polyline points="6 17 11 12 6 7" />
                  </svg>
                </span>
              </router-link>
            </li>
            <li>
              <router-link to="/admin/admin">
                List
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16px"
                    height="16px"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="feather feather-chevrons-right w-4 h-4"
                  >
                    <polyline points="13 17 18 12 13 7" />
                    <polyline points="6 17 11 12 6 7" />
                  </svg>
                </span>
              </router-link>
            </li>
            <li>Edit</li>
          </ul>
        </div>

        <div class="main_box">
          <v-container fluid>
            <v-row>
              <v-col cols="12" md="12" class="pl-0 slide-left">
                <v-form
                  ref="form"
                  v-model="valid"
                  class="custom_form_field"
                  id="form_field"
                  lazy-validation
                  @submit="update"
                >
                  <v-col cols="12" md="12" class="pb-0">
                    <v-col sm="2" class="label-align pt-0 image-upload-label">
                      <label class="label_text">Profile Photo</label>
                    </v-col>
                    <v-col sm="4" class="pt-0 pb-0">
                      <file-pond
                        name="uploadImage"
                        ref="pond"
                        label-idle="Drop or Browse your files"
                        v-bind:allow-multiple="false"
                        accepted-file-types="image/jpeg, image/png"
                        v-bind:server="serverOptions"
                        v-bind:files="myFiles"
                        v-on:addfilestart="setUploadIndex"
                        v-on:processfile="handleProcessFile"
                        v-on:processfilerevert="handleRemoveFile"
                      />
                    </v-col>
                  </v-col>
                  <v-col cols="12" md="12" class="pt-0 pb-0">
                    <v-col sm="2" class="label-align pt-0">
                      <label>First Name</label>
                    </v-col>
                    <v-col sm="4" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.first_name"
                        label="Enter First Name"
                        :rules="FnameRules"
                        required
                      ></v-text-field>
                    </v-col>
                  </v-col>
                  <v-col cols="12" md="12" class="pt-0 pb-0">
                    <v-col sm="2" class="label-align pt-0">
                      <label>Last Name</label>
                    </v-col>
                    <v-col sm="4" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.last_name"
                        label="Enter Last Name"
                        :rules="LnameRules"
                        required
                      ></v-text-field>
                    </v-col>
                  </v-col>

                  <v-col cols="12" md="12" class="pt-0 pb-0">
                    <v-col sm="2" class="label-align pt-0">
                      <label>Email</label>
                    </v-col>
                    <v-col sm="4" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.email"
                        :rules="emailRules"
                        name="email"
                        label="Enter Email"
                        required
                      ></v-text-field>
                    </v-col>
                  </v-col>

                  <v-col class="pt-0 pb-0" cols="12" md="12">
                    <v-col sm="2"></v-col>
                    <v-col sm="9" class="pt-0 pb-0">
                      <v-btn
                        type="submit"
                        :loading="loading"
                        :disabled="loading"
                        color="success"
                        class="custom-save-btn mt-4"
                        @click="update"
                        id="submit_btn"
                      >Update</v-btn>
                    </v-col>
                  </v-col>
                </v-form>
              </v-col>
            </v-row>
          </v-container>
        </div>
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
  components: {},
  data() {
    return {
      loading: false,
      valid: true,
      avatar: null,
      uploadInProgress: false,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      cross: false,
      addForm: {
        first_name: "",
        last_name: "",
        email: "",
        user_image: null,
        phone: "",
      },
      FnameRules: [(v) => !!v || "First Name is required."],
      LnameRules: [(v) => !!v || "Last Name is required."],
      emailRules: [
        (v) => !!v || "Email is required.",
        (v) => /.+@.+/.test(v) || "Email must be valid.",
      ],
      rules: [
        (value) =>
          !value ||
          value.size < 2000000 ||
          "Photo size should be less than 2 MB.",
      ],
      myFiles: [],
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
            Authorization: "Bearer " + currentUser.data.access_token,
          },
        },
        revert: {
          url: "deleteImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token,
          },
        },
      };
    },
    url() {
      if (this.file) {
        let parsedUrl = new URL(this.file);
        return [parsedUrl.pathname];
      } else {
        return null;
      }
    },
  },
  created() {
    this.loading = true;
    adminService.getAdmin(this.$route.params.id).then((response) => {
      if (response.status) {
        this.addForm.user_id = response.data.id;
        if (response.data.user_image) {
          this.cross = true;
          this.addForm.user_image = response.data.user_image;
          this.avatar = this.imgUrl + response.data.user_image;
        } else {
          this.avatar = "/images/avatar.png";
        }
        this.addForm.first_name = response.data.first_name;
        this.addForm.last_name = response.data.last_name;
        this.addForm.email = response.data.email;
        this.addForm.user_image = response.data.user_image;
      } else {
        router.push("/admin/manager");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
      this.loading = false;
    });
  },
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function (error, file) {
      this.addForm.user_image = file.serverId;
      this.avatar = this.imgUrl + file.serverId;
      this.uploadInProgress = false;
      this.cross = false;
    },
    handleRemoveFile: function (file) {
      this.addForm.user_image = "";
      this.avatar = "/images/avatar.png";
      this.cross = false;
    },
    Remove() {
      this.avatar = "/images/avatar.png";
      this.cross = false;
      this.addForm.user_image = "";
    },
    update: function (e) {
      //stop page to reload
      e.preventDefault();

      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Photo uploading is in progress.",
          type: "error",
          position: "top-right",
        });
        return false;
      }
      if (this.$refs.form.validate()) {
        if (this.loading) {
          return false;
        }
        //start loading
        this.loading = true;
        adminService
          .edit(this.addForm, this.$route.params.id)
          .then((response) => {
            //stop loading
            this.loading = false;
            //handle response
            if (response.status) {
              this.$toast.open({
                message: response.message,
                type: "success",
                position: "top-right",
              });
              //redirect to login
              router.push("/admin/admin");
            } else {
              this.$toast.open({
                message: response.message,
                type: "error",
                position: "top-right",
              });
            }
            this.loading = false;
          });
      }
    },
  },
};
</script>