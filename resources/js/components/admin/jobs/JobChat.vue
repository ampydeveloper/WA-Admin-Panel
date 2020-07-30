<template>
  <v-app>
    <v-container fluid>
      <v-row>
     	 <v-col sm="12" cols="12">
		Job_id: {{job.id}} <br>   Service Request: {{service.service_name}}<br>   Service Date: {{job.start_date}} <br>Price: ${{job.job_amount}}
<br>Manager Name: {{manager.first_name}} <br>Email Address: {{manager.email}} <br>Phone Number {{manager.phone}}<br> Farm Address: {{farm.farm_address}} {{farm.farm_unit}} {{farm.farm_city}} {{farm.farm_province}} {{farm.farm_zipcode}}
         </v-col>

         <v-col sm="12" cols="12">
          <div class="chat-area">
            <div class="chat-sender mb-6">
              <div class="chat-img">
              </div>
              <div class="chat-msg">
                Lorem ipsum dolor sit ametLorem ipsum dolor sit ametLorem ipsum dolor sit ametLorem ipsum dolor sit amet
              </div>
            </div>
            <div class="chat-receiver mb-6">
              <div class="chat-msg">
                Lorem ipsum dolor sit amet
              </div>
              <div class="chat-img">
              </div>
            </div>
            <div class="chat-receiver mb-6">
              <div class="chat-msg">
                Lorem ipsum dolor sit amet
              </div>
              <div class="chat-img">
              </div>
            </div>
          </div>
         </v-col>

          <v-col sm="12" cols="12" class="p-0">
            <div class="type_msg">
              <div class="input_msg_write">
                <input type="text" class="write_msg" placeholder="Type a message" />
                <button class="msg_send_btn" type="button">
                  <send-icon size="1.5x" class="custom-class"></send-icon>
                </button>
              </div>
            </div>
          </v-col>

      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { router } from "../../../_helpers/router";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
import { PlusCircleIcon } from "vue-feather-icons";
import { SendIcon } from 'vue-feather-icons'
export default {
  components: {
    PlusCircleIcon,
    SendIcon
  },
  data() {
    return {
      job:'',
      service:'',
      manager:'',
      farm: '',
    };
  },
  created() {
  
  },
   mounted() {
    this.getResults();
   },
    methods: {
     getResults() {
      jobService.singleJob(this.$route.params.id).then(response => {
        //handle response
        if (response.status) {
          this.job = response.data;
	this.service = response.data.service;
	this.manager = response.data.manager;
	this.farm = response.data.farm;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right"
          });
        }
      });
    }
},
updated() {
setTimeout(function() {
     $(document).ready(function() {
	    $('#example').DataTable();
	} );
  }, 1000);
    }
};
</script>
