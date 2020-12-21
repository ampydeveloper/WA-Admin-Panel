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
              <li><a href="/admin/report-details?type=sales-by-customer&number=5">Last 12 Months</a></li>
              <li><a href="" @click.prevent="generateReport(3)">This Year</a></li>
              <li><a href="" @click.prevent="generateReport(2)">Last Month</a></li>
              <li><a href="" @click.prevent="generateReport(1)">This Month</a></li>
              <li><a href="" @click.prevent="generateReport(0)">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Sales by Service Tech</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="generateReport()">Last 12 Months</a></li>
              <li><a href="" @click.prevent="generateReport()">This Year</a></li>
              <li><a href="" @click.prevent="generateReport()">Last Months</a></li>
              <li><a href="" @click.prevent="generateReport()">This Months</a></li>
              <li><a href="" @click.prevent="generateReport()">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Payroll</h4>
          <div class="report-each">
            <h5>Hours worked by Driver</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="generateReport()">Last Two Weeks</a></li>
              <li><a href="" @click.prevent="generateReport()">Last Week</a></li>
              <li><a href="" @click.prevent="generateReport()">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Job Driver & Labor Times</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="generateReport()">Last Two Weeks</a></li>
              <li><a href="" @click.prevent="generateReport()">Last Week</a></li>
              <li><a href="" @click.prevent="generateReport()">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Mechanic Reports</h4>
          <div class="report-each">
            <h5>Expenses by Mechanic</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="generateReport()">Last Two Weeks</a></li>
              <li><a href="" @click.prevent="generateReport()">Last Week</a></li>
              <li><a href="" @click.prevent="generateReport()">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Tax</h4>
          <div class="report-each">
            <h5>Sales Tax Report</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="generateReport()">Last Quarter</a></li>
              <li><a href="" @click.prevent="generateReport()">Last Month</a></li>
              <li><a href="" @click.prevent="generateReport()">This Month</a></li>
              <li><a href="" @click.prevent="generateReport()">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Activity Reports</h4>
          <div class="report-each">
            <h5>Job Activity Report</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="generateReport()">Last Month</a></li>
              <li><a href="" @click.prevent="generateReport()">Last Week</a></li>
              <li><a href="" @click.prevent="generateReport()">This Month</a></li>
              <li><a href="" @click.prevent="generateReport()">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
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
              <li><a href="">Last 12 Months</a></li>
              <li><a href="">Last Year</a></li>
              <li><a href="">Last Month</a></li>
              <li><a href="">This Month</a></li>
              <li><a href="">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Transactions by Job</h5>
            <ul class="list-unstyled">
              <li><a href="">Last 12 Months</a></li>
              <li><a href="">Last Year</a></li>
              <li><a href="">Last Month</a></li>
              <li><a href="">This Month</a></li>
              <li><a href="">This Week</a></li>
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>List Reports</h4>
          <div class="report-each">
            <h5>Customer List</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Customer Farms</h5>
            <ul class="list-unstyled">
              <li><a href="" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6 reports-list">
        <div class="reports-list-in">
          <h4>Day Sheet</h4>
          <div class="report-each">
            <h5>Day Sheet by Driver</h5>
            <ul class="list-unstyled">
              <li><a href="">Today</a></li>
              <li><a href="">Tomorrow</a></li>
              <li><a href="">Next Two Weeks</a></li>
              <li><a href="#" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>

          <div class="report-each">
            <h5>Expanded Day Sheet by Driver</h5>
            <ul class="list-unstyled">
              <li><a href="">Today</a></li>
              <li><a href="">Tomorrow</a></li>
              <li><a href="">Next Two Weeks</a></li>
              <li><a href="#" @click.prevent="dialog = true">Custom</a></li>
            </ul>
          </div>
        </div>
      </div>
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
              <h6 class="col-sm-2" style="margin-top: 12px; padding-right: 0">
                Date Range
              </h6>
              <v-col cols="5" sm="5" class="pt-0 pb-0">
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

              <v-col cols="5" sm="5" class="pt-0 pb-0">
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
                  class="custom-save-btn mt-4"
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
  }
};
</script>