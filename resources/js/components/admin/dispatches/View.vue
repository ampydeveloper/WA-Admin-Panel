<template>
  <v-app class="dispatcher-area">
    <v-container fluid class="pr-0">
      <v-row>
        <v-col cols="7" md="7">
          <div class="dispatch-top">
            <div class="form-group">
              <label>Date</label>
              <span>21/06/2020</span>
            </div>
            <h4 class="main-title pl-0">All Jobs</h4>
          </div>
          <table id="table" class="table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>Sno</th>
                <th>Job_id</th>
                <th>Price</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Service</th>
                <th>Driver Name</th>
                <th>Edit</th>
                <th>View Detail</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(job, index) in alljobs">
                <td>00{{index+1}}00</td>
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
      alljobs: []
    };
  },
  created() {},
  mounted() {
    this.getResults();
  },
  methods: {
    getResults() {
      jobService.dispatchJobList().then(response => {
        //handle response
        if (response.status) {
          this.alljobs = response.data;

          console.log(this.alljobs);
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
        $("#table").DataTable();
      });
    }, 1000);
  }
};
</script>
<style>
#map {
  width: 100%;
  height: 500px;
}
</style>
