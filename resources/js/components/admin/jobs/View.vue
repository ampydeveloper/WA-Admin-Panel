<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            Jobs
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
            Jobs
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

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <!-- <v-col class="d-flex" cols="12" sm="4">
          <v-select
            :items="dropdown"
            label="Status"
            dense
            v-model="selected"
            @change="onChange($event)"
            class="custom-sel-box"
          ></v-select>
          </v-col>-->
          <div class="add-icon top-buttons">
            <router-link v-if="isAdmin" to="/admin/jobs/add" class>
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/jobs/add" class>
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
          </div>
          <v-tabs
            class="custom-tabs-wdout-modif c-tabs-50"
            v-model="tab"
            background-color="transparent"
            color="#56bf7a"
            grow
          >
            <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
          </v-tabs>
          <v-tabs-items v-model="tab" class="custom-tab-content">
            <v-tab-item v-for="item in items" :key="item">
              <!-- customer info tabs -->
              <v-card
                class="jobs-aj"
                color="basil"
                flat
                v-if="item == 'All Jobs'"
              >
                <table
                  id="all-jobs-table"
                  class="table table-striped table-bordered table-main all_jobs"
                >
                  <thead>
                    <tr>
                      <th class="job-summ">Job Summary</th>
                      <th>Customer / Manager / Farm Location</th>
                      <th class="tech-col">Techs / Vehicles</th>
                      <th class="time-col">Est. Time</th>
                      <th>Payment Status</th>
                      <th>Job Status</th>
                      <th style="padding: 0 !important;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(job, index) in alljobs">
                      <td>
                        <span class="basic-info">{{
                          job.start_date | formatDateLic
                        }}</span>
                        <span class="basic-big">#JOB100{{ job.id }}</span>
                        <span class="basic-grey"
                          >${{ job.job_amount ? job.job_amount : 0 }}</span
                        >
                        <span class="basic-grey">{{
                          job.service ? job.service.service_name : ""
                        }}</span>
                      </td>
                      <td>
                        <span class="basic-big">{{
                          job.customer ? job.customer.first_name : ""
                        }}</span>
                        <span class="basic-grey"
                          >{{ job.manager ? job.manager.first_name : "" }} ({{
                            job.manager ? job.manager.email : ""
                          }})</span
                        >
                        <span class="basic-grey" v-if="job.manager"
                          >{{ job.manager.address }} {{ job.manager.city }}
                          {{ job.manager.state }} {{ job.manager.country }}
                          {{ job.manager.zip_code }}</span
                        >
                      </td>
                      <td class="job-col-body">
                        <span class="basic-grey-label-half">Truck Driver</span>
                        <span class="basic-info-half" v-if="job.truck_driver">{{
                          job.truck_driver ? job.truck_driver.first_name : ""
                        }}</span>
                        <span class="basic-info-half" v-if="!job.truck_driver"
                          >Not Assigned</span
                        >
                        <div class="clearfix"></div>
                        <span class="basic-grey-label-half">Truck</span>
                        <span class="basic-info-half" v-if="job.truck">{{
                          job.truck.truck_number
                        }}</span>
                        <span class="basic-info-half" v-if="!job.truck"
                          >Not Assigned</span
                        >
                        <div class="clearfix"></div>
                        <span class="basic-grey-label-half"
                          >Skidsteer Driver</span
                        >
                        <span
                          class="basic-info-half"
                          v-if="job.skidsteer_driver"
                          >{{
                            job.skidsteer_driver
                              ? job.skidsteer_driver.first_name
                              : ""
                          }}</span
                        >
                        <span
                          class="basic-info-half"
                          v-if="!job.skidsteer_driver"
                          >Not Assigned</span
                        >
                        <div class="clearfix"></div>
                        <span class="basic-grey-label-half">Skidsteer</span>
                        <span class="basic-info-half" v-if="job.skidsteer">{{
                          job.skidsteer.truck_number
                        }}</span>
                        <span class="basic-info-half" v-if="!job.skidsteer"
                          >Not Assigned</span
                        >
                      </td>
                      <td class="job-col-body">
                        <span class="basic-grey-label-full">Start Time</span>
                        <span class="basic-info-full">9:30 pm</span>

                        <!-- <span class="basic-grey-label">End Time:</span>
                        <template>12:30 Pm</template>
                      
                        <span class="basic-grey-label">Time Taken:</span>
                        <template>3</template>-->
                      </td>
                      <!-- <td>3000</td> -->
                      <td>
                        <template v-if="job.payment_status">
                          <span class="badges-item">Paid</span>
                        </template>
                        <template v-if="!job.payment_status">
                          <span class="badges-item">Unpaid</span>
                        </template>
                      </td>
                      <td>
                        <template v-if="!job.job_status">
                          <span class="badges-item">Open</span>
                        </template>
                        <template v-if="job.job_status">
                          <span class="badges-item">Close</span>
                        </template>
                      </td>
                      <td class="action-col">
                        <router-link
                          v-if="isAdmin"
                          :to="'/admin/jobs/chat/' + job.id"
                          class="btn-outline-green-top"
                          style="width: 75px;"
                        >View chat
                        </router-link>
                        <router-link
                          v-if="!isAdmin"
                          :to="'/manager/jobs/chat/' + job.id"
                         class="btn-outline-green-top" style="width: 75px;"
                        >View chat
                        </router-link>
                        <a href="javascript:void(0);" text @click="Delete(job.id)">
                        <trash-icon size="1.5x" class="custom-class"></trash-icon>
                      </a>
                      </td>
                      <!-- <td>
                        <button class="btn dropdown-item">Edit</button>
                      </td> -->
                    </tr>
                  </tbody>
                </table>
              </v-card>
              <v-card
                class="jobs-aj"
                color="basil"
                flat
                v-if="item == 'Repeating Jobs'"
              >
                <repeating-jobs />
              </v-card>
            </v-tab-item>
          </v-tabs-items>
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
    <span id="search-input-icon2" class="d-none">
      <span class="search-input-outer">
        <search-icon size="1.5x" class="custom-class"></search-icon>
      </span>
    </span>
  </v-app>
