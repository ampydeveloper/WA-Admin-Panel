<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Hauler
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
          <router-link to="/admin/hauler">
            Hauler
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
            <router-link v-if="isAdmin" to="/admin/hauler/add" class="">
               <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/hauler/add" class="">
               <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
          </div>
          <v-col cols="12" md="12" id="manager_wrap" class="main-box-inner">
            <table id="hauler-table" class="table table-striped table-bordered table-main">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Hauler Name</th>
                  <th class="text-left">Email</th>
                  <th class="text-left">Phone</th>
                  <th class="text-left">Address</th>
             
                  <th class="text-left">Active</th>
                  <th class="text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(customer, index) in customers">
                  <td>{{index+1}}</td>
                  <td>
                    <div
                      class="v-avatar v-list-item__avatar"
                    >
                      <img v-if="customer.user_image" class="small-img" :src="'../'+customer.user_image" />
                      <img v-if="!customer.user_image" class="small-img" src="/images/avatar.png" />
                    </div>

                    {{customer.prefix}} {{customer.first_name}}
                  </td>
                  <td>{{customer.email}}</td>
                  <td>{{customer.phone}}</td>
                  <td>{{customer.address}} {{customer.city}} {{customer.state}}, {{customer.zip_code}}</td>
               
                  <td>
                    <span v-if="!customer.is_active" class="badges-item">No</span>
                    <span v-if="customer.is_active" class="badges-item">Yes</span>
                  </td>
                  <td class="action-col">
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/hauler/drivers/list/' + customer.id"
                      class="btn-outline-green-top"
                    >
                      View Drivers
                    </router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/hauler/drivers/list/' + customer.id"
                      class="btn-outline-green-top"
                    >
                      View Drivers
                    </router-link>
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/hauler/edit/' + customer.id"
                      class="nav-item nav-link"
                    >
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/hauler/edit/' + customer.id"
                      class="nav-item nav-link"
                    >
                      <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                    </router-link>

                    <a href="javascript:void(0);" text @click="Delete(customer.id)">
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
import { companyService } from "../../../_services/company.service";
import { authenticationService } from "../../../_services/authentication.service";
import {
  UserIcon,
  Edit3Icon,
  TrashIcon,
  PlusIcon,
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
    ChevronLeftIcon,
    ChevronRightIcon,
    SearchIcon,
  },
  data() {
    return {
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
      companyService.listHauler().then((response) => {
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
      this.$swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this hauler?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes Delete it",
        cancelButtonText: "No, Keep it",
        showCloseButton: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        if (result.value) {
          this.deleteHauler(e);
        }
      });

      return false;
    },
    deleteHauler(e) {
      if (e) {
        companyService.deleteHauler(e).then((response) => {
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
            //router.push("/admin/company");
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
          aoColumnDefs: [
            {
              bSortable: false,
              aTargets: [-1, -2, -3, -4, -5, -6],
            },
          ],
          oLanguage: { sSearch: "", "sEmptyTable": "No company till now.", "infoEmpty": "No company found.", },
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
        $(".dataTables_filter input").attr("placeholder", "Search Haulers by Name / Email / Phone");
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
