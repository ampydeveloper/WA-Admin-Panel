<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <div class="bread_crum">
          <ul>
            <li>
              <h4 class="main-title text-left top_heading">
                Edit Driver
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
              <router-link to="/admin/services">
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

                          <v-col sm="12" class="p-0">
                            <div class="service-image-outer" v-if="avatar">
                              <button type="button" class="close" v-if="cross" @click="Remove()">
                                <span>&times;</span>
                              </button>
                              <img :src="avatar" alt />
                            </div>
                          </v-col>
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
                            label="Enter First Name"
                            required
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
                            label="Enter Last Name"
                            required
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
                          <label>Address</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_address"
                            :rules="[v => !!v || 'Address is required.']"
                            label="Enter Address"
                            required
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
                            label="Enter City"
                            required
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
                            label="Enter Province"
                            required
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
                            :rules="[v => !!v || 'Zipcode is required.']"
                            label="Enter Zipcode"
                            required
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
                            label="Enter Mobile Number"
                            required
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
                            :rules="[v => !!v || 'Licence Number is required.']"
                            label="Enter Licence Number"
                            required
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
                            :rules="[v => !!v || 'Licence Image is required.']"
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
                          <div class="service-image-outer" v-if="document_img">
                            <button type="button" class="close" v-if="cross" @click="Remove()">
                              <span>&times;</span>
                            </button>
                            <img :src="document_img" alt />
                          </div>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Licence Expiry Date</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-menu
                            v-model="menu2"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="date"
                                label="Select Licence Expiry Date"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker v-model="date" @input="menu2 = false" :min="setDate"></v-date-picker>
                          </v-menu>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label class="label_text label-half">Salary Type</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-radio-group
                            row
                            v-model="addForm.salary_type"
                            :rules="[v => !!v || 'Salary Type is required.']"
                          >
                            <v-radio label="Per Hour" value="per_hour"></v-radio>
                            <v-radio label="Per Load" value="per_load"></v-radio>
                          </v-radio-group>
                        </v-col>
                      </div>
                      <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label>Salary</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-text-field
                            v-model="addForm.driver_salary"
                            label="Enter Salary"
                            required
                            :rules="salaryRules"
                          ></v-text-field>
                        </v-col>
                      </div>
<div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label class="label_text label-check-half">Availabilty</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-switch v-model="addForm.status"></v-switch>
                        </v-col>
                      </div>
                      <!-- <div class="custom-col row">
                        <v-col sm="4" class="label-align pt-0">
                          <label class="label_text label-half">Fleet</label>
                        </v-col>
                        <v-col sm="8" class="pt-0 pb-0">
                          <v-radio-group
                            row
                            v-model="addForm.driver_type"
                            :mandatory="false"
                            required
                            :rules="[v => !!v || 'Fleet is required.']"
                          >
                            <v-radio label="Truck" value="Truck"></v-radio>
                            <v-radio label="Skidsteer" value="Skidsteer"></v-radio>
                          </v-radio-group>
                        </v-col>
                      </div>-->
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
                        >Update</v-btn>
                      </div>
                    </v-col>
                  </v-row>
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
import { driverService } from "../../../_services/driver.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
export default {
  components: {
    //      'image-component': imageVUE,
  },

  data() {
    return {
      loading: null,
      docError: false,
      menu2: false,
      uploadInProgress: false,
      valid: true,
      apiUrl: environment.apiUrl,
      avatar: null,
      date: "",
      user_image: "",
      document_img: null,
      cross: false,
      active: 0,
      setDate: new Date().toISOString().substr(0, 10),
      addForm: {
        driver_first_name: "",
        driver_last_name: "",
        email: "",
        driver_licence: "",
        expiry_date: "",
        salary_type: "",
        driver_salary: null,
        driver_licence_image: "",
        user_image: "",
        driver_address: "",
        driver_city: "",
        driver_province: "",
        driver_country: "",
        driver_zipcode: "",
        driver_phone: "",
        driver_type: "",
        status:""
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
      salaryRules: [
        (v) => !!v || "Driver salary is required.",
        (v) => /^\d*$/.test(v) || "Enter valid number.",
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
    driverService.getDriver(this.$route.params.id).then((response) => {
      if (response.status) {
        this.addForm.driver_id = response.data.id;
        if (response.data.user_image) {
          this.addForm.user_image = response.data.user_image;
          this.cross = true;
          this.avatar = environment.imgUrl + response.data.user_image;
        } else {
          this.avatar = environment.imgUrl + "";
        }
        if (response.data.driver.document) {
          this.cross = true;
          this.addForm.driver_licence_image = response.data.driver.document;
          this.document_img =
            environment.imgUrl + response.data.driver.document;
        }
        this.addForm.driver_first_name = response.data.first_name;
        this.addForm.driver_last_name = response.data.last_name;
        this.addForm.email = response.data.email;
        this.addForm.driver_phone = response.data.phone;
        this.addForm.driver_address = response.data.address;
        this.addForm.driver_city = response.data.city;
        this.addForm.driver_province = response.data.state;
        this.addForm.driver_country = response.data.country;
        this.addForm.driver_zipcode = response.data.zip_code;
        this.addForm.driver_salary = response.data.driver.driver_salary;
        this.active = response.data.driver.salary_type;
        this.addForm.driver_type = response.data.driver.driver_type;
        this.addForm.driver_licence = response.data.driver.driver_licence;
        this.addForm.status = response.data.driver.status;
         
        this.date = new Date(response.data.driver.expiry_date)
          .toISOString()
          .substr(0, 10);
        if (response.data.salary_type == 0) {
          this.addForm.salary_type = "per_hour";
        } else {
          this.addForm.salary_type = "per_load";
        }
        if (response.data.driver_type == 1) {
          this.addForm.driver_type = "Truck";
        } else {
          this.addForm.driver_type = "Skidsteer";
        }
        this.addForm.driver_salary = response.data.driver_salary;
      } else {
        router.push("/admin/drivers");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
  methods: {
    Remove() {
      this.avatar = "";
      this.cross = false;
      this.addForm.user_image = "";
      this.document_img = "";
    },
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function (error, file) {
      this.cross = false;
      this.addForm.user_image = file.serverId;
      this.avatar = environment.imgUrl + file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function (file) {
      this.cross = false;
      this.addForm.user_image = "";
      this.avatar = "";
    },
    handleProcessFile1: function (error, file) {
      this.addForm.document = file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
      this.document_img = environment.imgUrl + file.serverId;
    },
    handleRemoveFile1: function (file) {
      this.addForm.document = "";
      this.docError = true;
      this.document_img = "";
    },
    save: function (e) {
      //stop page to reload
      e.preventDefault();

      if (this.addForm.document == "") {
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
      this.addForm.expiry_date = this.date;
      if (this.addForm.salary_type == "per_hour") {
        this.addForm.salary_type = 0;
      } else {
        this.addForm.salary_type = 1;
      }
      if (this.addForm.driver_type == "Skidsteer") {
        this.addForm.driver_type = 0;
      } else {
        this.addForm.driver_type = 1;
      }

      if (this.$refs.form.validate() && !this.docError) {
        if (this.loading) {
          return false;
        }

        //start loader
        this.loading = true;
        driverService
          .edit(this.addForm, this.$route.params.id)
          .then((response) => {
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
              const currentUser = JSON.parse(
                localStorage.getItem("currentUser")
              );
              if (currentUser.data.user.role_id == 1) {
                router.push("/admin/truckdrivers");
              } else {
                router.push("/manager/truckdrivers");
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