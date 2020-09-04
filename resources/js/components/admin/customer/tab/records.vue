<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col sm="12" cols="12" class="cust-record-content">
          <div class="single-record">
            <div class="s-r-inner">
              <h2 class="record-price">${{ (records.monthamount)?records.monthamount:'0' }}</h2>
              <span class="record-timeline">Last 12 Months</span>
            </div>
          </div>

          <div class="single-record">
            <div class="s-r-inner">
              <h2 class="record-price">${{ (records.allamount)?records.allamount:'0'}}</h2>
              <span class="record-timeline">Life Time</span>
            </div>
          </div>

          <div class="single-record">
            <div class="s-r-inner">
              <h2 class="record-price">{{ (records.farmrecord)?records.farmrecord:'0'}}</h2>
              <span class="record-timeline">Total Farms</span>
            </div>
          </div>

          <div class="single-record">
            <div class="s-r-inner">
              <h2 class="record-price">{{ (records.totaljobs)?records.totaljobs:'0'}}</h2>
              <span class="record-timeline">Total Jobs</span>
            </div>
          </div>

          <div class="single-record">
            <div class="s-r-inner">
              <h2 class="record-price">{{records.memebersince | formatDate}}</h2>
              <span class="record-timeline">Member Since</span>
            </div>
          </div>
        </v-col>

        <v-col sm="12" cols="12">
          <table id="c-records-table" class="table table-striped table-bordered table-main">
            <thead>
              <tr>
                <th>Job ID</th>
                <th>Farm Location</th>
                <th>Start Date</th>
                <th>Start Time</th>
                <th>Tech</th>
                <th>Price</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="record in jobs">
                <td>{{record.id}}</td>
                <td>{{record.farm.farm_address}} {{record.farm.farm_unit}} {{record.farm.farm_city}} {{record.farm.farm_province}} {{record.farm.farm_zipcode}}</td>
                <td>{{record.created_at | formatDate}}</td>
                <td>{{record.time_slots_id}}</td>
                <td>
                  <template v-if="record.truck_driver_id">Truck driver name</template>
                  <template v-if="!record.truck_driver_id">Not Assigned Yet</template>
                  <template v-if="record.skidsteer_driver_id">Skidsteer driver name</template>
                  <template v-if="!record.skidsteer_driver_id">Not Assigned Yet</template>
                </td>
                <td>${{record.job_amount}}</td>
                <td v-if="!record.repeating_job">Scheduled</td>
                <td v-if="record.repeating_job">Rescheduled</td>
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
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { customerService } from "../../../../_services/customer.service";
import { router } from "../../../../_helpers/router";
import {
  PlusCircleIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from "vue-feather-icons";
export default {
  components: {
    PlusCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
  },
  data() {
    return {
      records: "",
      jobs: "",
    };
  },

  mounted() {
    this.getResult();
  },
  methods: {
    getResult() {
      customerService
        .getCustomerRecord(this.$route.params.id)
        .then((response) => {
          //handle response
          if (response.status) {
            this.records = response.data;
            this.jobs = response.data.jobs;
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
              aTargets: [-1, -2, -3, -4, -5, -6],
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
        $(".dataTables_filter input").attr("placeholder", "Search");
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