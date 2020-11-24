<template>
  <v-app class="dispatcher-area">
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            Dispatch
            <span class="right-bor"></span>
          </h4>
        </li>
        <li>
          <router-link to="/admin/dashboard" class="home_svg">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-home h-5 w-5 mb-1 stroke-current text-primary"
            >
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16px"
                height="16px"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-chevrons-right w-4 h-4"
              >
                <polyline points="13 17 18 12 13 7" />
                <polyline points="6 17 11 12 6 7" />
              </svg>
            </span>
          </router-link>
        </li>
        <li>List</li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid class>
        <v-row>
          <v-col cols="12" md="12" class="">
            <div id="app">
              <div id="map" class="contain"></div>
            </div>
          </v-col>
          <v-col cols="12" md="12">
            <div class="dispatch-top">
              <div class="form-group">
                <label>Date</label>
                <span>21/06/2020</span>
              </div>
            </div>

            <table
              id="all-dispatch-table"
              class="table table-striped table-bordered table-main all_jobs"
            >
              <thead>
                <tr>
                  <th class="job-summ">Job Summary</th>
                  <th>Customer / Manager / Farm Location</th>
                  <th class="tech-col">Techs / Vehicles</th>
                  <th class="time-col">Est. Time</th>
                
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(job, index) in alljobs">
                  <td>
                    <span class="basic-info">{{
                      job.start_date | formatDateLic
                    }}</span>
                    <span class="basic-big">#JOB100{{ job.id }}</span>
                   
                    <span class="basic-grey">{{
                      job.service.service_name
                    }}</span>
                  </td>
                  <td>
                    <span class="basic-big">{{ job.customer.first_name }}</span>
                    <span class="basic-grey"
                      >{{ job.manager.first_name }} ({{
                        job.manager.email
                      }})</span
                    >
                    <span class="basic-grey"
                      >{{ job.manager.address }} {{ job.manager.city }}
                      {{ job.manager.state }} {{ job.manager.country }}
                      {{ job.manager.zip_code }}</span
                    >
                  </td>
                  <td class="job-col-body">
                    <span class="basic-grey-label-half">Truck Driver</span>
                    <v-select
                      :items="truck.drivers"
                      v-model="job.truck_driver_id"
                      class="graph-select-sl"
                      item-text="first_name"
                      item-value="id"
                      outlined
                      dense
                      @change="update($event, 'truckDriver', job.id)"
                    ></v-select>
                    <!-- <span class="basic-info-half" v-if="job.truck_driver">{{
                      job.truck_driver.first_name
                    }}</span> -->
                    <!-- <span class="basic-info-half" v-if="!job.truck_driver"
                      >Not Assigned</span
                    > -->
                    <div class="clearfix"></div>
                    <span class="basic-grey-label-half">Truck</span>
                    <v-select
                      :items="truck.vehicles"
                      v-model="job.truck_id"
                      class="graph-select-sl"
                      item-text="truck_number"
                      item-value="id"
                      outlined
                      dense
                      @change="update($event, 'truck', job.id)"
                    ></v-select>
                    <!-- <span class="basic-info-half" v-if="job.truck">{{
                      job.truck.truck_number
                    }}</span>
                    <span class="basic-info-half" v-if="!job.truck"
                      >Not Assigned</span
                    > -->
                    <div class="clearfix"></div>
                    <span class="basic-grey-label-half">Skidsteer Driver</span>
                    <v-select
                      :items="skidsteer.drivers"
                      v-model="job.skidsteer_driver_id"
                      class="graph-select-sl"
                      item-text="first_name"
                      item-value="id"
                      outlined
                      dense
                      @change="update($event, 'skidsteerDriver', job.id)"
                    ></v-select>
                    <!-- <span class="basic-info-half" v-if="job.skidsteer_driver">{{
                      job.skidsteer_driver.first_name
                    }}</span>
                    <span class="basic-info-half" v-if="!job.skidsteer_driver"
                      >Not Assigned</span
                    > -->
                    <div class="clearfix"></div>
                    <span class="basic-grey-label-half">Skidsteer</span>
                    <v-select
                      :items="skidsteer.vehicles"
                      v-model="job.skidsteer_id"
                      class="graph-select-sl"
                      item-text="truck_number"
                      item-value="id"
                      outlined
                      dense
                      @change="update($event, 'skidsteer', job.id)"
                    ></v-select>
                    <!-- <span class="basic-info-half" v-if="job.skidsteer">{{
                      job.skidsteer.truck_number
                    }}</span>
                    <span class="basic-info-half" v-if="!job.skidsteer"
                      >Not Assigned</span
                    > -->
                  </td>
                  <td class="job-col-body">
                    <span class="basic-grey-label-full">{{timeSlotsMapping[job.time_slots_id]}}</span>
                    <!-- <span class="basic-info-full">9:30 pm</span> -->
                  </td>
                 
                  <td>
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/jobs/chat/' + job.id"
                      class
                    >
                      <v-btn color="success" class="btn-outline-green"
                        >View chat</v-btn
                      >
                    </router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/jobs/chat/' + job.id"
                      class
                    >
                      <v-btn color="success" class="btn-outline-green"
                        >View chat</v-btn
                      >
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </v-col>
          
        </v-row>
      </v-container>
       <span id="table-chevron-left" class="d-none">
      <chevron-left-icon size="1.5x" class="custom-class"></chevron-left-icon>
    </span>
    <span id="table-chevron-right" class="d-none">
      <chevron-right-icon size="1.5x" class="custom-class"></chevron-right-icon>
    </span>
    <span id="search-input-icon" class="d-none">
      <span class="search-input-outer">
        <search-icon size="1.5x" class="custom-class"></search-icon>
      </span>
    </span>
    <span id="search-input-icon2" class="d-none">
      <span class="search-input-outer">
        <search-icon size="1.5x" class="custom-class"></search-icon>
      </span>
    </span>
    </div>
  </v-app>
