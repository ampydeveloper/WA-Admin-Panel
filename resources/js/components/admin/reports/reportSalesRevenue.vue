<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading" v-if="reportType == 1">
            Sales Revenue By Customer
          </h4>
          <h4 class="main-title text-left top_heading" v-if="reportType == 2">
            Sales Revenue By Tech
          </h4>
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
                  <th class="text-left">
                    Pickup# <br />
                    Date
                  </th>
                  <th class="text-left">Time <br />Labor</th>
                  <th class="text-left">
                    Status <br />
                    Expenses(B)
                  </th>
                  <th class="text-left">Total</th>
                  <th class="text-left">Service</th>
                  <th class="text-left" v-if="reportType == 2">Tech(s)</th>
                  <th class="text-left" v-if="reportType == 1">Customer</th>
                  <th class="text-left">Job Details</th>
                </tr>
              </thead>
              <tbody>
                <template v-for="(report, index) in reportData">
                  <!-- <tr>
                    <td colspan="7" class="report-customer">
                      {{ report.first_name + " " + report.last_name }}
                    </td>
                  </tr> -->
                  <template v-if="report.jobs">
                    <tr v-for="(job, index2) in report.jobs">
                      <td>
                        #PICKUP100{{ job.id }} <br />{{
                          job.job_providing_date | formatDateLic
                        }}
                      </td>
                      <td></td>
                      <td>InProgress <br />$0.00</td>
                      <td>${{ job.amount }}</td>
                      <td>{{ job.service ? job.service.service_name : "" }}</td>
                      <td v-if="reportType == 2">
                        {{
                          job.truck_driver ? job.truck_driver.first_name : ""
                        }}
                        {{
                          job.truck_driver
                            ? job.truck_driver.last_name + ", "
                            : ""
                        }}
                        {{
                          job.skidsteer_driver
                            ? job.skidsteer_driver.first_name
                            : ""
                        }}
                        {{
                          job.skidsteer_driver
                            ? job.skidsteer_driver.last_name
                            : ""
                        }}
                      </td>
                      <td v-if="reportType == 1">
                        {{ report.first_name + " " + report.last_name }}
                      </td>
                      <td>{{ job.notes }}</td>
                    </tr>
                  </template>
                </template>
              </tbody>
            </table>
          </v-col>
        </v-row>
      </v-container>
    </div>
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
  </v-app>
</template>
<style lang="scss">
// #app-bar.custom-toolbar, #app .sidebar-nav{
//   display: none;
// }
// #app main.v-content {
//     padding: 0 !important;
// }
</style>
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
      reportType: false,
      imgUrl: environment.imgUrl,
    };
  },
  mounted() {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    if (this.$route.query.type == "sales-by-customer") {
      this.reportType = 1;
    } else {
      this.reportType = 2;
    }

    this.getResults();
  },
  methods: {
    getResults() {
      jobService
        .getReport({
          type: this.$route.query.type,
          report_of: this.$route.query.number,
          start_date: this.$route.query.start,
          end_date: this.$route.query.end,
        })
        .then((response) => {
          //handle response
          if (response.status) {
            this.reportData = response.data;
            if ($.fn.dataTable.isDataTable("#hauler-table")) {
        console.log('in');
        $("#hauler-table").DataTable().destroy();
        this.tableAdd();
      }
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right",
            });
          }
        });
    },
    tableAdd(){
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable("#hauler-table")) {
          $("#hauler-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3, -4],
              },
            ],
            oLanguage: {
              sSearch: "",
              sEmptyTable: "No reports till now.",
              infoEmpty: "No reports found.",
            },
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
          $(".dataTables_filter input").attr("placeholder", "Search Customers");
          $(".dataTables_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $(".dataTables_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
        }
        $(".table-main").css({ opacity: 1 });
      });
    }
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable("#hauler-table")) {
          $("#hauler-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3, -4],
              },
            ],
            oLanguage: {
              sSearch: "",
              sEmptyTable: "No reports till now.",
              infoEmpty: "No reports found.",
            },
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
          $(".dataTables_filter input").attr("placeholder", "Search Customers");
          $(".dataTables_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $(".dataTables_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
        }
        $(".table-main").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>