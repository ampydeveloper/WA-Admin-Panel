<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="4" md="4" class="job_info main_box">
          <div class="mb-5">
            <div class="clearfix">
              <span class="basic-grey-label-half">Pickup ID</span>
              <span class="det-half">#PICKUP100{{ job.id }}</span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Service</span>
              <span class="det-half">{{ service.service_name }}</span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Service Date</span>
              <span class="det-half"
                >{{ job.start_date | formatDateLic }}
              </span>
            </div>
          </div>

          <div class="">
            <div class="clearfix">
              <span class="basic-grey-label-half">Images</span>
              <span class="det-half">
                <template v-for="(image, index) in jobImages">
                  <img :src="image" class="dis-chat-img" />
                </template>
              </span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Primary Manager</span>
              <span class="det-half"
                >{{ manager.first_name }} {{ manager.last_name }}</span
              >
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Manager Contact</span>
              <span class="det-half"
                >{{ manager.email }} / {{ manager.phone }}</span
              >
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Farm Location</span>
              <span class="det-half">
                {{ farm.farm_address }} {{ farm.farm_unit }}
                {{ farm.farm_city }} {{ farm.farm_province }}
                {{ farm.farm_zipcode }}</span
              >
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Techs</span>
              <span class="det-half">
                <span v-if="job.skidsteer_driver"
                  >{{
                    job.skidsteer_driver.first_name +
                    " " +
                    job.skidsteer_driver.last_name +
                    ", "
                  }}
                </span>
                <span v-if="job.skidsteer_driver">
                  {{
                    job.truck_driver.first_name +
                    " " +
                    job.truck_driver.last_name
                  }}
                </span>
              </span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Note</span>
              <span class="det-half">{{ job.notes }}</span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Overhead Cost</span>
              <span class="det-half"
                >${{ job.overhead_cost ? job.overhead_cost : "0" }}</span
              >
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Total</span>
              <span class="det-half">${{ job.amount ? job.amount : "0" }}</span>
            </div>
            
          </div>
        </v-col>

        <v-col cols="8" md="8" class="job-map-box">
          <div class="main_box"><div id="map" class="contain"></div></div>
        </v-col>

        <v-col cols="12" md="12" class="main_box chat-area-outer">
          <div class="chat-area" id="message-container">
            <div class="empty-message">
              <p>
                Lets start conversing <br />
                & <br />
                find solutions.
              </p>
            </div>
            <div class="uploading-image-out">
              <loader-icon size="1.5x" class="custom-class"></loader-icon>
              <p>Uploading image</p>
            </div>
          </div>
        </v-col>

        <v-col cols="12" md="12" class="main_box chat-form-outer">
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="hidden" id="user-details-id" :value="userdata.id" />
              <input type="hidden" id="job-id" :value="job.id" />
              <input type="hidden" id="job-lat" :value="farm.latitude" />
              <input type="hidden" id="job-long" :value="farm.longitude" />
              <input
                type="hidden"
                id="current-user-image"
                :value="baseUrl + userdata.user_image"
              />
              <input
                type="hidden"
                id="all-user-data"
                :value="JSON.stringify(chatUsers)"
              />

              <input
                type="file"
                id="image-file"
                style="display: none"
                name="chat-image"
              />
              <label class="upload-images-out" for="image-file">
                <image-icon size="1.5x" class="custom-class"></image-icon>
              </label>
              <form id="send-container" autocomplete="off">
                <input
                  type="text"
                  class="write_msg"
                  placeholder="Type your message"
                  id="message-input"
                />
                <button class="msg_send_btn" id="send-button" type="submit">
                  <send-icon size="1.5x" class="custom-class"></send-icon>
                </button>
              </form>
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
import { SendIcon, ImageIcon, LoaderIcon } from "vue-feather-icons";
import { authenticationService } from "../../../_services/authentication.service";

