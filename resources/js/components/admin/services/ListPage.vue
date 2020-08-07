<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            All Services
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
          <div class="add-icon">
            <router-link v-if="isAdmin" to="/admin/service/add" class="nav-item nav-link">
              <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
            </router-link>
            <router-link v-if="!isAdmin" to="/manager/service/add" class="nav-item nav-link">
              <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
            </router-link>
          </div>
          <v-col cols="12" md="12" id="#custom_tabel" class="main-box-inner">
            <!-- <div v-bar="{
    preventParentScroll: true,
    scrollThrottle: 30, }"> -->
            <table id="example" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Service Name</th>
                  <th class="text-left">Service Rate</th>
                  <th class="text-left">Price</th>
                  <th class="text-left">Type</th>
                  <th class="text-left">Time</th>
                  <th class="text-left">Description</th>
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
                  <td v-if="item.service_rate == 1">Per Load</td>
                  <td v-if="item.service_rate == 2">Round</td>
                  <td>${{ item.price }}</td>
                  <td>
                    <span v-for="(type, index) in item.slot_type">
                      <label v-if="type == 1">Morning</label>
                      <label v-if="type == 2">Afternoon</label>
                    </span>
                  </td>
                  <td>
                    <span v-for="(tSlot, index) in item.timeSlots">
                      <label>{{tSlot.slot_start+'-'+tSlot.slot_end}}</label>
                      <label v-if="item.timeSlots.length-1 != index">, &nbsp;</label>
                    </span>
                  </td>
                  <td id="description_txt">{{ item.description }}</td>
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
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { serviceService } from "../../../_services/service.service";
import { authenticationService } from "../../../_services/authentication.service";
import { environment } from "../../../config/test.env";
import {
  UserIcon,
  EditIcon,
  TrashIcon,
  PlusCircleIcon,
  MoreVerticalIcon,
  Edit3Icon,
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
export default {
  components: {
    UserIcon,
    EditIcon,
    TrashIcon,
    PlusCircleIcon,
    MoreVerticalIcon,
    Edit3Icon,
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
              position: "bottom-right",
            });
            //redirect to login
            this.dialog = false;
            //               router.push("/admin/service");
          } else {
            this.dialog = false;
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "bottom-right",
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
        $("#example").DataTable({
          aoColumnDefs: [
            {
              bSortable: false,
              aTargets: [-1, -2, -3, -4, -6],
            },
          ],
          // language: {
          //   paginate: {
          //     previous: "",
          //     next: "",
          //   },
          // },
          oLanguage: { sSearch: "" },
        });
        $("#example_wrapper .dataTables_filter input").attr(
          "placeholder",
          "Search Services"
        );
         $("#example_wrapper .dataTables_paginate .paginate_button.previous").text('');
         $("#example_wrapper .dataTables_paginate .paginate_button.next").text('');
      });
    }, 1000);
  },
};
</script>
