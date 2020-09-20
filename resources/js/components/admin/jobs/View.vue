<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            All Jobs
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
          <div class="add-icon">
            <router-link v-if="isAdmin" to="/admin/jobs/add" class="nav-item nav-link">
              <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/jobs/add" class="nav-item nav-link">
              <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
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
              <v-card class="jobs-aj" color="basil" flat v-if="item == 'All Jobs'">
                <table
                  id="all-jobs-table"
                  class="table table-striped table-bordered table-main all_jobs"
                >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th class="job-summ">Job Summary</th>
                      <th>Customer</th>
                      <th class="tech-col">Techs</th>
                      <th class="time-col">Time</th>
                      <!-- <th>Distance</th> -->
                      <th>Payment</th>
                      <!-- <th>Chat</th> -->
                      <th>Status</th>
                      <!-- <th>Actions</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(job, index) in alljobs">
                      <td>{{index+1}}</td>
                      <td>
                        {{job.start_date}}
                        <br />
                        #{{job.id}}
                        <br />
                        ${{job.job_amount}}
                        <br />
                        {{job.service.service_name}}
                      </td>
                      <td>
                        {{job.customer.first_name}}
                        <br />
                        {{job.manager.first_name}}
                        <br />
                        {{job.manager.phone}}
                        <br />
                        {{job.manager.email}}
                        <br />
                        {{job.manager.address}} {{job.manager.city}} {{job.manager.state}} {{job.manager.country}} {{job.manager.zip_code}}
                      </td>
                      <td class="job-col-body">
                        <span>Truck Driver Name:</span>
                        <template v-if="job.truck_driver">{{job.truck_driver.first_name}}</template>
                        <template v-if="!job.truck_driver">Not Assigned Yet</template>
                        <br />
                        <span>Truck Number:</span>
                        <template v-if="job.truck">{{job.truck.truck_number}}</template>
                        <template v-if="!job.truck">Not Assigned Yet</template>
                        <br />
                        <span>skidsteer Driver Name:</span>
                        <template v-if="job.skidsteer_driver">{{job.skidsteer_driver.first_name}}</template>
                        <template v-if="!job.skidsteer_driver">Not Assigned Yet</template>
                        <br />
                        <span>skidsteer Number:</span>
                        <template v-if="job.skidsteer">{{job.skidsteer.truck_number}}</template>
                        <template v-if="!job.skidsteer">Not Assigned Yet</template>
                      </td>
                      <td class="job-col-body">
                        <span>Start Time:</span>
                        <template>9:30 pm</template>
                        <br />
                        <span>End Time:</span>
                        <template>12:30 Pm</template>
                        <br />
                        <span>Time Taken:</span>
                        <template>3</template>
                      </td>
                      <!-- <td>3000</td> -->
                      <td>
                        <template v-if="job.payment_status">
                          <span class="badges-item">Paid</span></template>
                        <template v-if="!job.payment_status">
                        
                          <span class="badges-item">Unpaid</span>
                        </template>
                      </td>
                      <!-- <td>
                        <router-link
                          v-if="isAdmin"
                          :to="'/admin/jobs/chat/' + job.id"
                          class="nav-item nav-link"
                        >View chat</router-link>
                        <router-link
                          v-if="!isAdmin"
                          :to="'/manager/jobs/chat/' + job.id"
                          class="nav-item nav-link"
                        >View chat</router-link>
                      </td>-->
                      <td>
                        <template v-if="!job.job_status">
                          <span class="badges-item">Open</span>
                        </template>
                        <template v-if="job.job_status">
                          <span class="badges-item">Close</span>
                        </template>
                      </td>
                      <!-- <td>
                        <div class="dropdown" v-bind:class="{ 'show': triggerDropdown }">
                          <more-vertical-icon
                            size="1.5x"
                            class="custom-class dropdown-trigger"
                            v-on:click="dropdownToggle"
                          ></more-vertical-icon>
                          <span class="dropdown-menu">
                            <router-link
                              v-if="isAdmin"
                              :to="'/admin/jobs/chat/' + job.id"
                              class="nav-item nav-link"
                            >View chat</router-link>
                            <router-link
                              v-if="!isAdmin"
                              :to="'/manager/jobs/chat/' + job.id"
                              class="nav-item nav-link"
                            >View chat</router-link>
                           <button class="btn dropdown-item">Edit</button>
                            <button class="btn dropdown-item">Delete</button>
                          </span>
                        </div>
                      </td> -->
                    </tr>
                    <tr v-if="alljobs.length == 0">
                      <td colspan="9">No jobs till now.</td>
                    </tr>
                  </tbody>
                </table>
              </v-card>
              <v-card class="jobs-aj" color="basil" flat v-if="item == 'Repeating Jobs'">
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
  </v-app>
</template>

<script>
import {
  PlusCircleIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  MoreVerticalIcon,
} from "vue-feather-icons";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon,
    MoreVerticalIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
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
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable("#all-jobs-table")) {
          $("#all-jobs-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3, -4, -5],
              },
            ],
            oLanguage: { sSearch: "" },
            drawCallback: function (settings) {
              $("#all-jobs-table_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
              );
              $("#all-jobs-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });

          $("#all-jobs-table_filter input").attr("placeholder", "Search Jobs");
          $("#all-jobs-table_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $("#all-jobs-table_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
        }
        $("#all-jobs-table").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>
