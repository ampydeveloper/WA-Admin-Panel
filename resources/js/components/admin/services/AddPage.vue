<template>
  <v-app>
    <v-container>
      <v-row>
        <h4 class="main-title">Add Service</h4>

        <v-col cols="12" md="12" class="pl-0">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-col cols="12" md="12">
              <v-text-field
                v-model="addForm.service_name"
                label="Service name"
                required
		            :rules="[v => !!v || 'Service name is required']">
              </v-text-field>
            </v-col>
	          <v-col cols="12" md="12">
             <header>Service Time Period</header>
	           <v-radio-group  row v-model="addForm.slot_type" @change="getTime()" :mandatory="false" required :rules="[v => !!v || 'Service time period is required']">
              <v-radio label="Morning" value="1" ></v-radio>
              <v-radio label="Afternoon" value="2"></v-radio>
	           </v-radio-group>
	          </v-col>

	          <v-col class="time-slots pt-0" cols="12" md="12" v-if="timeSlots.length">
              <template v-for="(timeSlot, index) in timeSlots">
              <span class="checkbox" v-bind:class="[addForm.slot_time.includes(timeSlot.id) ? 'activeClass' : '']">
                <input 
                type="checkbox"
                @click="setTimeSlot(timeSlot.id, index)"
                :value="timeSlot.id"
                :id="timeSlot.id"><label v-bind:for="timeSlot.id">{{timeSlot.slot_start+'-'+timeSlot.slot_end}}</label>
              </span>
              <!-- <v-checkbox v-model="addForm.slot_time" :value="timeSlot.id" class="mx-2" :label="timeSlot.slot_start+'-'+timeSlot.slot_end"></v-checkbox> -->
              </template>
	          </v-col>
            <v-col cols="12" md="12">
              <v-text-field
                type="number"
                max="100"
                min="0"
                v-model="addForm.price"
                :rules="priceRules"
                label="Service Price"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12" class="textarea-parent">
              <v-textarea rows="1"
                auto-grow
                clearable
                clear-icon="cancel"
                v-model="addForm.description"
                :rules="descriptionRules"
                label="Description"
                required
              ></v-textarea>
            </v-col>
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

		<v-col cols="12" md="12">
             <header>Service Rate</header>
	   <v-radio-group  row v-model="addForm.service_rate"  :mandatory="false" required :rules="[v => !!v || 'Service rate is required']">
	      <v-radio label="Per Load" value="1" ></v-radio>
	      <v-radio label="Round" value="2"></v-radio>
	    </v-radio-group>
	</v-col>
            <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4" @click="save">Add Service</v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { jobService } from "../../../_services/job.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";

export default {
  components: {
    //      'image-component': imageVUE,
  },
  data() {
    return {
      valid: true,
      avatar: null,
      apiUrl: environment.apiUrl,
      addForm: {
        service_name: "",
        price: "",
        description: "",
        service_image: "",
        service_rate: '1',
	      slot_type: '1',
        slot_time:[],
      },
      timeSlots: [],
      priceRules: [
        v => !!v || "Service price is invalid/required"
      ],
      descriptionRules: [v => !!v || "Service description is required"],
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
  mounted: function() {
    this.getTime();
  },
  methods: {
    getTime(){
      //make previous selection blank if tab changed
      if(this.timeSlots.length > 0) {
        this.addForm.slot_time = [];
      }
      this.timeSlots = [];
     jobService.getTimeSlots(this.addForm.slot_type).then(response => {
          //handle response
          if (response.status) {
            this.timeSlots = response.data; 
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
    },
    //set time slow
    setTimeSlot(timeSlotId, index){
      var findIndex = this.addForm.slot_time.indexOf(timeSlotId);
      if(findIndex > -1) {
        this.addForm.slot_time.splice(findIndex, 1);
      } else {
        this.addForm.slot_time.push(timeSlotId);
      }
    },
    handleProcessFile: function(error, file) {
      this.addForm.service_image = file.serverId;
    },
    save() {
      if (this.$refs.form.validate()) {
        jobService.add(this.addForm).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            router.push("/admin/services");
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
