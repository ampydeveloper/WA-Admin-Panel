<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            Service Details
            <span class="right-bor"></span>
          </h4>
        </li>
        <li>
          <router-link :to="isMechanic ? '/mechanic/skidsteers/': '/admin/dashboard'" class="home_svg">
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
          <div class="add-icon">
            <!-- <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon> -->

            <router-link
              v-if="isAdmin"
              :to="'/admin/skidsteer/addservice/' +vehicle_id"
              class="nav-item nav-link"
            >
              <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Service</v-btn>
            </router-link>
            <router-link
              v-else-if="isMechanic"
              :to="'/mechanic/skidsteer/addservice/' +vehicle_id"
              class="nav-item nav-link"
            >
              <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Service</v-btn>
            </router-link>
            <router-link
              v-else
              :to="'/manager/skidsteer/addservice/' +vehicle_id"
              class="nav-item nav-link"
            >
              <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Service</v-btn>
            </router-link>

            <router-link
              v-if="isAdmin"
              :to="'/admin/skidsteer/addinsurance/' +vehicle_id"
              class="nav-item nav-link"
            >
              <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Insurance</v-btn>
            </router-link>
            <router-link
              v-else-if="isMechanic"
              :to="'/mechanic/skidsteer/addinsurance/' +vehicle_id"
              class="nav-item nav-link"
            >
              <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Insurance</v-btn>
            </router-link>
            <router-link
              v-else
              :to="'/manager/skidsteer/addinsurance/' +vehicle_id"
              class="nav-item nav-link"
            >
              <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Insurance</v-btn>
            </router-link>
          </div>

          <v-tabs
            v-model="tab"
            class="custom-tabs-wdout-modif c-tabs-50"
            background-color="transparent"
            color="#56bf7a"
            grow
          >
            <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab" class="custom-tab-content">
            <v-tab-item v-for="item in items" :key="item">
              <v-card class="service-tab-content" color="basil" flat v-if="item == 'Mechanic Service'">
                <table
                  id="truck-service-table"
                  class="table table-striped table-bordered table-main"
                >
                  <thead>
                    <tr>
                      <th class="text-left">#</th>
                      <th class="text-left">Service Date</th>
                      <th class="text-left">Service Miles</th>
                      <th class="text-left">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(item, index) in truck"
                      :key="item.name"
                      v-bind:class="{ 'selected' : isActive == index}"
                    >
                      <td>{{index+1}}</td>
                      <td>{{ item.service_date | formatDateLic}}</td>
                      <td>{{ item.service_killometer }}</td>

                      <td class="action-col">
                        <router-link
                          v-if="isAdmin"
                          :to="'/admin/skidsteer/editservice/' + item.id"
                          class="nav-item nav-link"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                        </router-link>
                        <router-link
                          v-else-if="isMechanic"
                          :to="'/mechanic/skidsteer/editservice/' + item.id"
                          class="nav-item nav-link"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                        </router-link>
                        <router-link
                          v-else
                          :to="'/manager/skidsteer/editservice/' + item.id"
                          class="nav-item nav-link"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                        </router-link>
                        <a href="javascript:void(0);" text @click="DeleteS(item.id)">
                          <trash-icon size="1.5x" class="custom-class"></trash-icon>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </v-card>
              <v-card class="insurance-tab-content" color="basil" flat v-if="item == 'Vehicle Insurance'">
                <table
                  id="truck-insurance-table"
                  class="table table-striped table-bordered table-main"
                >
                  <thead>
                    <tr>
                      <th class="text-left">#</th>
                      <th class="text-left">Number</th>
                      <th class="text-left">Insurance Date</th>
                      <th class="text-left">Insurance Expiry Date</th>
                      <th class="text-left">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(item, index) in insurance"
                      :key="item.insurance_number"
                      v-bind:class="{ 'selected' : isActive == index}"
                    >
                      <td>{{index+1}}</td>
                      <td>{{item.insurance_number}}</td>
                      <td>{{item.insurance_date | formatDateLic}}</td>
                      <td>{{item.insurance_expiry | formatDateLic}}</td>

                      <td class="action-col">
                        <router-link
                          v-if="isAdmin"
                          :to="'/admin/skidsteer/editinsurance/' + item.id"
                          class="nav-item nav-link"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                        </router-link>
                        <router-link
                          v-else-if="isMechanic"
                          :to="'/mechanic/skidsteer/editinsurance/' + item.id"
                          class="nav-item nav-link"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                        </router-link>
                        <router-link
                          v-else
                          :to="'/manager/skidsteer/editinsurance/' + item.id"
                          class="nav-item nav-link"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                        </router-link>
                        <a href="javascript:void(0);" text @click="DeleteI(item.id)">
                          <trash-icon size="1.5x" class="custom-class"></trash-icon>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
  </v-app>
