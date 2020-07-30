<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12" class="pl-0">
            <v-form ref="form" v-model="valid" lazy-validation>
              

                <v-col class="d-flex pt-0" cols="12">
                    <v-col sm="2" class="label-align pt-0">
                        <label>Select Farm</label>
                    </v-col>
                    <v-col sm="4" class="pt-0">
                        <v-select
                        :v-model="addForm.farm_id"
                        :items="farmName"
                        item-text="farm_address"
			label="Select Farm"
			:rules="[v => !!v || 'Farm Address is required']"
			item-value="id"
                        @change="getFarmManager"
                        ></v-select>
                    </v-col>
                </v-col>

                <v-col class="d-flex pt-0" cols="12" v-if="!addForm.manager_id">
                    <v-col sm="2" class="label-align pt-0">
                        <label>Manager Name</label>
                    </v-col>
                    <v-col sm="4" class="pt-0">
                        <v-select
                        :v-model="addForm.manager_id"
			:items="managerName"
			label="Select Manager"
			:rules="[v => !!v || 'Manager Name is required']"
			item-text="first_name"
			item-value="id"
                        @change="managerSelection"
                        ></v-select>
                    </v-col>
                </v-col>
                <v-col cols="12" class="textarea-parent d-flex pt-0">
                    <v-col sm="2" class="label-align pt-0">
                        <label>Job Description</label>
                    </v-col>
                    <v-col sm="4" class="pt-0">
                        <v-textarea rows="1"
                            auto-grow
                            clearable
                            clear-icon="cancel"
                            label=""
                            v-model="addForm.job_description"
                        ></v-textarea>
                    </v-col>
                </v-col>

<v-col cols="12" class="textarea-parent d-flex pt-0">
              <v-col sm="2" class="label-align pt-0">
                <label>Gate Number</label>
              </v-col>
              <v-col sm="4" class="pt-0">
                <v-text-field
                  label
                  v-model="addForm.gate_no"
                  required
                  :rules="[v => !!v || 'Gate number is required']"
                ></v-text-field>
              </v-col>
            </v-col>

                <v-col class="d-flex pt-0" cols="12">
                    <v-col sm="2" class="label-align pt-0">
                        <label>Service Name</label>
                    </v-col>
                    <v-col sm="4" class="pt-0">
                        <v-select
                        :v-model="addForm.service_id"
			:items="serviceName"
			label="Select Service"
			:rules="[v => !!v || 'Service Name is required']"
			item-text="service_name"
			item-value="id"
                        @change="serviceSelection"
                        ></v-select>
                    </v-col>
                </v-col>
                <v-col class="d-flex pt-0" cols="12" v-if="weightShow">
                    <v-col sm="2" class="label-align pt-0">
                        <label>Job Weight</label>
                    </v-col>
                    <v-col sm="4" class="pt-0">
                        <v-text-field
		            v-model="addForm.job_weight"
		            required
	                    type="number"
		            :rules="killometerRules"
		          ></v-text-field>
                    </v-col>
                </v-col>
                <div  id="showTimingSection" v-if="servicetime">
                <v-col cols="12" md="12" class="custom-col calendar-col pt-0">
                    <v-col sm="2" class="label-align pt-0">
                        <label>Start Date</label>
                    </v-col>
                    <v-col sm="4" class="pt-0">
                        <v-menu
                            v-model="menu1"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px">
                            <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="date"
                                label="Start Date"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                                required
                                :rules="[v => !!v || 'Start date is required']"
                            ></v-text-field>
                            </template>
                            <v-date-picker v-model="date" @input="menu1 = false" :min="setDate"></v-date-picker>
                        </v-menu>
                    </v-col>
                </v-col>
              
                <v-col cols="12" md="12" class="input-max pt-0" >
                   <v-col sm="2" class="label-align pt-0">
                        <label>Time Slots</label>
                    </v-col>
                 <v-col sm="10" class="pt-0">
                    <v-radio-group  row v-model="addForm.time_slots_id" :mandatory="false" required :rules="[v => !!v || 'Time slot is required']">
<template v-for="(timeSlot, index) in servicetime">
                    <v-radio :label="timeSlot.slot_start+'-'+timeSlot.slot_end" :value="timeSlot.id" ></v-radio>
                  
