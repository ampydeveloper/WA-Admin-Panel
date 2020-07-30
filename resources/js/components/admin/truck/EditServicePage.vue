<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12">
          <h4 class="main-title mb-0">Edit Truck Service</h4>
        </v-col>

        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="save">
            <v-row>
              <v-col cols="12" md="12">
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
                        label="Service Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        required
                        :rules="[v => !!v || 'Service date is required']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu2 = false"></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.service_killometer"
                    label="Total miles"
                    required
                    :rules="killometerRules"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-textarea
                    rows="3"
                    label="Service Note"
                    max-lenght="2000"
                    auto-grow
                    v-model="addForm.note"
                  ></v-textarea>
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
                    v-on:processfilerevert="handleRemoveFile1"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png, video/mp4, video/mov"
                  />
                  <div class="v-messages theme--light error--text" role="alert" v-if="docError">
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">Service image upload is required</div>
                    </div>
                  </div>
                  <div v-if="documentImg" style="height:200px; width:200px">
                    <img :src="documentImg" alt="John" style="height:200px;" />
                  </div>
                </v-col>

                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Upload Receipt"
                    v-bind:allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:addfilestart="setUploadIndex"
                    v-on:processfile="handleProcessFile2"
                    v-on:processfilerevert="handleRemoveFile2"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                  />
                  <div class="v-messages theme--light error--text" role="alert" v-if="insdocError">
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">Receipt document upload is required</div>
                    </div>
                  </div>
                  <div v-if="receiptImg" style="height:200px; width:200px">
                    <img :src="receiptImg" alt="John" style="height:200px;" />
                  </div>
                </v-col>
              </v-col>

              <v-col cols="12" md="12">
                <v-btn type="submit" :loading="loading" :disabled="loading" color="success" class="mr-4 custom-save-btn ml-4 mt-4" @click="save">Submit</v-btn>
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
import { truckService } from "../../../_services/truck.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  data() {
    return {
      loading: false,
      docError: false,
      insdocError: false,
      menu2: false,
      menu1: false,
      valid: true,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      receiptImg: null,
      documentImg: null,
      date: "",
      uploadInProgress: false,
      setDate: new Date().toISOString().substr(0, 10),
      user_image: "",
      addForm: {
        vehicle_id: "",
        service_date: "",
        service_killometer: "",
        receipt: "",
        document: "",
        note: ""
      },
      killometerRules: [
        v => !!v || "Truck miles is required",
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
  mounted: function() {
    truckService.getTruckSingleService(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        this.addForm.id = response.data.id;
        this.addForm.receipt = response.data.receipt;
        this.addForm.document = response.data.document;
        this.addForm.note = response.data.note;
        this.addForm.vehicle_id = response.data.vehicle_id;
        this.date = new Date(response.data.service_date)
          .toISOString()
          .substr(0, 10);
        this.addForm.service_killometer = response.data.service_killometer;
        this.receiptImg = this.imgUrl + response.data.receipt;
        this.documentImg = this.imgUrl + response.data.document;
      }
    });
  },
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile1: function(error, file) {
      this.addForm.document = file.serverId;
      this.documentImg = this.imgUrl + file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function(file) {
      this.addForm.document = "";
      this.docError = true;
      this.documentImg = "";
    },
    handleProcessFile2: function(error, file) {
      this.addForm.receipt = file.serverId;
      this.insdocError = false;
      this.receiptImg = this.imgUrl + file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile2: function(file) {
      this.addForm.receipt = "";
      this.insdocError = true;
      this.receiptImg = "";
    },
    save: function(e) {
      //stop page to reload
      e.preventDefault();

      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      }
      if (this.addForm.document == "") {
        this.docError = true;
      }
      if (this.addForm.receipt == "") {
        this.insdocError = true;
      }
      this.addForm.service_date = this.date;
      if (this.$refs.form.validate() && !this.insdocError && !this.docError) {
        if(this.loading) {
          return false;
        }
        //start loading
        this.loading = true;

        truckService.updateTruckService(this.addForm).then(response => {
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
            const currentUser = authenticationService.currentUserValue;
            if (currentUser.data.user.role_id == 1) {
              const url = "/admin/truck/service/" + this.addForm.vehicle_id;
              router.push(url);
            } else {
              const url = "/manager/truck/service/" + this.addForm.vehicle_id;
              router.push(url);
            }
          } else {
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
