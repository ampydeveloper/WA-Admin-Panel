<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h2>Add New Driver</h2>
        <v-col cols="12" md="12" class="new_driver">
          <v-form ref="form" v-model="valid" lazy-validation @submit="save">
            <v-row>
              <v-col cols="6" md="6">
                <div
                  class="v-avatar v-list-item__avatar"
                  style="height: 80px; min-width: 80px; width: 80px;"
                  v-if="avatar"
                >
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
                  <div class="v-messages theme--light error--text" role="alert" v-if="profileImgError">
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">Profile image is required</div>
                    </div>
                  </div>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Driver name</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.driver_name"
                      :rules="[v => !!v || 'Driver name is required']"
                      required
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Email Address</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.email"
                    :rules="emailRules"
                    name="email"
                    required
                  ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Address</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.driver_address"
                    :rules="[v => !!v || 'Address is required']"
                    required
                  ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>City</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.driver_city"
                    :rules="[v => !!v || 'City is required']"
                    required
                  ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>State</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.driver_state"
                    :rules="[v => !!v || 'State is required']"
                    required
                  ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Zipcode</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.driver_zipcode"
                    :rules="[v => !!v || 'Zip code is required']"
                    required
                  ></v-text-field>
                  </v-col>
                </v-col>
                <!-- <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_country"
                    :rules="[v => !!v || 'Country is required']"
                    label="Country"
                    required
                  ></v-text-field>
                </v-col>-->
              </v-col>

              <v-col cols="6" md="6">
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Mobile Number</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.driver_phone"
                    :rules="phoneRules"
                    required
                    maxlength="10"
                  ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Driver Licence Number</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.driver_licence"
                    :rules="[v => !!v || 'driver licence number is required']"
                    required
                  ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Licence Expiry Date</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
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
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu2 = false" :min="setDate"></v-date-picker>
                  </v-menu>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-radio-group
                  row
                    v-model="addForm.salary_type"
                    :rules="[v => !!v || 'Field is required']"
                  >
                    <v-radio label="Per Hours" value="0"></v-radio>
                    <v-radio label="Per Load" value="1"></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Driver Salary</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                  <v-text-field
                    v-model="addForm.driver_salary"
                    required
                    :rules="salaryRules"
                  ></v-text-field>
                  </v-col>
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

                <v-col cols="12" md="12" class="pt-0">
                  <v-radio-group
                    row
                    v-model="addForm.driver_type"
                    :mandatory="false"
                    required
                    :rules="[v => !!v || 'Truck type is required']"
                  >
                    <v-radio label="Truck" value="1"></v-radio>
                    <v-radio label="Skidsteer" value="0"></v-radio>
                  </v-radio-group>
                </v-col>
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
      addForm: {
        driver_name: "",
        email: "",
        driver_licence: "",
        expiry_date: "",
        salary_type: "",
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
    this.avatar = "/images/avatar.png";
  },
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function(error, file) {
      this.addForm.user_image = file.serverId;
      this.avatar = environment.baseUrl + file.serverId;
      this.profileImgError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function(file){
      this.addForm.user_image = '';
      this.avatar = "/images/avatar.png";
    },
    handleProcessFile1: function(error, file) {
      this.addForm.document = file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function(file){
      this.addForm.document = '';
      this.docError = true
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
      if (this.$refs.form.validate() && !this.docError) {
        if(this.loading) {
          return false;
        }
        //start loader
        this.loading = true;
        
        driverService.add(this.addForm).then(response => {
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
            const currentUser = JSON.parse(localStorage.getItem("currentUser"));
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
