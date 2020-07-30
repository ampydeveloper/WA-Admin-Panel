<template>
  <v-app>
    <v-container fluid>
      <v-row>
<h4 class="main-title text-left ml-6">Add New Truck</h4>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="save">
            <v-row>
              <v-col cols="12" md="12">
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Company Name</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
                    <v-text-field
                      v-model="addForm.company_name"
                      :rules="[v => !!v || 'Company name is required']"
                      required
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Truck Number</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
                    <v-text-field
                      v-model="addForm.truck_number"
                      :rules="[v => !!v || 'Truck number is required']"
                      required
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Chassis Number</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
                    <v-text-field
                      v-model="addForm.chaase_number"
                      :rules="[v => !!v || 'Chassis number is required']"
                      required
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Insurance Number</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
                    <v-text-field
                      v-model="addForm.insurance_number"
                      :rules="[v => !!v || 'Insurance number is required']"
                      required
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Insurance Date</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
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
                          required
                        :rules="[v => !!v || 'Insurance date is required']"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="date" @input="menu2 = false"></v-date-picker>
                    </v-menu>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Insurance expiry date</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
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
                          prepend-icon="event"
                          readonly
                          v-on="on"
                          required
                        :rules="[v => !!v || 'Insurance expiry date is required']"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="date1" @input="menu1 = false" :min="setDate"></v-date-picker>
                    </v-menu>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Total Miles</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
                    <v-text-field
                      v-model="addForm.total_killometer"
                      required
                      :rules="killometerRules"
                    ></v-text-field>
                  </v-col>
                </v-col>
		            <v-col cols="12" md="12" class="pt-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Truck Capacity</label>
                  </v-col>
                  <v-col sm="4" class="pt-0">
                    <v-text-field
                    type="number"
                      v-model="addForm.capacity"
                      required
                      :rules="[v => !!v || 'Truck capacity is required']"
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Upload RC"
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
                <div class="v-messages__wrapper"><div class="v-messages__message">RC document upload is required</div></div>
                </div>
                </v-col>
                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Upload Insurance"
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
		<div class="v-messages__wrapper"><div class="v-messages__message">Insurance document upload is required</div></div>
		</div>
                </v-col>
		  <v-col cols="12" md="12">
		 <v-switch
		      v-model="addForm.is_active"
		      label="Truck Available"
		    ></v-switch>
		</v-col>
              </v-col>
		
              <v-col cols="12" md="12">
                <v-btn type="submit"
                  :loading="loading"
                  :disabled="loading" color="success" class="mr-4 custom-save-btn" @click="save">Submit</v-btn>
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
      avatar: null,
      uploadInProgress:false,
      date: "",
      date1: "",
      setDate:new Date().toISOString().substr(0, 10),
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
        capacity: '',
        insurance_expiry: "",
	is_active: true
      },
     killometerRules: [
        v => !!v || "Truck Miles is required",
        v => /^\d*$/.test(v) || "Enter valid number",
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
  created() {},
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile1: function(error, file) {
      this.addForm.document = file.serverId;
      this.docError = false;
     this.uploadInProgress = false;
    },
    handleRemoveFile1: function(file){
      this.addForm.document = '';
      this.docError = true;
    },
    handleProcessFile2: function(error, file) {
      this.addForm.insurance_document = file.serverId;
      this.insdocError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile2: function(file){
      this.addForm.insurance_document = '';
       this.insdocError = true;
    },
    save: function(e) {
      //stop page to reload
      e.preventDefault();

   	if(this.addForm.document == ''){
		this.docError = true;
	}
        if(this.addForm.insurance_document == ''){
		this.insdocError = true;
	}
      if(this.uploadInProgress) {
        this.$toast.open({
              message: "Image uploading is in progress!",
              type: "error",
              position: "top-right"
            });
            return false;
      }
      this.addForm.insurance_date = this.date;
      this.addForm.insurance_expiry = this.date1;
      if (this.$refs.form.validate() && (!this.insdocError) && (!this.docError)) {
        if(this.loading) {
          return false;
        }
        //start loader
        this.loading = true;
        truckService.add(this.addForm).then(response => {
          //stop loader
          this.loading = false;
         //handle response
         if(response.status) {
             this.$toast.open({
               message: response.message,
               type: 'success',
               position: 'top-right'
             });
          //redirect to login
	    const currentUser = authenticationService.currentUserValue;
	    if(currentUser.data.user.role_id == 1){
          	router.push("/admin/trucks");
	    }else{
          	router.push("/manager/trucks");
	    }

         } else {
             this.$toast.open({
               message: response.message,
               type: 'error',
               position: 'top-right'
             })
         }
       });
      }
    }
  }
};
</script>
