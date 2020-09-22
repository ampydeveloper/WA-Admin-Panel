<template>
  <v-app>
    <v-container fluid class="pt-0">
      <v-row>
        <div class="bread_crum">
          <ul>
            <li>
              <h4 class="main-title text-left top_heading">
                Update Skidsteers
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
              <router-link to="/admin/trucks">
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
            <li>Update</li>
          </ul>
        </div>

        <div class="main_box">
          <v-container fluid>
            <v-form
              ref="form"
              v-model="valid"
              class="v-form custom_form_field divide-50"
              id="form_field"
              lazy-validation
              @submit="save"
            >
              <input type="hidden" name="vehicle_type" value="1" />
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
                      <label>Total Miles</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.killometer"
                        required
                        label="Enter Total Miles"
                        placeholder
                        :rules="killometerRules"
                      ></v-text-field>
                    </v-col>
                  </div>
                </v-col>

                <v-col cols="6" md="6" class="pl-0 pt-0 manager-cols">
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0 image-upload-label">
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
                        v-on:processfile="handleProcessFile1"
                        v-on:processfilerevert="handleRemoveFile1"
                        allow-file-type-validation="true"
                        accepted-file-types="image/jpeg, image/png"
                      />
                      <div class="v-messages theme--light error--text" role="alert" v-if="docError">
                        <div class="v-messages__wrapper">
                          <div class="v-messages__message">RC document is required.</div>
                        </div>
                      </div>
                      <div class="service-image-outer" v-if="rc">
                        <button type="button" class="close" v-if="rc_cross" @click="RemoveRc()">
                          <span>&times;</span>
                        </button>
                        <img :src="rc" alt />
                      </div>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="4" class="label-align pt-0">
                      <label class="label_text label-check-half">Availabilty</label>
                    </v-col>
                    <v-col sm="8" class="pt-0 pb-0">
                      <v-switch v-model="addForm.is_active"></v-switch>
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
  components: {
    //      'image-component': imageVUE,
  },

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
      rc_cross: false,
      ins_cross: false,
      insurancedocument: null,
      date: "",
      date1: "",
      setDate: new Date().toISOString().substr(0, 10),
      user_image: "",
      addForm: {
        vehicle_type: 1,
        company_name: "",
        truck_number: "",
        chaase_number: "",
        killometer: "",
        capacity: "",
        rc_document: "",
        is_active: "",
      },
      killometerRules: [
        (v) => !!v || "Skidsteer Miles are required.",
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
        this.addForm.vehicle_id = response.data.id;
        this.addForm.company_name = response.data.company_name;
        this.addForm.truck_number = response.data.truck_number;
        this.addForm.chaase_number = response.data.chaase_number;
        this.addForm.killometer = response.data.killometer;
        this.addForm.capacity = response.data.capacity;
        this.addForm.rc_document = response.data.document;
        this.addForm.is_active = response.data.status;
        
        
        if (response.data.document) {
          this.rc_cross = true;
          this.rc = this.imgUrl + response.data.document;
        }
        if (response.data.vehicle_insurance.document) {
          this.ins_cross = true;
          this.insurancedocument =
            this.imgUrl + response.data.vehicle_insurance.document;
        }
      } else {
        router.push("/admin/trucks");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
  methods: {
    RemoveRc() {
      this.rc = "";
      this.rc_cross = false;
      this.addForm.rc_document = "";
    },
    handleProcessFile1: function (error, file) {
      this.addForm.document = file.serverId;
      this.rc = this.imgUrl + file.serverId;
      this.docError = false;
    },
    handleRemoveFile1: function (file) {
      this.addForm.document = "";
      this.rc = "";
      this.docError = true;
    },
    save: function (e) {
      //stop page to reload
      e.preventDefault();

      if (this.addForm.document == "") {
        this.docError = true;
      }
      
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