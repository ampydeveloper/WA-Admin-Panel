<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            FAQ
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
          <router-link to="/admin/faq">
            FAQ
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
            <router-link to="/admin/faq/add" class="nav-item nav-link">
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
                  <th class="text-left">Question</th>
                  <th class="text-left">Answer</th>
                  <th class="text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in faqs"
                  :key="item.name"
                >
                  <template v-if="currentUser.id != item.id">
                    <td>{{index+1}}</td>
                    <td>
                      {{ item.question }}
                    </td>
                    <td>{{ item.answer }}</td>
                    <td class="action-col">
                      <router-link :to="'/admin/faq/edit/' + item.id" class="nav-item nav-link">
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
import { faqService } from "../../../_services/faq.service";
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
      faqs: [],
      currentUser: "",
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

    //list faq
    getResults() {
      faqService.listFaq().then((response) => {
        //handle response
        if (response.status) {
          this.faqs = response.data;
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
        text: "Are you sure you want to delete this FAQ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes Delete it!",
        cancelButtonText: "No, Keep it!",
        showCloseButton: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        if (result.value) {
          this.deletefaq(e);
        }
      });
      return false;
    },
    deletefaq(e) {
      if (e) {
        this.loading = true;
        faqService.deleteFaq(e).then((response) => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: 'Faq deleted successfully.',
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
    Close() {
      this.dialog = false;
    },
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable("#admin-table")) {
          $("#admin-table").DataTable({
            oLanguage: {
              sSearch: "",
              sEmptyTable: "No FAQ till now.",
              sInfoEmpty: "No FAQ found.",
            },
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
        $(".dataTables_filter input").attr(
          "placeholder",
          "Search FAQ by Question / Answer"
        );
        $(".dataTables_paginate .paginate_button.previous").html(
          $("#table-chevron-left").html()
        );
        $(".dataTables_paginate .paginate_button.next").html(
          $("#table-chevron-right").html()
        );
        $("#admin-table").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>