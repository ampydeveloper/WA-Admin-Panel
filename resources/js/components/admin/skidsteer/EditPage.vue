<template>
  <v-app>
    <v-container fluid class="pt-0">
      <v-row>
        <div class="bread_crum">
          <ul>
            <li>
              <h4 class="main-title text-left top_heading">
                Edit Skidsteer
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
              <router-link to="/admin/skidsteer">
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
            <v-form
              ref="form"
              v-model="valid"
              lazy-validation
              @submit="save"
              class="custom_form_field divide-50"
              id="form_field"
            >
              <v-row>
                <v-col cols="6" md="6" class="pl-0 pt-0 manager-cols">
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Company Name</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.company_name"
                        :rules="[v => !!v || 'Company Name is required.']"
                        label="Enter Company Name"
                        required
                      ></v-text-field>
                    </v-col>
                  </div>

                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Skidsteer Number</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.truck_number"
                        :rules="[v => !!v || 'Skidsteer Number is required.']"
                        label="Enter Skidsteer Number"
                        required
                      ></v-text-field>
                    </v-col>
                  </div>

                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Chassis Number</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.chaase_number"
                        :rules="[v => !!v || 'Chassis Number is required.']"
                        label="Enter Chassis Number"
                        required
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Insurance Number</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.insurance_number"
                        :rules="[v => !!v || 'Insurance Number is required.']"
                        label="Enter Insurance Number"
                        required
                      ></v-text-field>
                    </v-col>
                  </div>

                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Insurance Date</label>
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
                            label="Select Insurance Date"
                            prepend-icon="event"
                            readonly
                            v-on="on"
                            required
                            :rules="[v => !!v || 'Insurance Date is required.']"
                          ></v-text-field>
                        </template>
                        <v-date-picker v-model="date" @input="menu2 = false"></v-date-picker>
                      </v-menu>
                    </v-col>
                  </div>

                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Insurance Expiry Date</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-menu
                        v-model="menu1"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on }">
                          <v-text-field
                            v-model="date1"
                            label="Select Insurance Expiry Date"
                            prepend-icon="event"
                            readonly
                            v-on="on"
                            required
                            :rules="[v => !!v || 'Insurance Expiry Date is required.']"
                          ></v-text-field>
                        </template>
                        <v-date-picker v-model="date1" @input="menu1 = false" :min="setDate"></v-date-picker>
                      </v-menu>
                    </v-col>
                  </div>
                </v-col>

                <v-col cols="6" md="6" class="pl-0 pt-0 manager-cols">
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Total Miles</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.total_killometer"
                        label="Enter Total Miles"
                        required
                        :rules="killometerRules"
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Capacity</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        type="number"
                        v-model="addForm.capacity"
                        label="Enter Capacity"
                        required
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>RC</label>
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
                        v-on:processfilerevert="handleRemoveFile1"
                        allow-file-type-validation="true"
                        accepted-file-types="image/jpeg, image/png"
                      />
                      <div class="v-messages theme--light error--text" role="alert" v-if="docError">
                        <div class="v-messages__wrapper">
                          <div class="v-messages__message">RC Document is required.</div>
                        </div>
                      </div>
                      <div v-if="rc" style="height:200px; width:200px">
                        <img :src="rc" alt="Rc" style="height:200px;" />
                      </div>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Insurance</label>
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
                        v-on:processfile="handleProcessFile2"
                        v-on:processfilerevert="handleRemoveFile2"
                        allow-file-type-validation="true"
                        accepted-file-types="image/jpeg, image/png"
                      />
                      <div
                        class="v-messages theme--light error--text"
                        role="alert"
                        v-if="insdocError"
                      >
                        <div class="v-messages__wrapper">
                          <div class="v-messages__message">Insurance Document is required.</div>
                        </div>
                      </div>
                      <div v-if="insurancedocument" style="height:200px; width:200px">
                        <img :src="insurancedocument" alt="insurancedocument" style="height:200px;" />
                      </div>
                    </v-col>
                  </div>

                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label>Availibilty</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-switch v-model="addForm.is_active"></v-switch>
                    </v-col>
                  </div>
                </v-col>

                <!-- <v-col cols="12" md="12">
                  <v-btn
                    type="submit"
                    :loading="loading"
                    :disabled="loading"
                    color="success"
                    class="mr-4 custom-save-btn"
                    @click="save"
                  >Submit</v-btn>
                </v-col>-->

                <v-col class="pt-0 pb-0" cols="12" md="12">
                  <div class="p-0 float-right">
                    <v-btn
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      type="submit"
                      class="custom-save-btn"
                      @click="save"
                      id="submit_btn"
                    >Update</v-btn>
                  </div>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </div>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { truckService } from "../../../_services/truck.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {},

  data() {
    return {
      loading: null,
      docError: false,
      insdocError: false,
      menu2: false,
      menu1: false,
      valid: true,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      rc: null,
      insurancedocument: null,
      date: "",
      date1: "",
      uploadInProgress: false,
      setDate: new Date().toISOString().substr(0, 10),
      user_image: "",
      addForm: {
        vehicle_type: 1,
        company_name: "",
        truck_number: "",
        chaase_number: "",
        insurance_number: "",
        insurance_date: "",
        document: "",
        insurance_document: "",
        total_killometer: "",
        capacity: "",
        insurance_expiry: "",
        is_active: "",
      },
      killometerRules: [
        (v) => !!v || "Truck Miles is required.",
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
  mounted: function () {
    truckService.getTruck(this.$route.params.id).then((response) => {
      //handle response
      if (response.status) {
        this.addForm.id = response.data.id;
        this.addForm.company_name = response.data.company_name;
        this.addForm.truck_number = response.data.truck_number;
        this.addForm.chaase_number = response.data.chaase_number;
        this.addForm.insurance_number =
          response.data.vehicle_insurance.insurance_number;
        this.addForm.total_killometer = response.data.killometer;
        this.addForm.capacity = response.data.capacity;
        this.addForm.document = response.data.document;
        this.addForm.insurance_document = response.data.insurance_document;
        this.addForm.is_active = response.data.status;
        this.date = new Date(response.data.vehicle_insurance.insurance_date)
          .toISOString()
          .substr(0, 10);
        this.date1 = new Date(response.data.vehicle_insurance.insurance_expiry)
          .toISOString()
          .substr(0, 10);
        if (response.data.document) {
          this.rc = this.imgUrl + response.data.document;
        }
        if (response.data.insurance_document) {
          this.insurancedocument =
            this.imgUrl + response.data.insurance_document;
        }
      } else {
        const currentUser = authenticationService.currentUserValue;
        if (currentUser.data.user.role_id == 1) {
          router.push("/admin/skidsteers");
        } else {
          router.push("/manager/skidsteers");
        }
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile1: function (error, file) {
      this.addForm.document = file.serverId;
      this.rc = this.imgUrl + file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function (file) {
      this.addForm.document = "";
      this.docError = true;
      this.rc = "";
    },
    handleProcessFile2: function (error, file) {
      this.addForm.insurance_document = file.serverId;
      this.insurancedocument = this.imgUrl + file.serverId;
      this.insdocError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile2: function (file) {
      this.addForm.insurance_document = "";
      this.insdocError = true;
      this.insurancedocument = "";
    },
    save: function (e) {
      //stop page to reload
      e.preventDefault();

      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress.",
          type: "error",
          position: "top-right",
        });
        return false;
      }
      if (this.addForm.document == "") {
        this.docError = true;
      }
      if (this.addForm.insurance_document == "") {
        this.insdocError = true;
      }
      this.addForm.insurance_date = this.date;
      this.addForm.insurance_expiry = this.date1;
      if (this.$refs.form.validate() && !this.insdocError && !this.docError) {
        if (this.loading) {
          return false;
        }
        //start loader
        this.loading = true;

        truckService.edit(this.addForm).then((response) => {
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
            const currentUser = authenticationService.currentUserValue;
            if (currentUser.data.user.role_id == 1) {
              router.push("/admin/skidsteers");
            } else {
              router.push("/manager/skidsteers");
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