<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">Job Activity Report</h4>
        </li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="12" id="manager_wrap" class="main-box-inner">
            <table
              id="hauler-table"
              class="table table-striped table-bordered table-main"
            >
              <thead>
                <tr>
                  <th class="text-left">Pickup#</th>
                  <th class="text-left">Job Date</th>
                  <th class="text-left">Customer</th>
                  <th class="text-left">Activity</th>
                  <th class="text-left">Time of Activity</th>
                  <th class="text-left">Techs</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(report, index) in reportData">
                  <td>#PICKUP100{{ report.id }}</td>
                  <td>
                    {{ report.job_providing_date | formatDateLic }}
                  </td>
                  <td>
                    {{
                      report.customer.first_name +
                      " " +
                      report.customer.last_name
                    }}
                  </td>
                  <td>Job Created</td>
                  <td>09/08/2020 09:56 am</td>
                  <td>
                    {{
                      report.truck_driver ? report.truck_driver.first_name : ""
                    }}
                    {{
                      report.truck_driver
                        ? report.truck_driver.last_name + ", "
                        : ""
                    }}
                    {{
                      report.skidsteer_driver
                        ? report.skidsteer_driver.first_name
                        : ""
                    }}
                    {{
                      report.skidsteer_driver
                        ? report.skidsteer_driver.last_name
                        : ""
                    }}
                  </td>
                </tr>
              </tbody>
            </table>
          </v-col>
        </v-row>
      </v-container>
    </div>
    <!-- <span id="table-chevron-left" class="d-none">
      <chevron-left-icon size="1.5x" class="custom-class"></chevron-left-icon>
    </span>
    <span id="table-chevron-right" class="d-none">
      <chevron-right-icon size="1.5x" class="custom-class"></chevron-right-icon>
    </span>
    <span id="search-input-icon" class="d-none">
      <span class="search-input-outer">
        <search-icon size="1.5x" class="custom-class"></search-icon>
      </span>
    </span> -->
  </v-app>
</template>

<script>
// import { required } from "vuelidate/lib/validators";/
import { jobService } from "../../../_services/job.service";
import { authenticationService } from "../../../_services/authentication.service";
import { environment } from "../../../config/test.env";
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  SearchIcon,
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";

export default {
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
    SearchIcon,
  },
  data() {
    return {
      reportData: [],
      isAdmin: true,
      imgUrl: environment.imgUrl,
    };
  },
  getList() {},
  mounted() {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    this.getResults();
  },
  methods: {
    getResults() {
      jobService
        .getReport({
          type: this.$route.query.type,
          report_of: this.$route.query.number,
        })
        .then((response) => {
          //handle response
          if (response.status) {
            this.reportData = response.data;
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
       $(".table-main").css({ opacity: 1 });
  //     $(document).ready(function () {
  //       if (!$.fn.dataTable.isDataTable(".table-main")) {
  //         $(".table-main").DataTable({
  //           aoColumnDefs: [
  //             {
  //               bSortable: false,
  //               aTargets: [-1, -2, -3, -4],
  //             },
  //           ],
  //           oLanguage: {
  //             sSearch: "",
  //             sEmptyTable: "No reports till now.",
  //             infoEmpty: "No reports found.",
  //           },
  //           drawCallback: function (settings) {
  //             $(".dataTables_paginate .paginate_button.previous").html(
  //               $("#table-chevron-left").html()
  //             );
  //             $(".dataTables_paginate .paginate_button.next").html(
  //               $("#table-chevron-right").html()
  //             );
  //           },
  //         });
  //         $(".dataTables_filter").append($("#search-input-icon").html());
  //         $(".dataTables_filter input").attr("placeholder", "Search Activity");
  //         $(".dataTables_paginate .paginate_button.previous").html(
  //           $("#table-chevron-left").html()
  //         );
  //         $(".dataTables_paginate .paginate_button.next").html(
  //           $("#table-chevron-right").html()
  //         );
  //       }
  //       $(".table-main").css({ opacity: 1 });
  //     });
    }, 1000);
  },
};
</script>