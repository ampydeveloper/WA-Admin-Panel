<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Transactions By Customer
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
                  <th class="text-left">Date & Time</th>
                  <th class="text-left">Job#</th>
                  <th class="text-left">Customer</th>
                  <th class="text-left">Transaction Type</th>
                  <th class="text-left">Method <br />Total</th>
                </tr>
              </thead>
              <tbody>
                <template v-for="(reportData, index) in report">
                  <tr>
                    <!-- <tr v-for="(report.jobs, index2) in job"> -->
                    <!-- <td>{{ job.id }} <br />{{ job.job_providing_date }}</td>
                    <td></td>
                    <td>InProgress <br />$0.00</td>
                    <td>${{ job.amount }}</td>
                    <td>{{ job.service.service_name }}</td> -->
                    <td>
                      {{ report.first_name + " " + report.last_name }} #{{
                        report.id
                      }}
                    </td>
                    <td></td>
                  </tr>
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
  </v-app>
</template>

<script>
// import { required } from "vuelidate/lib/validators";/
import { jobService } from "../../../_services/job.service";
import { authenticationService } from "../../../_services/authentication.service";
import { environment } from "../../../config/test.env";
import { ChevronLeftIcon, ChevronRightIcon } from "vue-feather-icons";
import { router } from "../../../_helpers/router";

export default {
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
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
            this.reportData = response.saleCustomers;
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
        if (!$.fn.dataTable.isDataTable(".table-main")) {
          $(".table-main").DataTable({
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
          //   $(".dataTables_filter").append($("#search-input-icon").html());
          //   $(".dataTables_filter input").attr(
          //     "placeholder",
          //     "Search News by Heading / Description"
          //   );
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