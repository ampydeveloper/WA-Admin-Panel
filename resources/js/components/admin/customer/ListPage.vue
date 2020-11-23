<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Customers
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
          <router-link to="/admin/customer">
            Customers
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
            <router-link v-if="isAdmin" to="/admin/customer/add" class>
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/customer/add" class>
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
          </div>
          <v-col cols="12" md="12" class="main-box-inner">
            <table id="customer-table" class="table table-bordered table-main">
              <thead>
                <tr>
                  <th class="text-left"></th>
                </tr>
              </thead>
              <tbody>
                <tr class="multi-customers" v-for="customer in customers" :key="customer.name">
                  <td class="single-customer">
                    <span class="c-title">
                      <span class="c-title-label">Customer</span>
                      <span
                        class="c-title-name"
                      >{{ customer.prefix }} {{ customer.first_name }} {{ customer.last_name }}</span>
                      <span class="c-title-edit">
                        <router-link
                          v-if="isAdmin"
                          :to="'/admin/customer/details/' + customer.id"
                          class="btn-outline-green-top btn-o-sm"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon> Edit
                        </router-link>
                        <router-link
                          v-if="!isAdmin"
                          :to="'/manager/customer/details/' + customer.id"
                          class="btn-outline-green-top btn-o-sm"
                        >
                          <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon> Edit
                        </router-link>
                      </span>
                    </span>

                    <v-simple-table>
                      <thead>
                        <tr>
                          <th class="farm-th">Farm Location</th>
                          <th class="manager-th">Primary Manager / Phone / Email</th>
                          <th>Est. Jobs</th>
                          <th>Last Serviced</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(farm, index) in customer.farmlist">
                          <td>
                            <span>
                              <!-- <img src :src="baseUrl+farm.farm_image" /> -->
                              <span class="basic-grey">{{farm.farm_address}} {{farm.farm_city}}</span>
                              <span class="basic-grey">{{farm.farm_province}} {{farm.farm_zipcode}}</span>
                            </span>
                          </td>
                          <td>
                            <span v-for="manager in farm.farm_manager">
                              <span class="basic-big">{{manager.first_name}}</span>
                              <span class="basic-grey">{{manager.phone}}</span>
                              <span class="basic-grey">{{manager.email}}</span>
                            </span>
                          </td>
                          <td>
                            <span class="badges-item">0</span>
                          </td>
                          <td>{{'2020-06-23' | formatDateLic}}</td>
                        </tr>
                      </tbody>
                    </v-simple-table>
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
import { customerService } from "../../../_services/customer.service";
import { authenticationService } from "../../../_services/authentication.service";
import { environment } from "../../../config/test.env";
import {
  UserIcon,
  TrashIcon,
  PlusIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  Edit3Icon,
  SearchIcon,
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";

export default {
  components: {
    UserIcon,
    TrashIcon,
    PlusIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    Edit3Icon,
    SearchIcon,
  },
  data() {
    return {
      search: "",
      baseUrl: environment.baseUrl,
      headers: [
        {
          text: "Sno",
          align: "left",
          sortable: false,
          value: "index",
        },
        { text: "Manager", value: "farm_manager.first_name" },
        { text: "Phone Number", value: "farm_manager.phone" },
        { text: "Email", value: "farm_manager.email" },
        { text: "Farm Address", value: "farm_address" },
        { text: "City", value: "farm_city" },
        { text: "State/Province", value: "farm_province" },
        { text: "Zip/Postal", value: "farm_zipcode" },
        { text: "No Of Jobs", value: "" },
        { text: "Last Services", value: "" },
      ],
      items: [],
      customers: [],
      isAdmin: true,
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
    getTagNames: (tags) => {
      return tags.map((tag) => tag.name);
    },
    getResults() {
      customerService.listCustomer().then((response) => {
        //handle response
        if (response.status) {
          this.customers = response.data;
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
        customerService.Delete(e).then((response) => {
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
            //router.push("/admin/customer");
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
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        $(".table-main").DataTable({
          oLanguage: { sSearch: "", "sEmptyTable": "No customer till now.", "infoEmpty": "No customer found.", },
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
        $(".dataTables_filter input").attr("placeholder", "Search Customers by Name / Farm Location / Manager");
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
