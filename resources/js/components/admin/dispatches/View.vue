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
              <mapbox
                access-token="pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA"
                :map-options="{
                style: 'mapbox://styles/mapbox/light-v9',
                center: [-80.2853179, 26.6094155],
                zoom: 3,
              }"
                :geolocate-control="{
                show: true,
                position: 'top-left',
              }"
                :scale-control="{
                show: true,
                position: 'top-left',
              }"
                :fullscreen-control="{
                show: true,
                position: 'top-left',
              }"
              />
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