</template>
<script>
// import Mapbox from "mapbox-gl-vue";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
// import mapboxgl from "mapbox-gl";
import { authenticationService } from "../../../_services/authentication.service";
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  SearchIcon,
} from "vue-feather-icons";
export default {
  components: { 
    // Mapbox,
  ChevronLeftIcon,
  ChevronRightIcon,
  SearchIcon, },
  data() {
    return {
      timeSlotsMapping:{
        1: 'Morning',
        2: 'Afternoon',
        3: 'Evening'
      },
      alljobs: [],
      alldispatch: [],
      selected:{
        driver:{
          truck: {},
          skidsteer: {}
        },
        vehicles:{
          truck: {},
          skidsteer: {}
        }
      },
      truck: {
        drivers: [],
        vehicles: []
      },
      skidsteer:{
        drivers: [],
        vehicles: []
      }
    };
  },
  created() {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }

    this.getResults();
    this.getDispatch();
    this.getDriverWVehicles();
  },
  mounted() {
    let scrollScript = document.createElement("script");
    scrollScript.setAttribute(
      "src",
      "https://api.tiles.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js"
    );
    document.head.appendChild(scrollScript);
    let scrollScript2 = document.createElement("link");
    scrollScript2.setAttribute(
      "href",
      "https://api.tiles.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css"
    );
    scrollScript2.setAttribute("rel", "stylesheet");
    document.head.appendChild(scrollScript2);

    mapboxgl.accessToken = 'pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA';
      var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/dark-v9',
        center: [-122.662323, 45.523751], // starting position
        zoom: 12
      });
      // set the bounds of the map
      var bounds = [[-123.069003, 45.395273], [-122.303707, 45.612333]];
      map.setMaxBounds(bounds);

      // initialize the map canvas to interact with later
      var canvas = map.getCanvasContainer();

      // an arbitrary start will always be the same
      // only the end or destination will change
      var start = [-122.662323, 45.523751];

      // create a function to make a directions request
      function getRoute(start, end) {
        // make a directions request using cycling profile
        // an arbitrary start will always be the same
        // only the end or destination will change
        var url = 'https://api.mapbox.com/directions/v5/mapbox/cycling/' + start[0] + ',' + start[1] + ';' + end[0] + ',' + end[1] + '?steps=true&geometries=geojson&access_token=pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA';

        console.log(url);
        // make an XHR request https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
        var req = new XMLHttpRequest();
        req.open('GET', url, true);
        req.onload = function() {
          var json = JSON.parse(req.response);
          var data = json.routes[0];
          var route = data.geometry.coordinates;
          var geojson = {
            type: 'Feature',
            properties: {},
            geometry: {
              type: 'LineString',
              coordinates: route
            }
          };
          // if the route already exists on the map, reset it using setData
          if (map.getSource('route')) {
            console.log('un');
            map.getSource('route').setData(geojson);
          } else { // otherwise, make a new request
            map.addLayer({
              id: 'route',
              type: 'line',
              source: {
                type: 'geojson',
                data: {
                  type: 'Feature',
                  properties: {},
                  geometry: {
                    type: 'LineString',
                    coordinates: [geojson]
                  }
                }
              },
              layout: {
                'line-join': 'round',
                'line-cap': 'round'
              },
              paint: {
                'line-color': '#f30',
                'line-width': 50,
                'line-opacity': 1
              }
            });
          }
          // add turn instructions here at the end
        };
        req.send();
      }

      // this is where the code for the next step will go
      map.on('load', function() {
        // make an initial directions request that
        // starts and ends at the same location
        console.log('here');
        // getRoute(start, [-122.61365699963287, 45.51773726437733]);

        map.addSource('truck', {
            type: 'geojson',
            data: {
            type: 'FeatureCollection',
            features: [{
              type: 'Feature',
              properties: {},
              geometry: {
                type: 'Point',
                coordinates: start
              }
            }]
          }
        });
        // Add starting point to the map
        map.addLayer({
          id: 'truck',
          type: 'symbol',
          source: 'truck',
          layout: {
            'icon-image': 'bus-15'
          }
        });
        // this is where the code from the next step will go

        map.addLayer({
          id: 'end',
          type: 'circle',
          source: {
            type: 'geojson',
            data: {
              type: 'FeatureCollection',
              features: [{
                type: 'Feature',
                properties: {},
                geometry: {
                  type: 'Point',
                  coordinates: [-122.61365699963287, 45.51773726437733]
                }
              }]
            }
          },
          paint: {
            'circle-radius': 5,
            'circle-color': '#f30'
          }
        });

        getRoute(start, [-122.61365699963287, 45.51773726437733]);

      });
      window.lo = 45.523751;
      // window.setInterval(function () {
      //   lo = lo - .0001
      //   start = [-122.662453, lo];

      //   map.getSource('truck').setData({
      //         type: 'Feature',
      //         properties: {},
      //         geometry: {
      //           type: 'Point',
      //           coordinates: start
      //         }
      //     });
      //   map.flyTo({
      //     center: start,
      //     speed: 0.5
      //   });
      // getRoute(start, [-122.61365699963287, 45.51773726437733]);

      // }, 2000);
  },
  methods: {
    getDriverWVehicles(){
      jobService.listDrivers().then((response) => {
        if (response.status) {
          // console.log(response.data);
          this.truck.drivers = response.data; // Pending - Need to apply condition, if driver_type = 1
          this.skidsteer.drivers = response.data; // Pending - Need to apply condition, if driver_type = 2
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
      jobService.listTrucks().then((response) => {
        if (response.status) {
          // console.log(response.data);
          this.truck.vehicles = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
      jobService.listSkidsteers().then((response) => {
        if (response.status) {
          // console.log(response.data);
          this.skidsteer.vehicles = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    getResults() {
      this.alljobs = [];
      jobService.dispatchAllJoblist().then((response) => {
        //handle response
        if (response.status) {
          // console.log(response.data);
          this.alljobs = response.data;
          // [...response.data].forEach((job) => {
          //   let did = 0;
          //   if(job.truck_driver.driver_type == 1){ //Truck Driver
          //     if(job.truck_driver){ did = job.truck_driver.id };
          //     this.selected.driver.truck[job.id] = did;
          //   }
          //   if(job.truck_driver.driver_type == 1){ // Skidsteer Driver, to be changed to 2 later
          //     if(job.truck_driver){ did = job.truck_driver.id };
          //     this.selected.driver.truck[job.id] = did;
          //   }
          // });
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    getDispatch() {
      // this.alljobs = [];
      // jobService.dispatchlist().then((response) => {
      //   //handle response
      //   if (response.status) {
      //     this.alldispatch = response.data;
      //     console.log(response.data);
      //   } else {
      //     this.$toast.open({
      //       message: response.message,
      //       type: "error",
      //       position: "top-right",
      //     });
      //   }
      // });
    },
    update(did, type, jid){
      jobService.update(type, did, jid).then((response) => {
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
    }
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable(".table-main")) {
        $(".table-main").DataTable({
          aoColumnDefs: [
            {
              bSortable: false,
              aTargets: [-1],
            },
          ],
          oLanguage: { sSearch: "" },
          drawCallback: function (settings) {
            $(".dataTables_paginate .paginate_button.previous").html(
              $("#table-chevron-left").html()
            );
            $(".dataTables_paginate .paginate_button.next").html(
              $("#table-chevron-right").html()
            );
          },
        });
          $(".dataTables_filter").append($("#search-input-icon").html());
        $(".dataTables_filter input").attr(
          "placeholder",
          "Search Jobs by Job ID / Customer / Service"
        );
        $(".dataTables_paginate .paginate_button.previous").html(
          $("#table-chevron-left").html()
        );
        $(".dataTables_paginate .paginate_button.next").html(
          $("#table-chevron-right").html()
        );
        $(".table-main").css({ opacity: 1 });
              }
      });
    }, 1000);
  },
};
</script>
<style>
#map {
  width: 100%;
  height: 500px;
  position: relative;
  float: left; 
}
</style>