<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Create Haulers
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
          <router-link to="/admin/hauler">
            Haulers
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
        <li>Create</li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="12" class>
            <v-form
              ref="form"
              v-model="valid"
              class="v-form custom_form_field divide-50"
              lazy-validation
              id="form_field"
            >
              <v-row>
                <div class="col-xs-12 col-sm-6 pl-0 manager-cols">
                  <div class="custom-col row custom-img-holder">
                    <v-col sm="4" class="label-align pt-0 image-upload-label">
                      <label>Profile Image</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <file-pond
                        name="uploadImage"
                        ref="pond"
                        label-idle="Drop or Browse your files"
                        v-bind:allow-multiple="false"
                        v-bind:server="serverOptions"
                        v-bind:files="myFiles"
                        v-on:addfilestart="setUploadIndex"
                        allow-file-type-validation="true"
                        accepted-file-types="image/jpeg, image/png"
                        v-on:processfile="handleProcessFile"
                        v-on:processfilerevert="handleRemoveFile"
                      />
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Prefix</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-select
                        v-model="addForm.prefix"
                        :items="prefixs"
                        label="Select Prefix"
                        :rules="[v => !!v || 'Prefix is required.']"
                      ></v-select>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>First Name</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.first_name"
                        label="Enter First Name"
                        required
                        :rules="[v => !!v || 'Customer Name is required.']"
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Last Name</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.last_name"
                        label="Enter Last Name"
                        required
                        :rules="[v => !!v || 'Customer Name is required.']"
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Email</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.email"
                        :rules="emailRules"
                        name="email"
                        label="Enter Email"
                        required
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Phone</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.phone"
                        :rules="phoneRules"
                        label="Enter Phone"
                        required
                        maxlength="10"
                      ></v-text-field>
                    </v-col>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 pl-0 manager-cols">
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Address</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.address"
                        label="Enter Address"
                        required
                        :rules="[v => !!v || 'Address is required.']"
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>City</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.city"
                        label="Enter City"
                        required
                        :rules="[v => !!v || 'City is required.']"
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Province</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.province"
                        label="Enter Province"
                        required
                        :rules="[v => !!v || 'Province is required.']"
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Zipcode</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.zipcode"
                        :rules="[v => !!v || 'Zipcode is required.']"
                        label="Enter Zipcode"
                        required
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label class="label_text label-check-half">Status</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-switch v-model="addForm.is_active" class="mx-2"></v-switch>
                    </v-col>
                  </div>
                </div>

                <v-col class="pt-0 pb-0" cols="12" md="12">
                  <div class="p-0 float-right">
                    <v-btn
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      type="submit"
                      class="custom-save-btn"
                      @click="update"
                      id="submit_btn"
                    >Create Haulers</v-btn>
                    <router-link to="/admin/hauler" class="btn-custom-danger">Cancel</router-link>
                  </div>
                </v-col>
              </v-row>
            </v-form>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { companyService } from "../../../_services/company.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    VueGoogleAutocomplete,
  },
  data() {
    return {
      loading: false,
      prefixs: ["Ms.", "Mr.", "Mrs."],
      valid: true,
      avatar: null,
      customer_img: "",
      uploadInProgress: false,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      uberMapApiUrl: environment.uberMapApiUrl,
      uberMapToken: environment.uberMapToken,
      addForm: {
        prefix: "",
        first_name: "",
        last_name: "",
        email: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        user_image: null,
        zipcode: "",
        is_active: true,
        customer_role: 6,
      },
      emailRules: [
        (v) => !!v || "Email is required.",
        (v) => /.+@.+/.test(v) || "Email must be valid.",
      ],
      phoneRules: [
        (v) => !!v || "Phone Number is required.",
        (v) => /^\d*$/.test(v) || "Phone Number must be valid.",
        (v) => v.length >= 10 || "Phone Number must be greater than 10 characters.",
      ],
      rules: [
        (value) =>
          !value ||
          value.size < 2000000 ||
          "Profile Image size should be less than 2 MB.",
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
    this.customer_img = "/images/avatar.png";
  },
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function (error, file) {
      this.customer_img = this.imgUrl + file.serverId;
      this.addForm.user_image = file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function (file) {
      this.addForm.user_image = "";
      this.customer_img = "/images/avatar.png";
    },
    update() {
      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress.",
          type: "error",
          position: "top-right",
        });
        return false;
      }
      if (this.$refs.form.validate()) {
        //start loading
        this.loading = true;
        companyService.add(this.addForm).then((response) => {
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
            const currentUser = authenticationService.currentUserValue;
            if (currentUser.data.user.role_id == 1) {
              router.push("/admin/hauler");
            } else {
              router.push("/manager/hauler");
            }
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right",
            });
          }
        });
      }
    },
  },
};
</script>