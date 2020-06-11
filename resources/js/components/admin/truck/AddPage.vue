<template>
  <v-app>
    <v-container>
      <v-row>
<h2>Add New Truck</h2>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="8" md="8">
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.company_name"
                    :rules="[v => !!v || 'Company name is required']"
                    label="Company Name"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.truck_number"
                    :rules="[v => !!v || 'Truck number is required']"
                    label="Truck Number"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.chaase_number"
                    :rules="[v => !!v || 'Chassis number is required']"
                    label="Chassis Number"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.insurance_number"
                    :rules="[v => !!v || 'Insurance number is required']"
                    label="Insurance Number"
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
                        label="Insurance Date"
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
                <v-col cols="12" md="12">
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
                        label="Insurance expiry date"
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

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.total_killometer"
                    label="Total Kilometer"
                    required
                    :rules="killometerRules"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Upload RC"
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:processfile="handleProcessFile1"
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
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:processfile="handleProcessFile2"
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
                <v-btn color="success" class="mr-4" @click="save">Submit</v-btn>
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
export default {
  components: {
    //      'image-component': imageVUE,
  },

  data() {
    return {
      docError: false,
      insdocError: false,
      menu2: false,
      menu1: false,
      valid: true,
      apiUrl: environment.apiUrl,
      avatar: null,
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
        insurance_expiry: "",
	is_active: true
      },
     killometerRules: [
        v => !!v || "Truck kilometer is required",
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
    handleProcessFile1: function(error, file) {
      this.addForm.document = file.serverId;
      this.docError = false;
    },
    handleProcessFile2: function(error, file) {
      this.addForm.insurance_document = file.serverId;
      this.insdocError = false;
    },
    save() {
   	if(this.addForm.document == ''){
		this.docError = true;
	}
        if(this.addForm.insurance_document == ''){
		this.insdocError = true;
	}
      this.addForm.insurance_date = this.date;
      this.addForm.insurance_expiry = this.date1;
      if (this.$refs.form.validate() && (!this.insdocError) && (!this.docError)) {
        truckService.add(this.addForm).then(response => {
         //handle response
         if(response.status) {
             this.$toast.open({
               message: response.message,
               type: 'success',
               position: 'top-right'
             });
          //redirect to login
          router.push("/admin/trucks");
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
