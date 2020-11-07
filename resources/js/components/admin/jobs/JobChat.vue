<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="4" md="4" class="job_info main_box">
          <div class="mb-5">
            <div class="clearfix">
              <span class="basic-grey-label-half">Job ID</span>
              <span class="det-half">#JOB100{{ job.id }}</span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Service</span>
              <span class="det-half">{{ service.service_name }}</span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Price</span>
              <span class="det-half"> ${{ job.job_amount }}</span>
            </div>
            <div class="clearfix">
              <span class="basic-grey-label-half">Service Date</span>
              <span class="det-half">{{ job.start_date }} </span>
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
                <input
                  type="hidden"
                  id="current-user-image"
                  :value="baseUrl + userdata.user_image"
                />

                <span class="upload-images-out">
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
import Mapbox from "mapbox-gl-vue";
import mapboxgl from "mapbox-gl";

export default {
  components: {
    SendIcon,
    ImageIcon,
    LoaderIcon,
    Mapbox,
  },
  data() {
    return {
      job: "",
      service: "",
      manager: "",
      farm: "",
      userdata: "",
      chatUsers: "",
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

    // mapboxgl.accessToken =
    //   "pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA";
    // var map = new mapboxgl.Map({
    //   container: "map",
    //   style: "mapbox://styles/mapbox/light-v9",
    //   center: [-122.662323, 45.523751], // starting position
    //   zoom: 12,
    // });
    // // set the bounds of the map
    // var bounds = [
    //   [-123.069003, 45.395273],
    //   [-122.303707, 45.612333],
    // ];
    // map.setMaxBounds(bounds);

    // // initialize the map canvas to interact with later
    // var canvas = map.getCanvasContainer();

    // // an arbitrary start will always be the same
    // // only the end or destination will change
    // var start = [-122.662323, 45.523751];

    // // create a function to make a directions request
    // function getRoute(start, end) {
    //   // make a directions request using cycling profile
    //   // an arbitrary start will always be the same
    //   // only the end or destination will change
    //   var url =
    //     "https://api.mapbox.com/directions/v5/mapbox/cycling/" +
    //     start[0] +
    //     "," +
    //     start[1] +
    //     ";" +
    //     end[0] +
    //     "," +
    //     end[1] +
    //     "?steps=true&geometries=geojson&access_token=" +
    //     mapboxgl.accessToken;

    //   // make an XHR request https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
    //   var req = new XMLHttpRequest();
    //   req.open("GET", url, true);
    //   req.onload = function () {
    //     var json = JSON.parse(req.response);
    //     var data = json.routes[0];
    //     var route = data.geometry.coordinates;
    //     var geojson = {
    //       type: "Feature",
    //       properties: {},
    //       geometry: {
    //         type: "LineString",
    //         coordinates: route,
    //       },
    //     };
    //     // if the route already exists on the map, reset it using setData
    //     if (map.getSource("route")) {
    //       console.log(geojson);
    //       map.getSource("route").setData(geojson);
    //     } else {
    //       // otherwise, make a new request
    //       map.addLayer({
    //         id: "route",
    //         type: "line",
    //         source: {
    //           type: "geojson",
    //           data: {
    //             type: "Feature",
    //             properties: {},
    //             geometry: {
    //               type: "LineString",
    //               coordinates: geojson,
    //             },
    //           },
    //         },
    //         layout: {
    //           "line-join": "round",
    //           "line-cap": "round",
    //         },
    //         paint: {
    //           "line-color": "#3887be",
    //           "line-width": 5,
    //           "line-opacity": 0.75,
    //         },
    //       });
    //     }
    //     // add turn instructions here at the end
    //   };
    //   req.send();
    // }

    // // this is where the code for the next step will go
    // map.on("load", function () {
    //   // make an initial directions request that
    //   // starts and ends at the same location
    //   getRoute(start, [-122.61365699963287, 45.51773726437733]);

    //   map.addSource("truck", {
    //     type: "geojson",
    //     data: {
    //       type: "FeatureCollection",
    //       features: [
    //         {
    //           type: "Feature",
    //           properties: {},
    //           geometry: {
    //             type: "Point",
    //             coordinates: start,
    //           },
    //         },
    //       ],
    //     },
    //   });
    //   // Add starting point to the map
    //   map.addLayer({
    //     id: "truck",
    //     type: "symbol",
    //     source: "truck",
    //     layout: {
    //       "icon-image": "bus-15",
    //     },
    //   });
    //   // this is where the code from the next step will go

    //   map.addLayer({
    //     id: "end",
    //     type: "circle",
    //     source: {
    //       type: "geojson",
    //       data: {
    //         type: "FeatureCollection",
    //         features: [
    //           {
    //             type: "Feature",
    //             properties: {},
    //             geometry: {
    //               type: "Point",
    //               coordinates: [-122.61365699963287, 45.51773726437733],
    //             },
    //           },
    //         ],
    //       },
    //     },
    //     paint: {
    //       "circle-radius": 10,
    //       "circle-color": "#f30",
    //     },
    //   });

    //   getRoute(start, [-122.61365699963287, 45.51773726437733]);
    // });
    // window.lo = 45.523751;
    // window.setInterval(function () {
    //   // reqest to get new cordinates
    //   // request.open('GET', url, true);
    //   // request.onload = function () {
    //   //   if (this.status >= 200 && this.status < 400) {
    //   //     // retrieve the JSON from the response
    //   //     var json = JSON.parse(this.response);

    //   //     // update the drone symbol's location on the map

    //   //   }
    //   // };
    //   // request.send();
    //   lo = lo - 0.0001;
    //   start = [-122.662453, lo];

    //   map.getSource("truck").setData({
    //     type: "Feature",
    //     properties: {},
    //     geometry: {
    //       type: "Point",
    //       coordinates: start,
    //     },
    //   });
    //   map.flyTo({
    //     center: start,
    //     speed: 0.5,
    //   });
    //   getRoute(start, [-122.61365699963287, 45.51773726437733]);
    // }, 2000);
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
        messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });
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