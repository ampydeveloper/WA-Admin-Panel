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
          <v-tabs v-model="recordsTab" background-color="transparent">
            <v-tab key='jobs'>Jobs</v-tab>
            <v-tab key='invoices'>Invoices</v-tab>
          </v-tabs>
            <v-tabs-items v-model="recordsTab" dark>
              <v-tab-item key="jobs">
                  <table id="c-records-table" class="table table-striped table-bordered table-main">
                  <thead>
                      <tr>
                      <th>Job ID</th>
                      <th>Farm</th>
                      <th>Start Date</th>
                      <th>Start Time</th>
                      <th>Tech</th>
                      <th>Price</th>
                      <th>Status</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="record in records.jobDetails">
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
              </v-tab-item>
              <v-tab-item key="invoices" dark>
                  <table id="in-records-table" class="table table-striped table-bordered table-main">
                  <thead>
                      <tr>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Invoice Number</th>
                      <th>Service</th>
                      <th>Amount</th>
                      <th>In QuickBook</th>
                      <th>Email</th>
                      <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="invoice in records.invoices">
                      <td>{{invoice.created_at}}</td>
                      <td>
                          <router-link
                          v-if="isAdmin"
                          :to="'/admin/customer/details/'+invoice.id"
                          class="nav-black-link"
                          >{{invoice.customer.first_name}}</router-link>
                          <router-link
                          v-if="!isAdmin"
                          :to="'/manager/customer/details/'+invoice.id"
                          class="nav-black-link"
                          >{{invoice.customer.first_name}}</router-link>
                      </td>
                      <td>
                          <router-link v-if="isAdmin" :to="'/admin/jobs'" class="nav-black-link">{{invoice.id}}</router-link>
                          <router-link
                          v-if="!isAdmin"
                          :to="'/manager/jobs'"
                          class="nav-black-link"
                          >{{invoice.id}}</router-link>
                      </td>
                      <td>
                          <router-link
                          v-if="isAdmin"
                          :to="'/admin/service/edit/'+invoice.service.id"
                          class="nav-black-link"
                          >{{invoice.service.service_name}}</router-link>
                          <router-link
                          v-if="!isAdmin"
                          :to="'/manager/service/edit/'+invoice.service.id"
                          class="nav-black-link"
                          >{{invoice.service.service_name}}</router-link>
                      </td>
                      <td>${{invoice.amount}}</td>
                      <td>
                          <!-- <template v-if="!invoice.quick_book">
                          <span class="badges-item">Not Sync</span>
                          </template> -->
                          <template v-if="invoice.quick_book">
                          <span class="badges-item">Sync</span>
                          </template>
                      </td>
                      <td>{{invoice.customer.email}}</td>
                      <td><a :href="invoice.job_invoice" class="btn-outline-green-top">Download</a></td>
                      </tr>
                  </tbody>
                  </table>
              </v-tab-item>
            </v-tabs-items>
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
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { customerService } from "../../../../_services/customer.service";
import { router } from "../../../../_helpers/router";
import { authenticationService } from "../../../../_services/authentication.service";
import {
  PlusCircleIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
   SearchIcon,
} from "vue-feather-icons";
export default {
  components: {
    PlusCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
     SearchIcon,
  },
  data() {
    return {
      recordsTab: 'jobs',
      records: "",
      jobs: "",
      isAdmin: true,
    };
  },

  mounted() {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
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
        if (!$.fn.dataTable.isDataTable("#c-records-table")) {
        $("#c-records-table").DataTable({
          aoColumnDefs: [
            {
              bSortable: false,
              aTargets: [-1, -2, -3, -4, -5, -6],
            },
          ],
          oLanguage: {
              sSearch: "",
              sEmptyTable: "No records till now.",
              infoEmpty: "No records found.",
            },
          drawCallback: function (settings) {
            $("#c-records-table_paginate .paginate_button.previous").html(
              $("#table-chevron-left").html()
            );
            $("#c-records-table_paginate .paginate_button.next").html(
              $("#table-chevron-right").html()
            );
          },
        });
        $("#c-records-table_filter").append($("#search-input-icon").html());
        $("#c-records-table_filter input").attr("placeholder", "Search Records");
        $("#c-records-table_paginate .paginate_button.previous").html(
          $("#table-chevron-left").html()
        );
        $("#c-records-table_paginate .paginate_button.next").html(
          $("#table-chevron-right").html()
        );
        $("#c-records-table").css({ opacity: 1 });
        }

        if (!$.fn.dataTable.isDataTable("#in-records-table")) {
          $("#in-records-table").DataTable({
              aoColumnDefs: [
              {
                  bSortable: false,
                  aTargets: [-1, -2, -3, -4, -5, -6],
              },
              ],
              oLanguage: {
                  sSearch: "",
                  sEmptyTable: "No records till now.",
                  infoEmpty: "No records found.",
              },
              drawCallback: function (settings) {
              $("#in-records-table_paginate .paginate_button.previous").html(
                  $("#table-chevron-left").html()
              );
              $("#in-records-table_paginate .paginate_button.next").html(
                  $("#table-chevron-right").html()
              );
              },
          });
          $("#in-records-table_filter").append($("#search-input-icon").html());
          $("#in-records-table_filter input").attr("placeholder", "Search Records");
          $("#in-records-table_paginate .paginate_button.previous").html(
              $("#table-chevron-left").html()
          );
          $("#in-records-table_paginate .paginate_button.next").html(
              $("#table-chevron-right").html()
          );
          $("#in-records-table").css({ opacity: 1 });
        }
      });
    }, 1000);
  },
};
</script>