<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h2>Edit Driver</h2>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="save">
            <v-row>
              <v-col cols="5" md="5">
                <div
                  class="v-avatar v-list-item__avatar"
                  style="height: 80px; min-width: 80px; width: 80px;"
                >
            <button type="button" class="close AClass" style="margin-right: 13px; margin-top: -25px; font-size: 30px;" v-if="cross" @click="Remove()">
               <span>&times;</span>
             </button>
                  <img :src="avatar" />
                </div>

                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Driver Image"
                    v-bind:allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="user_image"
                    v-on:addfilestart="setUploadIndex"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                    v-on:processfile="handleProcessFile"
                    v-on:processfilerevert="handleRemoveFile"
                  />
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_name"
                    :rules="[v => !!v || 'Driver name is required']"
                    label="Driver Name"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.email"
                    :rules="emailRules"
                    name="email"
                    label="Email Address"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_address"
                    :rules="[v => !!v || 'Address is required']"
                    label="Address"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_city"
                    :rules="[v => !!v || 'City is required']"
                    label="City"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_state"
                    :rules="[v => !!v || 'State is required']"
                    label="State"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_zipcode"
                    :rules="[v => !!v || 'Zip code is required']"
                    label="zipcode"
                    required
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="5" md="5">
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_phone"
                    :rules="phoneRules"
                    label="Mobile Number"
                    required
                    maxlength="10"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_licence"
                    :rules="[v => !!v || 'driver licence number is required']"
                    label="Driver Licence Number"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
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
                        label="Licence Expiry Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu2 = false" :min="setDate"></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="12">
                  <v-radio-group
                    v-model="addForm.salary_type"
                    :rules="[v => !!v || 'Driver salary type is required']"
                  >
                    <v-radio label="Per Hour" value="per_hour"></v-radio>
                    <v-radio label="Per Load" value="per_load"></v-radio>
                  </v-radio-group>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_salary"
                    label="Driver Salary"
                    required
                    :rules="salaryRules"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Upload Document"
                    v-bind:allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:addfilestart="setUploadIndex"
                    v-on:processfile="handleProcessFile1"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                    :rules="[v => !!v || 'Document is required']"
                    v-on:processfilerevert="handleRemoveFile1"
                  />
                  <div class="v-messages theme--light error--text" role="alert" v-if="docError">
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">Document upload is required</div>
                    </div>
                  </div>
                </v-col>
                <div v-if="document_img" style="height: 200px; min-width: 200px; width: 200px;">
                  <img :src="document_img" alt="Doc" width="100%" />
                </div>
              </v-col>
              <v-col cols="12" md="12">
                <v-radio-group
                  row
                  v-model="addForm.driver_type"
                  :mandatory="false"
                  required
                  :rules="[v => !!v || 'Truck type is required']"
                >
                  <v-radio label="Truck" value="Truck"></v-radio>
                  <v-radio label="Skidsteer" value="Skidsteer"></v-radio>
                </v-radio-group>
              </v-col>
              <v-col cols="12" md="12">
                <v-btn
                type="submit"
                  :loading="loading"
                  :disabled="loading"
                  color="success"
                  class="mr-4 custom-save-btn ml-4"
                  @click="save"
                >Submit</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-col>
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
      cross:false,
      active: 0,
      setDate: new Date().toISOString().substr(0, 10),
      addForm: {
        driver_name: "",
        email: "",
        driver_licence: "",
        expiry_date: "",
        salary_type: "",
        driver_salary: "",
        document: "",
        user_image: "",
        driver_address: "",
        driver_city: "",
        driver_state: "",
        driver_country: "",
        driver_zipcode: "",
        driver_phone: "",
        driver_type: ""
      },
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+/.test(v) || "E-mail must be valid"
      ],
      phoneRules: [
        v => !!v || "Phone number is required",
        v => /^\d*$/.test(v) || "Enter valid number",
        v => v.length >= 10 || "Enter valid number length"
      ],
      salaryRules: [
        v => !!v || "Driver salary is required",
        v => /^\d*$/.test(v) || "Enter valid number"
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
        revert:{
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
    driverService.getDriver(this.$route.params.id).then(response => {
      if (response.status) {
        this.addForm.user_id = response.data.user.id;
        if (response.data.user.user_image) {
          this.addForm.user_image = response.data.user.user_image;
          this.cross=true;
          this.avatar = environment.imgUrl + response.data.user.user_image;
        } else {
          this.avatar = environment.imgUrl + "images/avatar.png";
        }
        if (response.data.document) {
        this.addForm.document = response.data.document;
          this.document_img = environment.imgUrl + response.data.document;
        }
        this.addForm.driver_name = response.data.user.first_name;
        this.addForm.email = response.data.user.email;
        this.addForm.driver_phone = response.data.user.phone;
        this.addForm.driver_address = response.data.user.address;
        this.addForm.driver_city = response.data.user.city;
        this.addForm.driver_state = response.data.user.state;
        this.addForm.driver_country = response.data.user.country;
        this.addForm.driver_zipcode = response.data.user.zip_code;
        this.active = response.data.salary_type;
        this.addForm.driver_type = response.data.driver_type;
        this.addForm.driver_licence = response.data.driver_licence;
        this.date = new Date(response.data.expiry_date)
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
          position: "top-right"
        });
      }
    });
  },
  methods: {
  Remove(){
    this.avatar = "/images/avatar.png";
    this.cross=false;
    this.addForm.user_image = '';
  },
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function(error, file) {
      this.cross=false;
      this.addForm.user_image = file.serverId;
      this.avatar = environment.imgUrl + file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function(file){
      this.cross=false;
      this.addForm.user_image = '';
      this.avatar = "/images/avatar.png";
    },
    handleProcessFile1: function(error, file) {
      this.addForm.document = file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
      this.document_img = environment.imgUrl + file.serverId;
    },
    handleRemoveFile1: function(file){
      this.addForm.document = '';
      this.docError = true;
      this.document_img = '';
    },
    save: function(e) {
      //stop page to reload
      e.preventDefault();

      if (this.addForm.document == "") {
        this.docError = true;
      }

      if(this.uploadInProgress) {
        this.$toast.open({
              message: "Image uploading is in progress!",
              type: "error",
              position: "top-right"
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
        if(this.loading) {
          return false;
        }

        //start loader
        this.loading = true;
        driverService
          .edit(this.addForm, this.$route.params.id)
          .then(response => {
            //stop loader
            this.loading = false;
            //handle response
            if (response.status) {
              this.$toast.open({
                message: response.message,
                type: "success",
                position: "top-right"
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
                position: "top-right"
              });
            }
          });
      }
    }
  }
};
</script>
