<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col sm="12" cols="12">
          Job_id: {{job.id}}
          <br />
          Service Request: {{service.service_name}}
          <br />
          Service Date: {{job.start_date}}
          <br />
          Price: ${{job.job_amount}}
          <br />
          Manager Name: {{manager.first_name}}
          <br />
          Email Address: {{manager.email}}
          <br />
          Phone Number {{manager.phone}}
          <br />
          Farm Address: {{farm.farm_address}} {{farm.farm_unit}} {{farm.farm_city}} {{farm.farm_province}} {{farm.farm_zipcode}}
        </v-col>

        <!-- <v-col sm="12" cols="12">
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
        </v-col>-->

        <div class="col-md-8">
          <div class="card card-default">
            <div class="card-header">
              <h5>
                Simple Public Chat
                <small>({{users - 1}} users online)</small>
              </h5>
            </div>

            <div class="form-group">
              <div class="form-group p-2">
                <label for="nickname">Your nickname:</label>
                <input
                  type="text"
                  name="nickname"
                  v-model="nickname"
                  class="chat-input nickname"
                  required
                />
              </div>
              <textarea rows="12" readonly class="form-control">{{dataMessages.join('\n')}}</textarea>

              <div class="p-2 message_block">
                <input
                  type="text"
                  v-model="message"
                  placeholder="Your message"
                  required
                  class="chat-input"
                />
                <button @click="sendMessage" class="btn btn-primary">Send</button>
              </div>
            </div>
          </div>
          <div>{{ error }}</div>
        </div>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { router } from "../../../_helpers/router";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
import { chatService } from "../../../_services/chat.service";
import { PlusCircleIcon } from "vue-feather-icons";
import { SendIcon } from "vue-feather-icons";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon,
    SendIcon
  },
  data() {
    return {
      dataMessages: [],
      message: "",
      nickname: "",
      users: "",
      error: "",
      job: "",
      service: "",
      manager: "",
      farm: ""
    };
  },
  created() {},
  mounted() {
    const currentUser = authenticationService.currentUserValue;

    this.nickname = currentUser.data.user.first_name;
    //chat code 
    var socket = io.connect('http://localhost:3000');
            socket.on('userCount', function (data) {
                this.users = data.userCount;
            }.bind(this));
            socket.on("news-action:App\\Events\\ChatEvent", function(data){
		console.log(data);
                this.dataMessages.push(data.nickname + ' : ' + data.message);
            }.bind(this));
            //chat code
    this.getResults();
  },
  methods: {
    sendMessage: function() {
                if (this.message === "" || this.nickname === "") {
                    this.error = 'Type-in your nickname and message then kuldeep';
                } else {

		      chatService.send({message: this.message, nickname: this.nickname}).then((response) => {
			this.message = "";
			});

              
                }
            },
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
        $("#example").DataTable();
      });
    }, 1000);
  }
};
</script>
