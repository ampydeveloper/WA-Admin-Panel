<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Create Hauler Driver
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
            Hauler
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
            Driver
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
        </li>
        <li>Create</li>
      </ul>
    </div>

    <div class="main_box">
          <v-container fluid>
            <v-row>
              <v-col cols="12" md="12">
                <v-form
                  ref="form"
                  v-model="valid"
                  class="v-form custom_form_field divide-50"
                  id="form_field"
                  lazy-validation
                  @submit="save"
                >
     
                  <v-row>
                    <v-col cols="6" md="6" class="pl-0 manager-cols">
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
                            v-bind:files="user_image"
                            v-on:addfilestart="setUploadIndex"
                            allow-file-type-validation="true"
                            accepted-file-types="image/jpeg, image/png"
                            v-on:processfile="handleProcessFile"
                            v-on:processfilerevert="handleRemoveFile"
                          />
                          <div
                            class="v-messages theme--light error--text"
                            role="alert"
                            v-if="profileImgError"
                          >
                            <div class="v-messages__wrapper">
                              <div class="v-messages__message">Profile image is required.</div>
                            </div>
                          </div>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>First Name</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_first_name"
                            :rules="[v => !!v || 'Driver First Name is required.']"
                            required
                            label="Enter First Name"
                            placeholder
                          ></v-text-field>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Last Name</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_last_name"
                            :rules="[v => !!v || 'Driver Last Name is required.']"
                            required
                            label="Enter Last Name"
                            placeholder
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
                            required
                            label="Enter Email"
                            placeholder
                          ></v-text-field>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Address</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_address"
                            :rules="[v => !!v || 'Address is required.']"
                            required
                            label="Enter Address"
                            placeholder
                          ></v-text-field>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>City</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_city"
                            :rules="[v => !!v || 'City is required.']"
                            required
                            label="Enter City"
                            placeholder
                          ></v-text-field>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Province</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_province"
                            :rules="[v => !!v || 'Province is required.']"
                            required
                            label="Enter Province"
                            placeholder
                          ></v-text-field>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Zipcode</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_zipcode"
                            :rules="[v => !!v || 'Zip code is required.']"
                            required
                            label="Enter Zipcode"
                            placeholder
                          ></v-text-field>
                        </v-col>
                      </div>
                    </v-col>

                    <v-col cols="6" md="6" class="pl-0 manager-cols">
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Mobile Number</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_phone"
                            :rules="phoneRules"
                            required
                            label="Enter Number"
                            placeholder
                            maxlength="10"
                          ></v-text-field>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Licence Number</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_licence"
                            :rules="[v => !!v || 'Driver licence number is required.']"
                            required
                            label="Enter Licence Number"
                            placeholder
                          ></v-text-field>
                        </v-col>
                      </div>
                      <div class="custom-col row custom-img-holder">
                        <v-col sm="4" class="label-align pt-0 image-upload-label">
                          <label>Licence Image</label>
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
                            v-on:processfile="handleProcessFile1"
                            allow-file-type-validation="true"
                            accepted-file-types="image/jpeg, image/png"
                            :rules="[v => !!v || 'Licence Image is required']"
                            v-on:processfilerevert="handleRemoveFile1"
                          />
                          <div
                            class="v-messages theme--light error--text"
                            role="alert"
                            v-if="docError"
                          >
                            <div class="v-messages__wrapper">
                              <div class="v-messages__message">Licence Image is required.</div>
                            </div>
                          </div>
                        </v-col>
                      </div>

                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Select Haulers</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-select
                                v-model="addForm.hauler_id"
                                :items="HaulerName"
                                :rules="[v => !!v || 'Hauler is required.']"
                                item-text="first_name"
                                item-value="id"
                                @change="getHaulers"
                              ></v-select>
                        </v-col>
                      </div>
                    </v-col>

                    <v-col class="pt-0 pb-0" cols="12" md="12">
                      <div class="p-0 float-right">
                        <v-btn
                          type="submit"
                          :loading="loading"
                          :disabled="loading"
                          color="success"
                          class="custom-save-btn"
                          @click="save"
                          id="submit_btn"
                        >Add Hauler Driver</v-btn>
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
    //      'image-component': imageVUE,
  },

  data() {
    return {
      loading: null,
      menu2: false,
      docError: false,
      profileImgError: false,
      valid: true,
      uploadInProgress: false,
      apiUrl: environment.apiUrl,
      avatar: null,
      date: "",
      user_image: "",
      setDate: new Date().toISOString().substr(0, 10),
      role: 1,
      hauler_id:"",
      HaulerName: [],
      addForm: {
        hauler_id:"",
        driver_first_name: "",
        driver_last_name:"",
        email: "",
        driver_licence: "",
        driver_licence_image: "",
        user_image: "",
        driver_address: "",
        driver_city: "",
        driver_province: "",
        driver_country: "",
        driver_zipcode: "",
        driver_phone: "",
      },
      emailRules: [
        (v) => !!v || "Email is required.",
        (v) => /.+@.+/.test(v) || "Email must be valid.",
      ],
      phoneRules: [
        (v) => !!v || "Phone number is required.",
        (v) => /^\d*$/.test(v) || "Enter valid number.",
        (v) => v.length >= 10 || "Enter valid number.",
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
    this.avatar = "/images/avatar.png";
  },

  mounted() {
    this.getHaulers();
  },
  methods: {
    getHaulers() {
      companyService.listHauler().then((response) => {
        //handle response
        if (response.status) {
          this.HaulerName = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function (error, file) {
      this.addForm.user_image = file.serverId;
      this.avatar = environment.baseUrl + file.serverId;
      this.profileImgError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function (file) {
      this.addForm.user_image = "";
      this.avatar = "/images/avatar.png";
    },
    handleProcessFile1: function (error, file) {
      this.addForm.driver_licence_image = file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function (file) {
      this.addForm.driver_licence_image = "";
      this.docError = true;
    },
    save: function (e) {
      //stop page to reload
      e.preventDefault();

      if (this.addForm.driver_licence_image == "") {
        this.docError = true;
      }

      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress.",
          type: "error",
          position: "top-right",
        });
        return false;
      }

      if (this.$refs.form.validate() && !this.docError) {
        if (this.loading) {
          return false;
        }
        //start loader
        this.loading = true;

        companyService.addHaulerDriver(this.addForm).then((response) => {
          //stop loader
          this.loading = false;
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            //redirect to login
            const currentUser = JSON.parse(localStorage.getItem("currentUser"));
            if (currentUser.data.user.role_id == 1) {
              router.push("/admin/hauler");
            } else {
              router.push("/manager/hauler");
            }
          } else {
            //stop loader
            this.loading = false;
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