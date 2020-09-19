<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Edit Insurance
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
          <router-link to="/admin/skidsteers">
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
        <li>Add</li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="12" class="pl-0 pt-0 slide-left">
            <v-form
              ref="form"
              v-model="valid"
              lazy-validation
              @submit="save"
              class="custom_form_field"
              id="form_field"
            >
              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Insurance Number</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-text-field
                    v-model="addForm.insurance_number"
                    label="Enter Insurance Number"
                    required
                    :rules="[v => !!v || 'Insurance Number is required.']"
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Insurance Date</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
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
                        v-model="date"
                        label="Select Insurance Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        required
                        :rules="[v => !!v || 'Insurance Date is required.']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu1 = false"></v-date-picker>
                  </v-menu>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Insurance Expiry Date</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
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
                        v-model="date1"
                        label="Select Insurance Expiry Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        required
                        :rules="[v => !!v || 'Insurance Expiry Date is required.']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date1" @input="menu2 = false" :min="setDate"></v-date-picker>
                  </v-menu>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0 image-upload-label">
                  <label class="label_text">Insurance Document</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
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
                      <div class="v-messages__message">Insurance upload is required.</div>
                    </div>
                  </div>
                  <div class="service-image-outer" v-if="documentImg">
                    <button
                      type="button"
                      class="close"
                      v-if="documentCross"
                      @click="documentRemove()"
                    >
                      <span>&times;</span>
                    </button>
                    <img :src="documentImg" alt />
                  </div>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Notes</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-textarea
                    rows="3"
                    label="Enter Notes"
                    max-lenght="2000"
                    auto-grow
                    v-model="addForm.notes"
                  ></v-textarea>
                </v-col>
              </v-col>

              <!-- <v-col cols="12" md="12">
                <v-btn
                  type="submit"
                  :loading="loading"
                  :disabled="loading"
                  color="success"
                  class="mr-4 custom-save-btn ml-4 mt-4"
                  @click="save"
                >Update Skidsteer Insurance Detail</v-btn>
              </v-col>-->

              <v-col class="pt-0 pb-0" cols="12" md="12">
                <v-row class="m-0">
                  <v-col sm="2"></v-col>
                  <v-col sm="10" class="pt-0 pb-0">
                    <v-btn
                      type="submit"
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      class="custom-save-btn mt-4"
                      @click="save"
                      id="submit_btn"
                    >Update</v-btn>

                    <router-link to="/admin/skidsteers" class="btn-custom-danger mt-4">Cancel</router-link>
                  </v-col>
                </v-row>
              </v-col>
            </v-form>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { truckService } from "../../../_services/truck.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";
import moment from "moment";
export default {
  data() {
    return {
      loading: false,
      docError: false,
      menu2: false,
      menu1: false,
      valid: true,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      date: "",
      date1: "",
      documentImg: null,
      documentCross: false,
      setDate: new Date().toISOString().substr(0, 10),
      addForm: {
        vehicle_id: "",
        insurance_number: "",
        insurance_date: "",
        insurance_expiry: "",
        insurance_document: "",
        notes: "",
      },
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
    truckService
      .getTruckSingleInsurance(this.$route.params.id)
      .then((response) => {
        //handle response
        if (response.status) {
          this.addForm.vehicle_insurance_id = response.data.id;
          this.addForm.vehicle_id = response.data.vehicle_id;
          this.addForm.insurance_document = response.data.document;
          this.addForm.notes = response.data.notes;
          this.date = moment(response.data.insurance_date)
            .format("YYYY-MM-DD");

          this.date1 = moment(response.data.insurance_expiry)
            .format("YYYY-MM-DD");
          this.addForm.insurance_number = response.data.insurance_number;
          if (response.data.document) {
            this.documentImg = this.imgUrl + response.data.document;
            this.documentCross = true;
          }
        }
      });
  },
  methods: {
    documentRemove() {
      this.documentCross = false;
      this.documentImg = "";
      this.addForm.insurance_document = "";
    },
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile1: function (error, file) {
      this.addForm.insurance_document = file.serverId;
      this.documentImg = this.imgUrl + file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function (file) {
      this.addForm.insurance_document = "";
      this.docError = true;
      this.documentImg = "";
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
      if (this.addForm.insurance_document == "") {
        this.docError = true;
      }

      this.addForm.insurance_date = this.date;
      this.addForm.insurance_expiry = this.date1;
      if (this.$refs.form.validate() && !this.docError) {
        if (this.loading) {
          return false;
        }
        //start loading
        this.loading = true;

        truckService.updateInsurance(this.addForm).then((response) => {
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
              const url = "/admin/skidsteer/service/" + this.addForm.vehicle_id;
              router.push(url);
            } else {
              const url = "/manager/skidsteer/service/" + this.addForm.vehicle_id;
              router.push(url);
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