</template>

<script>
import {
  PlusIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  MoreVerticalIcon,
  SearchIcon,
    TrashIcon,
} from "vue-feather-icons";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    PlusIcon,
    MoreVerticalIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    SearchIcon,
      TrashIcon,
    AllJobs: () => import("./tab/AllJobs"),
    AssignedJobs: () => import("./tab/AssignedJobs"),
    CompletedJobs: () => import("./tab/CompletedJobs"),
    OpenJobs: () => import("./tab/OpenJobs"),
    RepeatingJobs: () => import("./tab/RepeatingJobs"),
    UnpaidJobs: () => import("./tab/UnpaidJobs"),
  },

  data() {
    return {
      tab: null,
      isAdmin: true,
      triggerDropdown: false,
      items: ["All Jobs", "Repeating Jobs"],
      dropdown: [
        "All Jobs",
        "Assigned Jobs",
        "Completed Jobs",
        "Paid",
        "Unpaid",
        "Open",
      ],
      alljobs: "",
      selected: "All Jobs",
    };
  },
  mounted() {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    this.getResults(this.selected);
  },
  methods: {
    getResults(data) {
      this.alljobs = [];
      jobService.joblist({ status: data }).then((response) => {
        //handle response
        if (response.status) {
          this.alljobs = response.data.allJobs;
          this.tableAdd();
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    onChange(event) {
      this.getResults(event);
    },
    dropdownToggle: function () {
      this.triggerDropdown = !this.triggerDropdown;
    },
    Delete(e) {
      this.$swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this Job?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes Delete it!",
        cancelButtonText: "No, Keep it!",
        showCloseButton: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        if (result.value) {
          this.deleteJob(e);
        }
      });

      return false;
    },
    deleteJob(e) {
      if (e) {
        this.loading = true;
        jobService.deleteJob(e).then((response) => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: 'Job deleted successfully.',
              type: "success",
              position: "top-right",
            });
            //redirect to login
            this.dialog = false;

            //reload table
            this.getResults();
          } else {
            this.dialog = false;
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right",
            });
          }
          this.loading = false;
        });
      }
    },
    tableAdd(){
       $("#all-jobs-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3, -4, -5],
              },
            ],
            oLanguage: {
              sSearch: "",
              sEmptyTable: "No jobs till now.",
              infoEmpty: "No jobs found.",
            },
            drawCallback: function (settings) {
              $("#all-jobs-table_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
              );
              $("#all-jobs-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });

          $("#all-jobs-table_filter").append($("#search-input-icon").html());
          $("#all-jobs-table_filter input").attr(
            "placeholder",
            "Search Jobs by Job ID / Customer / Service"
          );
          $("#all-jobs-table_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $("#all-jobs-table_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
          $("#all-jobs-table").css({ opacity: 1 });
    }
  },
  updated() {
    // setTimeout(function () {
    //   $(document).ready(function () {
    //     if (!$.fn.dataTable.isDataTable("#all-jobs-table")) {
    //        $("#all-jobs-table").DataTable({
    //         aoColumnDefs: [
    //           {
    //             bSortable: false,
    //             aTargets: [-1, -2, -3, -4, -5],
    //           },
    //         ],
    //         oLanguage: {
    //           sSearch: "",
    //           sEmptyTable: "No jobs till now.",
    //           infoEmpty: "No jobs found.",
    //         },
    //         drawCallback: function (settings) {
    //           $("#all-jobs-table_paginate .paginate_button.previous").html(
    //             $("#table-chevron-left").html()
    //           );
    //           $("#all-jobs-table_paginate .paginate_button.next").html(
    //             $("#table-chevron-right").html()
    //           );
    //         },
    //       });

    //       $("#all-jobs-table_filter").append($("#search-input-icon").html());
    //       $("#all-jobs-table_filter input").attr(
    //         "placeholder",
    //         "Search Jobs by Job ID / Customer / Service"
    //       );
    //       $("#all-jobs-table_paginate .paginate_button.previous").html(
    //         $("#table-chevron-left").html()
    //       );
    //       $("#all-jobs-table_paginate .paginate_button.next").html(
    //         $("#table-chevron-right").html()
    //       );
    //     }
    //     $("#all-jobs-table").css({ opacity: 1 });
    //   });
    // }, 1000);
  },
};
</script>
