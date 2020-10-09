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
          <v-col cols="7" md="7">
            <div class="dispatch-top">
              <div class="form-group">
                <label>Date</label>
                <span>21/06/2020</span>
              </div>
              <h4 class="main-title pl-0"></h4>
            </div>
            <table id="dispatch-table" class="table table-striped table-bordered table-main">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Job ID</th>
                  <th>Price</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Service</th>
                  <th>Driver</th>
                  <th>Edit</th>
                  <th>View Detail</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(job, index) in alljobs">
                  <td>{{index+1}}</td>
                  <td>{{job.id}}</td>
                  <td>{{job.job_amount}}</td>
                  <td>{{job.start_date}}</td>
                  <td>{{job.start_time}}</td>
                  <td>{{job.service.service_name}}</td>
                  <td>{{job.truck_driver == null ? job.truck_driver_id:job.truck_driver.first_name}}</td>
                  <td>Edit Job</td>
                  <td>View Map</td>
                </tr>
              </tbody>
            </table>
          </v-col>
          <v-col cols="5" md="5" class="pr-0">
            <div id="app">
            
              <div id='map' class='contain'> </div>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-app>
</template>
<script>
import Mapbox from "mapbox-gl-vue";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
import mapboxgl from "mapbox-gl";

export default {
  components: { Mapbox },
  data() {
    return {
      alljobs: [],
    };
  },
  created() {},
  mounted() {
    this.getResults();
    mapboxgl.accessToken = 'pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA';
      var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/light-v9',
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
        var url = 'https://api.mapbox.com/directions/v5/mapbox/cycling/' + start[0] + ',' + start[1] + ';' + end[0] + ',' + end[1] + '?steps=true&geometries=geojson&access_token=' + mapboxgl.accessToken;

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
            console.log(geojson);
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
                    coordinates: geojson
                  }
                }
              },
              layout: {
                'line-join': 'round',
                'line-cap': 'round'
              },
              paint: {
                'line-color': '#3887be',
                'line-width': 5,
                'line-opacity': 0.75
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
        getRoute(start, [-122.61365699963287, 45.51773726437733]);

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
            'circle-radius': 10,
            'circle-color': '#f30'
          }
        });

        getRoute(start, [-122.61365699963287, 45.51773726437733]);

      });
      window.lo = 45.523751;
      window.setInterval(function () {
        // reqest to get new cordinates
        // request.open('GET', url, true);
        // request.onload = function () {
        //   if (this.status >= 200 && this.status < 400) {
        //     // retrieve the JSON from the response
        //     var json = JSON.parse(this.response);
            
        //     // update the drone symbol's location on the map
            
        //   }
        // };
        // request.send();
        lo = lo - .0001
        start = [-122.662453, lo];
        
        map.getSource('truck').setData({
              type: 'Feature',
              properties: {},
              geometry: {
                type: 'Point',
                coordinates: start
              }
          });
        map.flyTo({
          center: start,
          speed: 0.5
        });
      getRoute(start, [-122.61365699963287, 45.51773726437733]);

      }, 2000);

  },
  methods: {
    getResults() {
      jobService.dispatchJobList().then((response) => {
        //handle response
        if (response.status) {
          this.alljobs = response.data;

          console.log(this.alljobs);
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
      $(document).ready(function () {
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
        $(".dataTables_filter input").attr("placeholder", "Search Services");
        $(".dataTables_paginate .paginate_button.previous").html(
          $("#table-chevron-left").html()
        );
        $(".dataTables_paginate .paginate_button.next").html(
          $("#table-chevron-right").html()
        );
        $(".table-main").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>
<style>
#map {
  width: 100%;
  height: 500px;
}
</style>
