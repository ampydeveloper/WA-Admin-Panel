<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            All Trucks
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
        <li>Trucks</li>
        <li>
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
        </li>
        <li>All</li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <div class="add-icon">
            <router-link v-if="isAdmin" to="/admin/truck/add" class="nav-item nav-link">
              <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/truck/add" class="nav-item nav-link">
              <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
            </router-link>
          </div>
          <v-col cols="12" md="12" class="main-box-inner">
            <table id="truck-table" class="table table-striped table-bordered table-main">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Company</th>
                  <th class="text-left">Truck</th>
                  <th class="text-left">Chassis</th>
                  <th class="text-left">Distance</th>
                  <th class="text-left">Service Details</th>
                  <th class="text-left">Status</th>
                  <th class="text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in trucks"
                  :key="item.name"
                  v-on:click="selectTr(index)"
                  v-bind:class="{ 'selected' : isActive == index}"
                >
                  <td>{{index+1}}</td>
                  <td>{{item.company_name}}</td>
                  <td>{{item.truck_number}}</td>
                  <td>{{item.chaase_number}}</td>
                  <td>{{item.killometer}}</td>
                  <td>
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/truck/service/' + item.id"
                      class="custom-save-btn btn-table"
                    >View</router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/truck/service/' + item.id"
                      class="custom-save-btn btn-table"
                    >View</router-link>
                  </td>

                  <td v-if="item.status == 1">
                    <span class="badges-item">Available</span>
                  </td>
                  <td v-if="item.status == 0">
                    <span class="badges-item">Unavailable</span>
                  </td>
                  <td class="action-col">
                    <router-link v-if="isAdmin" :to="'/admin/truck/edit/' + item.id">
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>
                    <router-link v-if="!isAdmin" :to="'/manager/truck/edit/' + item.id">
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>
                    <a href="javascript:void(0);" text @click="Delete(item.id)">
                      <trash-icon size="1.5x" class="custom-class"></trash-icon>
                    </a>
                  </td>
                </tr>
                <tr v-if="trucks.length == 0">
                  <td colspan="9">No trucks till now.</td>
                </tr>
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
import { required } from "vuelidate/lib/validators";
import { truckService } from "../../../_services/truck.service";
import {
  UserIcon,
  TrashIcon,
  PlusCircleIcon,
  Edit3Icon,
  MoreVerticalIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    UserIcon,
    Edit3Icon,
    TrashIcon,
    PlusCircleIcon,
    MoreVerticalIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
  },
  data() {
    return {
      dialog: false,
      triggerDropdown: null,
      isActive: null,
      on: false,
      trucks: [],
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
      truckService.listTrucks().then((response) => {
        //handle response
        if (response.status) {
          this.trucks = response.data;
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
      if (e) {
        truckService.Delete(e).then((response) => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            //redirect to login
            this.dialog = false;
            this.getResults();
            //router.push("/admin/service");
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
    dropdownToggle: function (setIndex) {
      //if same index is called up again then close it
      if (this.triggerDropdown == setIndex) {
        this.triggerDropdown = null;
      } else {
        this.triggerDropdown = setIndex;
      }
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
        }
        $(".dataTables_filter input").attr("placeholder", "Search Trucks");
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