export default {
  components: {
    SendIcon,
    ImageIcon,
    LoaderIcon,
  },
  data() {
    return {
      imageUploadFired: false,
      job: "",
      service: "",
      manager: "",
      farm: "",
      jobImages: "",
      userdata: "",
      chatUsers: "",
      baseUrl: environment.baseUrl,
      skip:0,
    };
  },
  created() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    this.userdata = currentUser.data.user;
  },
  mounted() {
    this.getResults();

    // setTimeout(() => {
    //   this.getChatMessages();
    // }, 10000);
    // setTimeout(() => {
    //   this.getChatMembers();
    // }, 1000);

    let socketScript = document.createElement("script");
    socketScript.setAttribute(
      "src",
      "https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.1/socket.io.js"
    );
    document.head.appendChild(socketScript);
    let scrollScript = document.createElement("script");
    scrollScript.setAttribute(
      "src",
      "https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.12.0/js/OverlayScrollbars.min.js"
    );
    document.head.appendChild(scrollScript);
    let scrollScript2 = document.createElement("link");
    scrollScript2.setAttribute(
      "href",
      "https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.12.0/css/OverlayScrollbars.min.css"
    );
    scrollScript2.setAttribute("rel", "stylesheet");
    document.head.appendChild(scrollScript2);

    var wellOffice = "26.695145,-80.244859";
    var icons = {
      start: new google.maps.MarkerImage(
        "http://wa.customer.leagueofclicks.com/img/car-marker2.png",
        new google.maps.Size(72, 100),
        new google.maps.Point(0, 0),
        new google.maps.Point(22, 32)
      ),
      end: new google.maps.MarkerImage(
        "http://wa.customer.leagueofclicks.com/img/map-icon2.png",
        new google.maps.Size(55, 55),
        new google.maps.Point(0, 0),
        new google.maps.Point(22, 32)
      ),
    };
    var map;
    function initMap() {
      const directionsService = new google.maps.DirectionsService();
      const directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
      });
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: 41.85, lng: -87.65 },
        mapTypeControl: false,
        scaleControl: false,
        scrollwheel: false,
        navigationControl: false,
        streetViewControl: false,
        styles: [
          {
            featureType: "landscape.natural",
            elementType: "all",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "landscape.natural",
            elementType: "labels",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.text",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "all",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "labels",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "all",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.text.stroke",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit",
            elementType: "all",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "geometry.fill",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "labels.text.fill",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.bus",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.rail",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "geometry.fill",
            stylers: [
              {
                color: "#e2f6fe",
              },
            ],
          },
        ],
      });
      directionsRenderer.setMap(map);

      calculateAndDisplayRoute(directionsService, directionsRenderer);
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
      const jobLat = document.getElementById("job-lat")._value;
      const joblong = document.getElementById("job-long")._value;
      directionsService.route(
        {
          origin: {
            query: wellOffice,
          },
          destination: {
            query: jobLat + "," + joblong,
          },
          travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
          if (status === "OK") {
            directionsRenderer.setDirections(response);
            var leg = response.routes[0].legs[0];
            makeMarker(leg.start_location, icons.start, "Wellington Office");
            makeMarker(leg.end_location, icons.end, "Farm");
          }
        }
      );
    }
    function makeMarker(position, icon, title) {
      new google.maps.Marker({
        position: position,
        map: map,
        icon: icon,
        title: title,
      });
    }
    setTimeout(function () {
      initMap();
    }, 6000);
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
          this.jobImages = JSON.parse(response.data.images);
          this.getChatMembers();
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    getChatMembers() {
      jobService.chatUsers(this.$route.params.id).then((response) => {
        if (response.status) {
          var users = [];
          users[response.data.customer_id] = response.data.customer;
          users[response.data.manager_id] = response.data.manager;
          users[response.data.skidsteer_driver_id] =
            response.data.skidsteer_driver;
          users[response.data.truck_driver_id] = response.data.truck_driver;

          response.data.admin.forEach(function (val, index) {
            users[val.id] = val;
          });

          response.data.admin_manager.forEach(function (val, index) {
            users[val.id] = val;
          });

          this.chatUsers = users;
          this.getChatMessages();
        }
      });
    },
    getChatMessages() {
      const chatUsersList = this.chatUsers;
      const currentUserDetails = this.userdata;
      jobService
        .getJobChatMessages({ jobId: this.$route.params.id, skip: this.skip })
        .then((response) => {
          if (response) {
            response.data.forEach(function (val, index) {
              if (typeof val.username != "undefined") {
                if (typeof chatUsersList[val.username] != "undefined") {
                  var userImageLink = chatUsersList[val.username].user_image;
                } else {
                  var userImageLink =
                    environment.baseUrl + "/images/avatar.png";
                }
                var messageText;
                if (val.message.indexOf("uploads") > -1) {
                  messageText =
                    '<img class="chat-image-in" src="' +
                    `${val.message}` +
                    '">';
                  var imageClass = "inc-img";
                } else {
                  messageText = val.message;
                  var imageClass = "";
                }
                const messageElement = document.createElement("div");
                if (currentUserDetails.id == val.username) {
                  messageElement.className = "chat-receiver";
                } else {
                  messageElement.className = "chat-sender";
                }
                messageElement.innerHTML =
                  '<div class="chat-msg ' +
                  imageClass +
                  '">' +
                  `${messageText}` +
                  '</div><div class="chat-img"><img src="' +
                  `${userImageLink}` +
                  '"></div>';
                $(document)
                  .find("#message-container")
                  .find(".os-content")
                  .prepend(messageElement);
                $("#message-container .empty-message").remove();
              }
            });
          }
        });
    },
  },
  updated() {
    const self = this;
    var messageContainerScroll;
    setTimeout(function () {
      messageContainerScroll = OverlayScrollbars(
        document.querySelectorAll("#message-container"),
        {
          callbacks: {
            onScrollStop: (e) =>{
              if(e.target.scrollTop < 10){
                self.skip += 10;
                self.getChatMessages();
              }
            }
          }
        }
      );

      const socket = io.connect("https://wa.customer.leagueofclicks.com:3100", {
        secure: true,
      });
      const messageContainer = document.getElementById("message-container");
      const messageForm = document.getElementById("send-container");
      const messageInput = document.getElementById("message-input");
      const name = document.getElementById("user-details-id");
      const jobId = document.getElementById("job-id");
      const allUserData = document.getElementById("all-user-data");

      socket.emit("new-user", name._value);
      messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });

      const chatUsersList = JSON.parse(allUserData.value);
      const emitChannel = "chat-message"; //"chatmessage"+jobId
      socket.on(emitChannel, (data) => {
        const userImage = $("#current-user-image").val();
        var messageText;
        if (data.message.message.indexOf("uploads") > -1) {
          messageText =
            '<img class="chat-image-in" src="' +
            `${data.message.message}` +
            '">';
          var imageClass = "inc-img";
        } else {
          messageText = data.message.message;
          var imageClass = "";
        }
        if (data.job_id == jobId._value) {
          if (data.name == name._value) {
            if (
              $("." + data.message_id).length == 0 &&
              $("." + data.message.check_string).length == 0
            ) {
              appendMessage(
                '<div class="chat-msg ' +
                  data.message_id +
                  " " +
                  imageClass +
                  '">' +
                  `${messageText}` +
                  '</div><div class="chat-img"><img src="' +
                  `${userImage}` +
                  '"></div>',
                "chat-receiver"
              );
            }
          } else {
            if (typeof chatUsersList[data.name] != "undefined") {
              var userImageLink = chatUsersList[data.name].user_image;
            } else {
              var userImageLink = environment.baseUrl + "/images/avatar.png";
            }
            if ($("." + data.message_id).length == 0) {
              appendMessage(
                '<div class="chat-msg ' +
                  data.message_id + " " + imageClass +
                  '">' +
                  `${messageText}` +
                  '</div><div class="chat-img"><img src="' +
                  `${userImageLink}` +
                  '"></div>',
                "chat-sender"
              );
            }
          }
          messageContainerScroll.scroll([0, "100%"], 50, {
            x: "",
            y: "linear",
          });
        }
      });
      socket.on("receive-driver-coordinates", (data) => {
        console.log(data);
      });
      $(document).on("submit", "#send-container", function (e) {
        const message = messageInput.value;
        const userImage = $("#current-user-image").val();
        const check_string = Math.random().toString(36).substring(3);
        if (message != "") {
          appendMessage(
            '<div class="chat-msg '+check_string+'">' +
              `${message}` +
              '</div><div class="chat-img"><img src="' +
              `${userImage}` +
              '"></div>',
            "chat-receiver"
          );
          socket.emit("send-chat-message", {
            message: message,
            job_id: jobId._value,
            username: name._value,
            check_string: check_string,
          });
          messageInput.value = "";
          $("#send-button").attr("disabled", false);
          messageContainerScroll.scroll([0, "100%"], 50, {
            x: "",
            y: "linear",
          });
        }
        e.preventDefault();
      });
      function appendMessage(message, className) {
        const messageElement = document.createElement("div");
        messageElement.className = className; //"chat-receiver"
        messageElement.innerHTML = message;
        $(document)
          .find("#message-container")
          .find(".os-content")
          .prepend(messageElement);
        $("#message-container .empty-message").remove();
      }

      $(document).on("change", "#image-file", function (e) {
        var $this = $(this);
        if ($this.val() != "" && !self.fired) {
          self.fired = true;
          const currentUser = authenticationService.currentUserValue || {};
          const userImage = $("#current-user-image").val();
          var imageData = new FormData();
          imageData.append("uploadImage", $("#image-file").prop("files")[0]);
          $.ajax({
            url: environment.apiUrl + `uploadImage`,
            headers: {
              Authorization: "Bearer " + currentUser.data.access_token,
            },
            data: imageData,
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            success: function (result) {
              var check_string = Math.random().toString(36).substring(3);
              const messageElement = document.createElement("div");
              messageElement.className = "chat-receiver"; //"chat-receiver"
              messageElement.innerHTML =
                '<div class="chat-msg inc-img '+check_string+'"><img class="chat-image-in" src="' +
                `${environment.baseUrl + result}` +
                '"></div><div class="chat-img"><img src="' +
                `${userImage}` +
                '"></div>';
              $(document)
                .find("#message-container")
                .find(".os-content")
                .prepend(messageElement);
                
              socket.emit("send-chat-message", {
                message: environment.baseUrl + result,
                job_id: jobId._value,
                username: name._value,
                check_string: check_string,
              });
              messageContainerScroll.scroll([0, "100%"], 50, {
                x: "",
                y: "linear",
              });
            },
            complete: function () {
              $("#image-file").val("");
              self.fired = false;
            },
          });
        }
      });
    }, 5000);
  },
};
</script>
<style>
#map {
  width: 100%;
  height: 350px;
}
</style>