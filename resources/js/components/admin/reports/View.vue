<template>
  <v-container id="dashboard" fluid tag="section">
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            Reports
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
        <li>
          <router-link to="/admin/jobs">
            Reports
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
        <li>All</li>
      </ul>
    </div>

    <v-row class="dashboard-columns">
      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Sales Revenue</h4>

          <div class="report-each">
            <h5>Sales by Customer</h5>
            <ul class="list-unstyled">
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-customer&number=5'" target="_blank"
                  >Last 12 Months</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-customer&number=3'" target="_blank"
                  >This Year</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-customer&number=2'" target="_blank"
                  >Last Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-customer&number=1'" target="_blank"
                  >This Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-customer&number=0'" target="_blank"
                  >This Week</router-link
                >
              </li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-customer'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Sales by Service Tech</h5>
            <ul class="list-unstyled">
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-service-tech&number=5'" target="_blank"
                  >Last 12 Months</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-service-tech&number=3'" target="_blank"
                  >This Year</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-service-tech&number=2'" target="_blank"
                  >Last Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-service-tech&number=1'" target="_blank"
                  >This Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-sales-revenue?type=sales-by-service-tech&number=0'" target="_blank"
                  >This Week</router-link
                >
              </li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Payroll</h4>
          <div class="report-each">
            <h5>Hours worked by Driver</h5>
            <ul class="list-unstyled">
              <li><router-link :to="''">Last Two Weeks</router-link></li>
              <li><router-link :to="''">Last Week</router-link></li>
              <li><router-link :to="''">This Week</router-link></li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Job Driver & Labor Times</h5>
            <ul class="list-unstyled">
              <li><router-link :to="''">Last Two Weeks</router-link></li>
              <li><router-link :to="''">Last Week</router-link></li>
              <li><router-link :to="''">This Week</router-link></li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div> -->

      <!-- <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Mechanic Reports</h4>
          <div class="report-each">
            <h5>Expenses by Mechanic</h5>
            <ul class="list-unstyled">
              <li><router-link :to="''">Last Two Weeks</router-link></li>
              <li><router-link :to="''">Last Week</router-link></li>
              <li><router-link :to="''">This Week</router-link></li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div> -->

      <!-- <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Tax</h4>
          <div class="report-each">
            <h5>Sales Tax Report</h5>
            <ul class="list-unstyled">
              <li><router-link :to="''">Last Quarter</router-link></li>
              <li><router-link :to="''">Last Month</router-link></li>
              <li><router-link :to="''">This Month</router-link></li>
              <li><router-link :to="''">This Week</router-link></li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div> -->

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Activity Reports</h4>
          <div class="report-each">
            <h5>Job Activity Report</h5>
            <ul class="list-unstyled">
              <li>
                <router-link
                  :to="'/admin/report-activity?type=job-activity-report&number=2'" target="_blank"
                  >Last Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-activity?type=job-activity-report&number=7'" target="_blank"
                  >Last Week</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-activity?type=job-activity-report&number=1'" target="_blank"
                  >This Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-activity?type=job-activity-report&number=0'" target="_blank"
                  >This Week</router-link
                >
              </li>
              <li>
               <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'job-activity-report'),
                      (reportcLink = 'report-activity')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Payments & Refund Transactions</h4>
          <div class="report-each">
            <h5>Transactions by Customer</h5>
            <ul class="list-unstyled">
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-customer&number=5'" target="_blank"
                  >Last 12 Months</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-customer&number=3'" target="_blank"
                  >Last Year</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-customer&number=2'" target="_blank"
                  >Last Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-customer&number=1'" target="_blank"
                  >This Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-customer&number=0'" target="_blank"
                  >This Week</router-link
                >
              </li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'transactions-by-customer'),
                      (reportcLink = 'report-payment-refund')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Transactions by Job</h5>
            <ul class="list-unstyled">
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-job&number=5'" target="_blank"
                  >Last 12 Months</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-job&number=3'" target="_blank"
                  >Last Year</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-job&number=2'" target="_blank"
                  >Last Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-job&number=1'" target="_blank"
                  >This Month</router-link
                >
              </li>
              <li>
                <router-link
                  :to="'/admin/report-payment-refund?type=transactions-by-job&number=0'" target="_blank"
                  >This Week</router-link
                >
              </li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'transactions-by-job'),
                      (reportcLink = 'report-payment-refund')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>List Reports</h4>
          <div class="report-each">
            <h5>Customer List</h5>
            <ul class="list-unstyled">
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Customer Farms</h5>
            <ul class="list-unstyled">
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div> -->

      <!-- <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Day Sheet</h4>
          <div class="report-each">
            <h5>Day Sheet by Driver</h5>
            <ul class="list-unstyled">
              <li><router-link :to="''">Today</router-link></li>
              <li><router-link :to="''">Tomorrow</router-link></li>
              <li><router-link :to="''">Next Two Weeks</router-link></li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Expanded Day Sheet by Driver</h5>
            <ul class="list-unstyled">
              <li><router-link :to="''">Today</router-link></li>
              <li><router-link :to="''">Tomorrow</router-link></li>
              <li><router-link :to="''">Next Two Weeks</router-link></li>
              <li>
                <a
                  href=""
                  @click.prevent="
                    (dialog = true),
                      (reportcType = 'sales-by-service-tech'),
                      (reportcLink = 'report-sales-revenue')
                  "
                  >Custom</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div> -->
    </v-row>

    <v-dialog v-model="dialog" width="620">
      <div class="modal-outer">
        <v-card>
          <v-card-title class="headline"> Customize Report </v-card-title>

          <v-card-text class="">
            <v-form
              ref="form"
              v-model="valid"
              lazy-validation
              class="custom_form_field row"
              id="form_field"
            >
              <input
                type="hidden"
                id="reportcType-input"
                name="reportcType"
                :value="reportcType"
              />
              <input
                type="hidden"
                id="reportcLink-input"
                name="reportcLink"
                :value="reportcLink"
              />

              <h6 class="col-sm-2" style="margin-top: 12px; padding-right: 0">
                Date Range
              </h6>
              <v-col cols="5" sm="5" class="pt-0 pb-0 cr-start-date">
                <v-menu
                  v-model="menu1"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  label="Enter Start Date"
                  placeholder
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="date"
                      prepend-icon="event"
                      readonly
                      v-on="on"
                      label="Select Start Date"
                      placeholder
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="date"
                    @input="menu1 = false"
                    :min="setDate"
                  ></v-date-picker>
                </v-menu>
              </v-col>

              <v-col cols="5" sm="5" class="pt-0 pb-0 cr-end-date">
                <v-menu
                  v-model="menu2"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  label="Enter End Date"
                  placeholder
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="date1"
                      prepend-icon="event"
                      readonly
                      v-on="on"
                      label="Select End Date"
                      placeholder
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="date1"
                    @input="menu2 = false"
                    :min="setDate"
                  ></v-date-picker>
                </v-menu>
              </v-col>

              <v-col class="pt-0 pb-0 text-right" cols="12" md="12">
                <v-btn
                  type="submit"
                  color="success"
                  class="custom-save-btn mt-4 customReportLink"
                  id="submit_btn"
                  >Generate Report</v-btn
                >
              </v-col>
            </v-form>
          </v-card-text>
        </v-card>
      </div>
    </v-dialog>
  </v-container>
