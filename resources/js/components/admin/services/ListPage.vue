<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Services
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
          <router-link to="/admin/services">
            Service
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
          <div class="add-icon">
            <router-link v-if="isAdmin" to="/admin/service/add" class>
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/service/add" class>
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
          </div>
          <v-col cols="12" md="12" class="main-box-inner">
            <!-- <div v-bar="{
    preventParentScroll: true,
            scrollThrottle: 30, }">-->
            <table id="service-table" class="table table-striped table-bordered table-main">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Service Name</th>
                  <th class="text-left">Service Type</th>
                  <th class="text-left">Service For</th>
                  <th class="text-left">Price</th>
                  <th class="text-left">Overhead Cost</th>
                  <th class="text-left">Day Time</th>
                  <th class="text-left">Complete Time</th>
                  <th class="text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in services"
                  :key="item.name"
                  v-bind:class="{ 'selected' : isActive == index}"
                >
                  <td>{{index+1}}</td>
                  <td>{{ item.service_name }}</td>
                  <td v-if="item.service_type == 1">Per Load</td>
                  <td v-if="item.service_type == 2">Per Trip</td>
                  <td v-if="item.service_type == 3">Per Bucket</td>
                  <td v-if="item.service_for == 4">Customer</td>
                  <td v-if="item.service_for == 6">Hauler</td>
                  <td>${{ item.price }}</td>
                  <td>${{ item.overhead_cost }}</td>
                  <td>
                    <span v-for="(type, index) in item.slot_type">
                      <span class="badges-item" v-if="type == 1 || type == 2 || type == 3">
                        <label v-if="type == 1">Morning</label>
                        <label v-if="type == 2">Afternoon</label>
                        <label v-if="type == 3">Evening</label>
                      </span>
                    </span>
                  </td>
                  <td v-if="item.time_taken_to_complete_service == 1">15 mins</td>
                  <td v-if="item.time_taken_to_complete_service == 2">30 mins</td>
                  <td v-if="item.time_taken_to_complete_service == 3">45 mins</td>
                  <td v-if="item.time_taken_to_complete_service == 4">60 mins</td>
                  <td v-if="item.time_taken_to_complete_service == 5">75 mins</td>
                  <td v-if="item.time_taken_to_complete_service == 6">90 mins</td>
                  <td v-if="item.time_taken_to_complete_service == null || item.time_taken_to_complete_service == 0"></td>
                  <td class="action-col">
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/service/edit/' + item.id"
                      class="nav-item nav-link"
                    >
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/service/edit/' + item.id"
                      class="nav-item nav-link"
                    >
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>
                    <a href="javascript:void(0);" text @click="Delete(item.id)">
                      <trash-icon size="1.5x" class="custom-class"></trash-icon>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- </div> -->
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

<script>
import { required } from "vuelidate/lib/validators";
import { serviceService } from "../../../_services/service.service";
import { authenticationService } from "../../../_services/authentication.service";
import { environment } from "../../../config/test.env";
import {
  UserIcon,
  TrashIcon,
  PlusIcon,
  MoreVerticalIcon,
  Edit3Icon,
  ChevronLeftIcon,
  ChevronRightIcon,
  SearchIcon,
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
export default {
  components: {
    UserIcon,
    TrashIcon,
    PlusIcon,
    MoreVerticalIcon,
    Edit3Icon,
    ChevronLeftIcon,
    ChevronRightIcon,
    SearchIcon,
  },
  data() {
    return {
      dialog: false,
      triggerDropdown: null,
      isActive: null,
      on: false,
      baseUrl: environment.baseUrl,
      services: [],
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
    this.getResults();
  },

  methods: {
    getResults() {
      serviceService.listService().then((response) => {
        //handle response
        if (response.status) {
          this.services = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    Delete(e) {
      this.$swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this service?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes Delete it!",
        cancelButtonText: "No, Keep it!",
        showCloseButton: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        if (result.value) {
          this.deleteService(e);
        }
      });

      return false;
    },
    deleteService(e) {
      if (e) {
        serviceService.Delete(e).then((response) => {
          //handle response
          if (response.status) {
            this.getResults();
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            //redirect to login
            this.dialog = false;
            //               router.push("/admin/service");
          } else {
            this.dialog = false;
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right",
            });
          }
        });
      }
    },
    Close() {
      this.dialog = false;
    },
    selectTr: function (rowIndex) {
      this.isActive = rowIndex;
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
              aTargets: [-1, -2, -3, -5, -6],
            },
          ],
          oLanguage: { sSearch: "", "sEmptyTable": "No service till now.", "sInfoEmpty": "No service found.", },
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
        $(".dataTables_filter input").attr(
          "placeholder",
          "Search Services by Name / Price"
        );
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
