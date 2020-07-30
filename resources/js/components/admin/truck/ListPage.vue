<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h4 class="main-title">Truck List</h4>
        <div class="add-icon">
          <router-link v-if="isAdmin" to="/admin/truck/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
          <router-link v-if="!isAdmin" to="/manager/truck/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
        <v-col cols="12" md="12">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Company</th>
                  <th class="text-left">Truck number</th>
                  <th class="text-left">Chassis number</th>
                  <th class="text-left">Total Km</th>
                  <th class="text-left">Service Details</th>
                  <th class="text-left">Documents</th>
                  <th class="text-left">Status</th>
                  <th class="text-left">Options</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in trucks" :key="item.name" v-on:click="selectTr(index)" v-bind:class="{ 'selected' : isActive == index}">
                  <td>
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/truck/view/' + item.id"
                      class="nav-item nav-link"
                    >{{item.company_name}}</router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/truck/view/' + item.id"
                      class="nav-item nav-link"
                    >{{item.company_name}}</router-link>
                  </td>
                  <td>{{item.truck_number}}</td>
                  <td>{{item.chaase_number}}</td>
                  <td>{{item.killometer}}</td>
                  <td>
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/truck/service/' + item.id"
                      class="nav-item nav-link"
                    >View Service</router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/truck/service/' + item.id"
                      class="nav-item nav-link"
                    >View Service</router-link>
                  </td>
                  <td>
                    <router-link
                      v-if="isAdmin"
                      :to="'/admin/truck/docview/' + item.id"
                      class="nav-item nav-link"
                    >View Documents</router-link>
                    <router-link
                      v-if="!isAdmin"
                      :to="'/manager/truck/docview/' + item.id"
                      class="nav-item nav-link"
                    >View Documents</router-link>
                  </td>
                  <td v-if="item.status == 1">Available</td>
                  <td v-if="item.status == 0">Unavailable</td>
                  <td class="action-col">
                    <div class="dropdown" v-bind:class="{ 'show': triggerDropdown == index }">
                      <more-vertical-icon size="1.5x" class="custom-class dropdown-trigger" v-on:click="dropdownToggle(index)"></more-vertical-icon>
                      <span class="dropdown-menu">
                        <router-link v-if="isAdmin" :to="'/admin/truck/edit/' + item.id" class="dropdown-item">
                          <button class="btn">Edit</button>
                        </router-link>
                        <router-link v-if="!isAdmin" :to="'/manager/truck/edit/' + item.id" class="dropdown-item">
                          <button class="btn">Edit</button>
                        </router-link>
                        <button class="btn dropdown-item" @click="Delete(item.id)">Delete</button>
                      </span>
                    </div>
                  </td>
                </tr>
                <tr v-if="trucks.length == 0">
                  <template>
                    No trucks till now.
                  </template>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { truckService } from "../../../_services/truck.service";
import {
  UserIcon,
  EditIcon,
  TrashIcon,
  PlusCircleIcon,
  MoreVerticalIcon
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    UserIcon,
    EditIcon,
    TrashIcon,
    PlusCircleIcon,
    MoreVerticalIcon
  },
  data() {
    return {
      dialog: false,
      triggerDropdown: null,
      isActive: null,
      on: false,
      trucks: [],
      isAdmin: true
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
      truckService.listTrucks().then(response => {
        //handle response
        if (response.status) {
          this.trucks = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right"
          });
        }
      });
    },
    Delete(e) {
      if (e) {
        truckService.Delete(e).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
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
              position: "top-right"
            });
          }
        });
      }
    },
    Close() {
      this.dialog = false;
    },
    dropdownToggle: function(setIndex) {
      //if same index is called up again then close it
      if(this.triggerDropdown == setIndex) {
        this.triggerDropdown = null;
      } else {
        this.triggerDropdown = setIndex;
      }
    },
    selectTr: function(rowIndex){
      this.isActive = rowIndex;
    },
  }
};
</script>