</template>

<script>
import { router } from "../../../_helpers/router";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
// import { SendIcon, ImageIcon, LoaderIcon } from "vue-feather-icons";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  data() {
    return {
      dialog: false,
      valid: true,
      menu2: false,
      menu1: false,
      reportcType: false,
      reportcLink: false,
      date: "",
      date1: "",
      setDate: new Date().toISOString().substr(0, 10),
      updateForm: {
        start_date: "",
        end_date: "",
      },
    };
  },
  created() {},
  methods: {
    generateReport(reportId) {
      jobService.getReport({ report_of: reportId }).then((response) => {
        //handle response
        if (response.status) {
          // this.job = response.data;
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
    $(document).ready(function () {
      $("#form_field").on("submit", function (e) {
        // $('#reportcType-input').val();

        var rName = $("#reportcType-input").val(),
          rLink = $("#reportcLink-input").val(),
          startdate = $("#form_field .cr-end-date input").val(),
          enddate = $("#form_field .cr-start-date input").val();
        var createLink =
          environment.baseUrl +
          "admin/" +
          rLink +
          "?type=" +
          rName +
          "&start=" +
          startdate +
          "&end=" +
          enddate+"&number=null";
        location.href = createLink;
        e.preventDefault();
      });
    });
  },
};
</script>