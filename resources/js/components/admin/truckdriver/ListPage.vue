<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h4 class="main-title text-left">Driver list</h4>
        <div class="add-icon">
          <router-link v-if="isAdmin" to="/admin/truckdriver/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
          <router-link v-if="!isAdmin" to="/manager/truckdriver/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
        <v-col cols="12" md="12">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Driver Name</th>
                  <th class="text-left mgr-add-col">Address</th>
                  <th class="text-left">Contact Number</th>
                  <th class="text-left">Email Address</th>
                  <th class="text-left">Driver License Number</th>
                  <th class="text-left">License Expiry Date</th>
                  <th class="text-left">Total Km</th>
                  <th class="text-left">Salary Rate</th>
                  <th class="text-left">Total Amount</th>
                  <th class="text-left">Active</th>
                  <th class="text-left">Options</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in drivers" :key="item.name" v-on:click="selectTr(index)" v-bind:class="{ 'selected' : isActive == index}">
                  <td>
                    <div
                      class="v-avatar v-list-item__avatar"
                      style="height: 40px; min-width: 40px; width: 40px;"
                    >
                      <img
                        v-if="item.user.user_image"
                        :src="'../../'+item.user.user_image"
                        alt="John"
                      />
                      <img v-if="!item.user.user_image" src="/images/avatar.png" alt="driver" />
                    </div>
                  </td>
                  <td>
                    <router-link
                      :to="'/admin/truckdriver/view/' + item.user.id"
                      class="nav-item nav-link"
                    >{{ item.user.first_name }}</router-link>
                  </td>
                  <td>{{ item.user.address }} {{ item.user.city }} {{ item.user.state }} {{ item.user.country }} {{ item.user.zip_code }}</td>
                  <td>{{ item.user.phone }}</td>
                  <td>{{ item.user.email }}</td>
                  <td>{{ item.driver_licence }}</td>
                  <td>{{ item.expiry_date | formatDateLic }}</td>
                  <td>0</td>
                  <td v-if="item.salary_type  == 0">${{ item.driver_salary }}/hr</td>
                  <td v-if="item.salary_type  == 1">${{ item.driver_salary }}/Per load</td>
                  <td>0</td>
                  <td>
                    <v-chip
                      v-if="!item.user.is_active"
                      class="ma-2"
                      color="red"
                      text-color="white"
                    >No</v-chip>
                    <v-chip
                      v-if="item.user.is_active"
                      class="ma-2"
                      color="green"
                      text-color="white"
                    >Yes</v-chip>
                  </td>

                  <td class="action-col">
                    <!-- <router-link
                      :to="'/admin/truckdriver/view/' + item.user.id"
                      class="nav-item nav-link"
                    >
                      <user-icon size="1.5x" class="custom-class"></user-icon>
                    </router-link>-->
                    <div class="dropdown" v-bind:class="{ 'show': triggerDropdown == index }">
                      <more-vertical-icon size="1.5x" class="custom-class dropdown-trigger" v-on:click="dropdownToggle(index)"></more-vertical-icon>
                      <span class="dropdown-menu">
                        <router-link v-if="isAdmin" :to="'/admin/truckdriver/edit/' + item.id" class="dropdown-item">
                          <button class="btn">Edit</button>
                        </router-link>
                        <router-link v-if="!isAdmin" :to="'/manager/truckdriver/edit/' + item.id" class="dropdown-item">
                          <button class="btn">Edit</button>
                        </router-link>
                        <button class="btn dropdown-item" @click="Delete(item.id)">Delete</button>
                      </span>
                    </div>
                  </td>
                </tr>
                <tr v-if="drivers.length == 0">
                  <template>
                    No driver till now.
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
import { driverService } from "../../../_services/driver.service";
import { authenticationService } from "../../../_services/authentication.service";
import {
  UserIcon,
  EditIcon,
  TrashIcon,
  PlusCircleIcon,
  MoreVerticalIcon
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
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
      isActive: null,
      on: false,
      drivers: [],
      isAdmin: true,
      triggerDropdown: null
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
    selectTr: function(rowIndex){
      this.isActive = rowIndex;
    },
    getResults() {
      driverService.listDrivers().then(response => {
        //handle response
        if (response.status) {
          this.drivers = response.data;
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
        driverService.Delete(e).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
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
    }
  }
};
</script>
