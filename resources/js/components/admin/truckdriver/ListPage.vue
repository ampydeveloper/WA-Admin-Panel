<template>
  <v-app id="driver_list">
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Drivers
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
          <router-link to="/admin/truckdrivers">
            Drivers
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
            <router-link v-if="isAdmin" to="/admin/truckdriver/add" class="nav-item nav-link">
               <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/truckdriver/add" class="nav-item nav-link">
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
          </div>

          <v-col cols="12" md="12" class="main-box-inner">
            <table id="driver-table" class="table table-striped table-bordered table-main">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Driver Name</th>
                  <th class="text-left">Phone</th>
                  <th class="text-left">Email</th>
                  <th class="text-left">Distance Traveled</th>
                  <th class="text-left">Salary</th>
                  <th class="text-left">Active</th>
                  <th class="text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in drivers"
                  :key="item.name"
                  v-on:click="selectTr(index)"
                  v-bind:class="{ 'selected' : isActive == index}"
                >
                  <td>{{index+1}}</td>
                  <td>
                    <div class="v-avatar v-list-item__avatar">
                      <img
                        v-if="item.user_image"
                        :src="imgUrl+item.user_image"
                        class="small-img"
                      />
                      <img v-if="!item.user_image" :src="imgUrl+'images/avatar.png'" alt class="small-img" />
                    </div>
                    {{ item.first_name }} {{ item.last_name }}
                  </td>
                  <td>{{ item.phone }}</td>
                  <td>{{ item.email }}</td>
                  <td>0</td>
                  <td v-if="item.driver.salary_type  == 0">${{ item.driver.driver_salary }}/hr</td>
                  <td v-if="item.driver.salary_type  == 1">${{ item.driver.driver_salary }}/month</td>
                  <td>
                    <span v-if="!item.is_active" class="badges-item">No</span>
                    <span v-if="item.is_active" class="badges-item">Yes</span>
                  </td>
                  <td class="action-col">
                    <router-link v-if="!isAdmin" :to="'/manager/truckdriver/edit/' + item.id">
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>
                    <router-link v-if="isAdmin" :to="'/admin/truckdriver/edit/' + item.id">
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>
                    <a href="javascript:void(0);" text @click="Delete(item.id)">
                      <trash-icon size="1.5x" class="custom-class"></trash-icon>
                    </a>
                  </td>
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
     <span id="search-input-icon" class="d-none">
      <span class="search-input-outer">
        <search-icon size="1.5x" class="custom-class"></search-icon>
      </span>
    </span>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { driverService } from "../../../_services/driver.service";
import { authenticationService } from "../../../_services/authentication.service";
import { environment } from "../../../config/test.env";
import {
  UserIcon,
  Edit3Icon,
  TrashIcon,
  PlusIcon,
  MoreVerticalIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
   SearchIcon,
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
export default {
  components: {
    UserIcon,
    Edit3Icon,
    TrashIcon,
    PlusIcon,
    MoreVerticalIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
     SearchIcon,
  },
  data() {
    return {
      dialog: false,
      isActive: null,
      on: false,
      drivers: [],
      isAdmin: true,
      triggerDropdown: null,
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
    this.getResults();
  },
  methods: {
    selectTr: function (rowIndex) {
      this.isActive = rowIndex;
    },
    getResults() {
      driverService.listDrivers().then((response) => {
        //handle response
        if (response.status) {
          this.drivers = response.data;
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
        text: "Are you sure you want to delete this driver?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes Delete it!",
        cancelButtonText: "No, Keep it!",
        showCloseButton: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        if (result.value) {
          this.deleteDriver(e);
        }
      });

      return false;
    },
    deleteDriver(e) {
      if (e) {
        driverService.Delete(e).then((response) => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            this.getResults();
            //redirect to login
            this.dialog = false;
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
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable(".table-main")) {
          $(".table-main").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3, -4, -5, -6, -7],
              },
            ],
            oLanguage: { sSearch: "", "sEmptyTable": "No driver till now.", "infoEmpty": "No driver found.", },
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
         $(".dataTables_filter").append($("#search-input-icon").html());
        $(".dataTables_filter input").attr("placeholder", "Search Driver by Name / Phone / Email");
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