</template>

<script>
import { truckService } from "../../../_services/truck.service";
import {
  PlusCircleIcon,
  Edit3Icon,
  TrashIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
   SearchIcon,
} from "vue-feather-icons";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon,
    Edit3Icon,
    TrashIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
     SearchIcon,
  },
  data() {
    return {
      tab: null,
      isActive: null,
      items: ["Mechanic Service", "Vehicle Insurance"],
      avatar: null,
      truck: [],
      insurance: [],
      vehicle_id: "",
      isAdmin: false,
      isMechanic: false
    };
  },

  mounted() {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else if (currentUser.data.user.role_id == 8) {
      this.isMechanic = true;
    } else {
      this.isAdmin = false;
    }
    this.getResults();
  },
  methods: {
    getResults() {
      this.vehicle_id = this.$route.params.id;
      truckService.getTruckService(this.$route.params.id).then((response) => {
        //handle response
        if (response.status) {
          this.truck = response.data;
        } else {
          const currentUser = authenticationService.currentUserValue;
          if (currentUser.data.user.role_id == 1) {
            router.push("/admin/trucks");
          } else if (currentUser.data.user.role_id == 8) {
            router.push("/mechanic/trucks");
          } else {
            router.push("/manager/trucks");
          }
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });

      truckService.getTruckInsurance(this.$route.params.id).then((response) => {
        //handle response
        if (response.status) {
          this.insurance = response.data;
        } else {
          const currentUser = authenticationService.currentUserValue;
          if (currentUser.data.user.role_id == 1) {
            router.push("/admin/trucks");
          } else if (currentUser.data.user.role_id == 8) {
            router.push("/mechanic/trucks");
          } else {
            router.push("/manager/trucks");
          }

          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    DeleteS(e) {
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
        truckService.DeleteService(e).then((response) => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            //redirect to login
            this.dialog = false;
            //load new data
            this.getResults();
            //router.push("/admin/manager");
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
    DeleteI(e) {
      this.$swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this insurance?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes Delete it!",
        cancelButtonText: "No, Keep it!",
        showCloseButton: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        if (result.value) {
          this.deleteInsurance(e);
        }
      });

      return false;
    },
    deleteInsurance(e) {
      if (e) {
        truckService.DeleteInsurance(e).then((response) => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            //redirect to login
            this.dialog = false;
            //load new data
            this.getResults();
            //router.push("/admin/manager");
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
    selectTr: function (rowIndex) {
      this.isActive = rowIndex;
    },
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable("#truck-insurance-table")) {
          $("#truck-insurance-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3],
              },
            ],
            oLanguage: { sSearch: "" },
            drawCallback: function (settings) {
              $(
                "#truck-insurance-table_paginate .paginate_button.previous"
              ).html($("#table-chevron-left").html());
              $("#truck-insurance-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });

          $("#truck-insurance-table_filter input").attr(
            "placeholder",
            "Search Insurance"
          );
            $("#truck-insurance-table_filter").append($("#search-input-icon").html());
          $("#truck-insurance-table_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $("#truck-insurance-table_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
          $("#truck-insurance-table").css({ opacity: 1 });
        }
        if (!$.fn.dataTable.isDataTable("#truck-service-table")) {
          $("#truck-service-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2],
              },
            ],
            oLanguage: { sSearch: "" },
            drawCallback: function (settings) {
              $("#truck-service-table_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
              );
              $("#truck-service-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });

          $("#truck-service-table_filter input").attr(
            "placeholder",
            "Search Service"
          );
            $("#truck-service-table_filter").append($("#search-input-icon").html());
          $("#truck-service-table_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $("#truck-service-table_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
          $("#truck-service-table").css({ opacity: 1 });
        }
      });
    }, 1000);
  },
};
</script>
