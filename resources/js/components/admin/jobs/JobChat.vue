<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12" class="job_info pl-6 pr-6">
          Job_id: {{ job.id }} <br />
          Service Request: {{ service.service_name }}<br />
          Service Date: {{ job.start_date }} <br />Price: ${{ job.job_amount }}
          <br />Manager Name: {{ manager.first_name }} <br />Email Address:
          {{ manager.email }} <br />Phone Number {{ manager.phone }}<br />
          Farm Address: {{ farm.farm_address }} {{ farm.farm_unit }}
          {{ farm.farm_city }} {{ farm.farm_province }} {{ farm.farm_zipcode }}
        </v-col>

        <v-col cols="12" md="12" class="pl-6 mt-6 pr-6">
          <div class="chat-area" id="message-container">
            <div class="chat-sender mb-6">
              <div class="chat-msg">
                Lorem ipsum dolor sit ametLorem ipsum dolor sit ametLorem ipsum
                dolor sit ametLorem ipsum dolor sit amet
              </div>
            </div>
            <div class="chat-receiver mb-6">
              <div class="chat-msg">Lorem ipsum dolor sit amet</div>
              <div class="chat-img"></div>
            </div>
            <div class="chat-receiver mb-6">
              <div class="chat-msg">Lorem ipsum dolor sit amet</div>
              <div class="chat-img"></div>
            </div>
          </div>
        </v-col>

        <v-col cols="12" md="12" class="pl-6 mt-6 pr-6">
          <div class="type_msg">
            <form id="send-container" autocomplete="off">
            <div class="input_msg_write">
              <input
                type="text"
                class="write_msg"
                placeholder="Type a message"
                id="message-input"
              />
              <button class="msg_send_btn" id="send-button" type="submit">
                <send-icon size="1.5x" class="custom-class"></send-icon>
              </button>
            </div>
                  </form>
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
import { SendIcon } from "vue-feather-icons";
export default {
  components: {
    SendIcon,
  },
  data() {
    return {
      job: "",
      service: "",
      manager: "",
      farm: "",
    };
  },
  created() {},
  mounted() {
    this.getResults();
    let socketScript = document.createElement("script");
    socketScript.setAttribute(
      "src",
      "https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.1/socket.io.js"
    );
    document.head.appendChild(socketScript);
  },
  methods: {
    getResults() {
      jobService.singleJob(this.$route.params.id).then((response) => {
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
            position: "top-right",
          });
        }
      });
    },
  },
  updated() {
    setTimeout(function () {
      const socket = io.connect('http://13.235.151.113:3100', {secure: true});
      const messageContainer = document.getElementById("message-container");
      const messageForm = document.getElementById("send-container");
      const messageInput = document.getElementById("message-input");
      const name = "{{$username}}";
      const messageElement = document.createElement("div");
      messageElement.className = "new-user";
      messageElement.innerHTML =
        "You have joined SilkExotic public chat as " + name + ".";
      $("#message-container").append(messageElement);
      // messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });
      socket.emit("new-user", name);
      socket.on("chat-message", (data) => {
        if (data.name == name) {
          appendMessage(
            '<span class="each-username">' +
              `You:` +
              "</span> " +
              `${data.message}`
          );
        } else {
          appendMessage(
            '<span class="each-username">' +
              `${data.name}:` +
              "</span> " +
              `${data.message}`
          );
        }
        // messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });
      });
      $(document).on("submit", "#send-container", function (e) {
        const message = messageInput.value;
        if (message != "") {
          appendMessage(
            '<span class="each-username">' + `You:` + "</span> " + `${message}`
          );
          socket.emit("send-chat-message", message);
          messageInput.value = "";
          $("#send-button").attr("disabled", false);
          // messageContainerScroll.scroll([0, "100%"], 50, {
          //   x: "",
          //   y: "linear",
          // });
        }
        e.preventDefault();
      });
      function appendMessage(message) {
        const messageElement = document.createElement("div");
        messageElement.className = "each-message";
        messageElement.innerHTML = message;
        $(document)
          .find("#message-container")
          .append(messageElement);
      }
    }, 1000);
  },
};
</script>
<style>
#app .chat-area {
  border-top: 0px solid #ebebeb;
  padding-top: 15px;
}
#app .chat-area .chat-sender .chat-msg {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.12), 0 2px 4px 0 rgba(0, 0, 0, 0.08) !important;
  padding: 10px 14px;
  border-radius: 6px;
  background-color: #11b276;
  color: #fff;
  font-weight: 300;
}
#app .chat-area .chat-sender .chat-msg:before {
  border-right: 10px solid #11b276;
}

#app .chat-area .chat-receiver .chat-msg {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.12), 0 2px 4px 0 rgba(0, 0, 0, 0.08) !important;
  padding: 10px 14px;
  border-radius: 6px;
  background-color: #fff;
  color: #333;
  font-weight: 300;
}
#app .chat-area .chat-receiver .chat-msg:before {
  border-left: 10px solid #fff;
}
#app .type_msg {
  border-top: 0px solid #ebebeb;
  padding-top: 1px;
}
#app .type_msg .input_msg_write input {
  border-radius: 7px;
}
.job_info {
  font-weight: 300;
}
</style> 
