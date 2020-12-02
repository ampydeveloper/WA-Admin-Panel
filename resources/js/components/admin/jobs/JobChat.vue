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
              <span class="basic-grey-label-half">Price</span>
              <span class="det-half">
                ${{ job.job_amount ? job.job_amount : "0" }}</span
              >
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
          </div>
        </v-col>

        <v-col cols="8" md="8" class="job-map-box">
          <div class="main_box"><div id="map" class="contain"></div></div>
        </v-col>

        <v-col cols="12" md="12" class="main_box chat-area-outer">
          <div class="chat-area" id="message-container">
            <!-- <div class="chat-receiver mb-6">
              <div class="chat-msg">Lorem ipsum dolor sit amet</div>
              <div class="chat-img"></div>
            </div> -->
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
            <form id="send-container" autocomplete="off">
              <div class="input_msg_write">
                <input
                  type="hidden"
                  id="user-details-id"
                  :value="userdata.id"
                />
                <input type="hidden" id="job-id" :value="job.id" />
                <input type="hidden" id="job-lat" :value="farm.latitude" />
                <input type="hidden" id="job-long" :value="farm.longitude" />
                <input
                  type="hidden"
                  id="current-user-image"
                  :value="baseUrl + userdata.user_image"
                />

                <span class="upload-images-out">
                  <!-- <input
                  type="file"
                  id="image-file"
                  name="chat-image"
                /> -->
                  <v-file-input
                    accept=".png,.jpg,.jpeg"
                    id="image-file"
                    v-model="chatUploadImage"
                     @click="chatImageUpload"
                  >
                  </v-file-input>
                  <image-icon size="1.5x" class="custom-class"></image-icon>
                </span>
                <input
                  type="text"
                  class="write_msg"
                  placeholder="Type your message"
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
import { SendIcon, ImageIcon, LoaderIcon } from "vue-feather-icons";
// import Mapbox from "mapbox-gl-vue";
// import mapboxgl from "mapbox-gl";

export default {
  components: {
    SendIcon,
    ImageIcon,
    LoaderIcon,
    // Mapbox,
  },
  data() {
    return {
      job: "",
      service: "",
      manager: "",
      farm: "",
      userdata: "",
      chatUsers: "",
      chatUploadImage: "",
      baseUrl: environment.baseUrl,
    };
  },
  created() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    this.userdata = currentUser.data.user;
  },
  mounted() {
    this.getResults();
    this.getChatMembers();

    setTimeout(() => {
      this.getChatMessages();
    }, 500);

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
        // The origin point (x,y)
        new google.maps.Point(0, 0),
        // The anchor point (x,y)
        new google.maps.Point(22, 32)
      ),
      end: new google.maps.MarkerImage(
        "http://wa.customer.leagueofclicks.com/img/map-icon2.png",
        new google.maps.Size(55, 55),
        // The origin point (x,y)
        new google.maps.Point(0, 0),
        // The anchor point (x,y)
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
        // draggable: false,
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
            query: jobLat + "," + joblong, // //"st louis, mo"
          },
          travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
          if (status === "OK") {
            directionsRenderer.setDirections(response);
            var leg = response.routes[0].legs[0];
            makeMarker(leg.start_location, icons.start, "Wellington Office");
            makeMarker(leg.end_location, icons.end, "Farm");
          } else {
            window.alert("Directions request failed due to " + status);
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
        //handle response
        if (response.status) {
          // this.job = response.data;
          console.log(response.data);
          var users = [];
          users[response.data.customer_id] = response.data.customer;
          users[response.data.manager_id] = response.data.manager;
          users[response.data.skidsteer_driver_id] =
            response.data.skidsteer_driver;
          users[response.data.truck_driver_id] = response.data.truck_driver;

          this.chatUsers = users;
        }
      });
    },
    getChatMessages() {
      //${this.chatUsers[val.username].user_image}
      jobService
        .getJobChatMessages({ jobId: this.$route.params.id })
        .then((response) => {
          if (response) {
            response.data.forEach(function (val, index) {
              const messageElement = document.createElement("div");
              messageElement.className = "chat-receiver";
              messageElement.innerHTML =
                '<div class="chat-msg">' +
                `${val.message}` +
                '</div><div class="chat-img"><img src="' +
                `${environment.baseUrl + "/images/avatar.png"}` +
                '"></div>';
              $(document).find("#message-container").prepend(messageElement);
              $("#message-container .empty-message").remove();
            });
          }
        });
    },
    chatImageUpload() {
      jobService
        .chatImageUpload({ uploadImage: this.chatUploadImage })
        .then((response) => {
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
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
    var messageContainerScroll;
    setTimeout(function () {
      messageContainerScroll = OverlayScrollbars(
        document.querySelectorAll("#message-container"),
        {}
      );

      const socket = io.connect("http://13.235.151.113:3100", { secure: true });
      const messageContainer = document.getElementById("message-container");
      const messageForm = document.getElementById("send-container");
      const messageInput = document.getElementById("message-input");
      const name = document.getElementById("user-details-id");
      const jobId = document.getElementById("job-id");

      socket.emit("new-user", name._value);
      messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });

      socket.on("chat-message", (data) => {
        const userImage = $("#current-user-image").val();
        if (data.job_id == jobId._value) {
          if (data.name == name._value) {
            appendMessage(
              '<div class="chat-msg">' +
                `${data.message.message}` +
                '</div><div class="chat-img"><img src="' +
                `${userImage}` +
                '"></div>'
            );
          } else {
            appendMessage(
              '<div class="chat-msg">' +
                `${data.message.message}` +
                '</div><div class="chat-img"><img src="' +
                `${environment.baseUrl + "/images/avatar.png"}` +
                '"></div>'
            );
          }
          messageContainerScroll.scroll([0, "100%"], 50, {
            x: "",
            y: "linear",
          });
        }
      });
      $(document).on("submit", "#send-container", function (e) {
        const message = messageInput.value;
        const userImage = $("#current-user-image").val();
        if (message != "") {
          appendMessage(
            '<div class="chat-msg">' +
              `${message}` +
              '</div><div class="chat-img"><img src="' +
              `${userImage}` +
              '"></div>'
          );
          socket.emit("send-chat-message", {
            message: message,
            job_id: jobId._value,
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
      function appendMessage(message) {
        const messageElement = document.createElement("div");
        messageElement.className = "chat-receiver";
        messageElement.innerHTML = message;
        $(document)
          .find("#message-container")
          .find(".os-content")
          .prepend(messageElement);
        $("#message-container .empty-message").remove();
      }
    }, 1000);
  },
};
</script>
<style>
#map {
  width: 100%;
  height: 350px;
}
</style>