</template>
                    </v-radio-group>
	            </v-col>
 </v-col>
                </div>
  <v-col cols="12" md="12">
                    <file-pond
                name="uploadImage"
                ref="pond"
                label-idle="Drop files here..."
                allow-multiple="false"
                v-bind:server="serverOptions"
                v-bind:files="myFiles"
                v-on:processfile="handleProcessFile"
                allow-file-type-validation="true"
                accepted-file-types="image/jpeg, image/png"/>
                  </v-col>
                <v-btn color="success" :loading="loading" :disabled="loading" class="mr-4 custom-save-btn ml-4" @click="submit">Submit</v-btn>
            </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { router } from "../_helpers/router";
import { jobService } from "../_services/job.service";
import { environment } from "../config/test.env";
import { authenticationService } from "../_services/authentication.service";
export default {
  components: {
  },
  data() {
      return {
          valid: true,
          setDate:new Date().toISOString().substr(0, 10),
          menu1: false,
          loading: false,
	  weightShow: false,
          date: "",
          start_date: "",
          apiUrl: environment.apiUrl,
          managerName: [],
          serviceName: [],
          farmName:[],
          servicetime: '',
          customer_id: '',
          addForm: {
              customer_id: "",
              manager_id: "",
              service_id: "",
              job_description: "",
              gate_no: "",
              farm_add: "",
	      farm_id: "",
              job_images: [],
              time_slots_id: "",
              start_date: "",
	            job_weight:"",
              job_amount: "",
          },
          killometerRules: [
	    v => !!v || "Job weight is required",
	   //v => /^\d*$/.test(v) || "Enter valid weight",
         ],
         myFiles: [],
      }
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
  mounted() {
     this.addForm.service_id = this.$route.params.id;
     const currentUser = authenticationService.currentUserValue;
     if(currentUser.data.user.role_id == 5){
       this.addForm.customer_id = currentUser.data.user.created_by;
       this.addForm.manager_id = currentUser.data.user.id;
     }else{
       this.addForm.customer_id = currentUser.data.user.id;
     }
     this.getService();
     this.getCustomerFarm();
     this.serviceSelection();
   },
  methods: {
   handleProcessFile: function(error, file) {
     this.addForm.job_images.push(file.serverId);
    },
     getService() {
      jobService.listService().then(response => {
        //handle response
        if (response.status) {
          this.serviceName = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right"
          });
        }
      });
    },
    getCustomerFarm(){
	 jobService.getFarm(this.addForm.customer_id).then(response => {
	//handle response
	if (response.status) {
	 //console.log(response.data)
	 this.farmName = response.data;
	} else {
	  this.$toast.open({
	    message: response.message,
	    type: "error",
	    position: "top-right"
	  });
	}
      });
   },
	getFarmManager(val){
	 this.addForm.farm_id = val;
	 jobService.getFarmManager(val).then(response => {
		//handle response
		if (response.status) {
		 this.managerName = response.data;
		} else {
		  this.$toast.open({
		    message: response.message,
		    type: "error",
		    position: "top-right"
		  });
		}
	      });
	},
	managerSelection(val){
         this.addForm.manager_id = val;
       },
       serviceSelection(){
       console.log(this.serviceName)
       const val = this.addForm.service_id;
	this.serviceName.find(file => {
	  if (file.id == val) {
 	     this.addForm.job_amount = file.price;
           if(file.service_rate == 1){ 
	    this.weightShow = true;
	   }else{
           this.addForm.job_weight = "";
	   this.weightShow = false;
	   }  
	  }
	})

	 jobService.servicesTimeSlots(val).then(response => {
		//handle response
		if (response.status) {
		this.servicetime = response.data;
		} else {
		  this.$toast.open({
		    message: response.message,
		    type: "error",
		    position: "top-right"
		  });
		}
	      });
       },
      submit() {
        //start loading
        this.loading = true;
     this.addForm.start_date = this.date;
    
        if (this.$refs.form.validate()) {
        jobService.createJob(this.addForm).then(response => {
          //stop loading
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
	     router.push("/admin/jobs");
	    }else{
	     router.push("/manager/jobs");
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
    },
    showTiming(){
        console.log('abcd');
    }
  }
}
</script>
