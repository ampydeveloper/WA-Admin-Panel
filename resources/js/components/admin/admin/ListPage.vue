<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            Admin
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
          <router-link to="/admin/admin">
            Admin
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
            <router-link to="/admin/admin/add" class="nav-item nav-link">
              <v-btn color="success" class="btn-outline-green-top">
                <plus-icon size="1.5x" class="custom-class"></plus-icon>Add New
              </v-btn>
            </router-link>
          </div>
          <v-col cols="12" md="12" class="main-box-inner">
            <table id="admin-table" class="table table-striped table-bordered table-main">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Name</th>
                  <th class="text-left">Email</th>
                  <!-- <th class="text-left">Active</th> -->
                  <th class="text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in managers"
                  :key="item.name"
                  v-on:click="selectTr"
                  v-bind:class="{ 'selected' : isActive}"
                >
                  <template v-if="currentUser.id != item.id">
                    <td>{{index+1}}</td>
                    <td>
                      <div class="v-avatar v-list-item__avatar">
                        <img v-if="item.user_image" :src="'../'+item.user_image" class="small-img" />
                        <img v-if="!item.user_image" src="/images/avatar.png" class="small-img" />
                      </div>
                      {{ item.first_name }} {{ item.last_name }}
                    </td>
                    <td>{{ item.email }}</td>
                    <!-- <td>
                    <v-chip
                      v-if="!item.is_active"
                      class="ma-2"
                      color="red"
                      text-color="white"
                    >Deactivate</v-chip>
                    <v-chip
                      v-if="item.is_active"
                      class="ma-2"
                      color="green"
                      text-color="white"
                    >Activate</v-chip>
                    </td>-->
                    <td class="action-col">
                      <router-link :to="'/admin/admin/edit/' + item.id" class="nav-item nav-link">
                        <edit-3-icon size="1.2x" class="custom-class"></edit-3-icon>
                      </router-link>
                      <a href="javascript:void(0);" text @click="Delete(item.id)">
                        <trash-icon size="1.5x" class="custom-class"></trash-icon>
                      </a>
                    </td>
                  </template>
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
import { adminService } from "../../../_services/admin.service";
import { authenticationService } from "../../../_services/authentication.service";

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
      loading: false,
      dialog: false,
      on: false,
      managers: [],
      currentUser: "",
      triggerDropdown: null,
      isActive: false,
    };
  },
  getList() {},
  mounted: function () {
    this.currentUser = authenticationService.currentUserValue.data.user;
    this.listAdmin();
  },
  methods: {
    Action() {},

    //list admin
    listAdmin() {
      this.loading = true;
      adminService.listAdmin().then((response) => {
        //handle response
        if (response.status) {
          this.managers = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
        this.loading = false;
      });
    },

    Delete(e) {
      if (e) {
        this.loading = true;
        adminService.Delete(e).then((response) => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            //redirect to login
            this.dialog = false;

            //reload table
            this.listAdmin();
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
    selectTr: function () {
      this.isActive = !this.isActive;
    },
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        // $("#admin-table").DataTable({
        //   oLanguage: { sSearch: "" },
        //   drawCallback: function (settings) {
        //     $(".dataTables_paginate .paginate_button.previous").html(
        //       $("#table-chevron-left").html()
        //     );
        //     $(".dataTables_paginate .paginate_button.next").html(
        //       $("#table-chevron-right").html()
        //     );
        //   },
        // });
        // $(".dataTables_filter").append($("#search-input-icon").html());
        // $(".dataTables_filter input").attr(
        //   "placeholder",
        //   "Search Admin by Name / Email"
        // );
        // $(".dataTables_paginate .paginate_button.previous").html(
        //   $("#table-chevron-left").html()
        // );
        // $(".dataTables_paginate .paginate_button.next").html(
        //   $("#table-chevron-right").html()
        // );
        $("#admin-table").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>