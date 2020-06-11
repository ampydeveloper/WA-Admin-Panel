<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <h4 class="main-title">Edit Service</h4>
        </v-col>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-col cols="12" md="12">
              <v-text-field
                v-model="editForm.service_name"
                :rules="nameRules"
                label="Service Name"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="12">
              <header>Service Time Period</header>
              <v-radio-group
                row
                v-model="editForm.slot_type"
                @change="getTime()"
                :mandatory="false"
                required
                :rules="[v => !!v || 'Service time period is required']"
              >
                <v-radio label="Morning" value="morning"></v-radio>
                <v-radio label="Afternoon" value="afternoon"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col class="time-slots pt-0" cols="12" md="12" v-if="timeSlots.length">
              <template v-for="timeSlot in timeSlots">
              <span class="checkbox" v-bind:class="[editForm.slot_time.includes(timeSlot.id) ? 'activeClass' : '']">
                <input 
                type="checkbox"
                @click="setTimeSlot(timeSlot.id)"
                :value="timeSlot.id"
                :id="timeSlot.id"
                :checked="editForm.slot_time.includes(timeSlot.id) ? true:false">
                <label v-bind:for="timeSlot.id">{{timeSlot.slot_start+'-'+timeSlot.slot_end}}</label>
              </span>
               <!-- <v-checkbox v-model="editForm.slot_time" :value="timeSlot.id" class="mx-2" :label="timeSlot.slot_start+'-'+timeSlot.slot_end"></v-checkbox> -->
              </template>
            </v-col>
      
    
            <v-col cols="12" md="12">
              <v-text-field
                type="number"
                max="100"
                min="0"
                v-model="editForm.price"
                :rules="priceRules"
                label="Service Price"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-textarea rows="1"
                auto-grow
                clearable
                clear-icon="cancel"
                v-model="editForm.description"
                :rules="descRules"
                label="Description"
                required
              ></v-textarea>
            </v-col>
            <v-col cols="12" md="12">
              <div
                class="v-avatar v-list-item__avatar"
                style="height: 40px; min-width: 40px; width: 40px;"
              >
                <img :src="'../../../'+editForm.service_image" alt="John" />
              </div>
              <file-pond
                name="uploadImage"
                ref="pond"
                label-idle="Drop files here..."
                allow-multiple="false"
                v-bind:server="serverOptions"
                v-bind:files="myFiles"
                v-on:processfile="handleProcessFile"
                allow-file-type-validation="true"
                accepted-file-types="image/jpeg, image/png"
              />
            </v-col>

	<v-col cols="12" md="12">
             <header>Service Rate</header>
	   <v-radio-group  row v-model="editForm.service_rate"  :mandatory="false" required :rules="[v => !!v || 'Service rate is required']">
	      <v-radio label="per Load" value="perload" ></v-radio>
	      <v-radio label="Round" value="round"></v-radio>
	    </v-radio-group>
	</v-col>
            <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4" @click="update">Update</v-btn>
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
      editForm: {
        id: "",
        service_name: "",
        price: "",
        description: "",
        service_image: "",
	service_rate: "",
        slot_type: "",
        slot_time: []
      },
      timeSlots: [],
      nameRules: [v => !!v || "Service name is required"],
      priceRules: [v => !!v || "Service price is invalid/required"],
      descRules: [v => !!v || "Service description is required"],
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
  mounted: function() {
    jobService.getService(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        this.editForm.id = response.data.id;
        this.editForm.service_name = response.data.service_name;
        this.editForm.price = response.data.price;
        this.editForm.description = response.data.description;
        this.editForm.service_image = response.data.service_image;
        this.editForm.slot_time = JSON.parse(response.data.slot_time);
        if (response.data.slot_type == 1) {
          this.editForm.slot_type = "morning";
        } else {
          this.editForm.slot_type = "afternoon";
        }

       if (response.data.service_rate == 1) {
          this.editForm.service_rate = "perload";
        } else {
          this.editForm.service_rate = "round";
        }

        this.getTime();
      } else {
        router.push("/admin/services");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
    });
  },
  methods: {
    getTime() {
      //make previous selection blank if tab changed
      if(this.timeSlots.length > 0) {
        this.editForm.slot_time = [];
      }
      //make empty initially
      this.timeSlots = [];
      if (this.editForm.slot_type == "morning") {
        var type = 1;
      } else {
        var type = 2;
      }
      jobService.getTimeSlots(type).then(response => {
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
    setTimeSlot(timeSlotId){
      console.log(this.editForm.slot_time);
      var findIndex = this.editForm.slot_time.indexOf(timeSlotId);
      if(findIndex > -1) {
        this.editForm.slot_time.splice(findIndex, 1);
      } else {
        this.editForm.slot_time.push(timeSlotId);
      }
    },
    handleProcessFile: function(error, file) {
      this.editForm.service_image = file.serverId;
    },
    update() {
     if (this.editForm.slot_type == "morning") {
        this.editForm.slot_type = 1;
      } else {
        this.editForm.slot_type = 2;
      }
      if (this.editForm.service_rate == "perload") {
        this.editForm.service_rate = 1;
      } else {
        this.editForm.service_rate = 2;
      }
      if (this.$refs.form.validate()) {
        jobService.edit(this.editForm).then(response => {
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
