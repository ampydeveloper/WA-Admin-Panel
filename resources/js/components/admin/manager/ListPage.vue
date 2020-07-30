<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h4 class="main-title">List Manager</h4>
        <div class="add-icon">
          <router-link v-if="isAdmin" to="/admin/manager/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
          <router-link v-if="!isAdmin" to="/manager/manager/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
        <v-col cols="12" md="12">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Manager Name</th>
                  <th class="text-left mgr-add-col">Address</th>
                  <th class="text-left">Contact Number</th>
                  <th class="text-left">Email Address</th>
                  <th class="text-left">Salary</th>
                  <th class="text-left">Active</th>
                  <th class="text-left">Options</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in managers" :key="item.name" v-on:click="selectTr(index)" v-bind:class="{ 'selected' : isActive == index}">
                  <template v-if="checkuser != item.id">
                    <td>
                      <div
                        class="v-avatar v-list-item__avatar"
                        style="height: 40px; min-width: 40px; width: 40px;"
                      >
                        <img v-if="item.user_image" :src="'../'+item.user_image" alt="John" />
                        <img v-if="!item.user_image" src="/images/avatar.png" alt="driver" />
                      </div>
                    </td>
                    <td>
                      <router-link
                        :to="'/admin/manager/view/' + item.id"
                        class="nav-item nav-link"
                      >{{ item.first_name }} {{ item.last_name }}</router-link>
                    </td>
                    <td>{{ item.address }} {{ item.city }} {{ item.state }} {{ item.country }} {{ item.zip_code }}</td>
                    <td>{{ item.phone }}</td>
                    <td>{{ item.email }}</td>
                    <td>${{ item.manager.salary }}</td>
                    <td>
                      <v-chip v-if="!item.is_active" class="ma-2" color="red" text-color="white">No</v-chip>
                      <v-chip
                        v-if="item.is_active"
                        class="ma-2"
                        color="green"
                        text-color="white"
                      >Yes</v-chip>
                    </td>
                    <td class="action-col">
                        <!-- <edit-icon size="1.5x" class="custom-class"></edit-icon> -->
                        <!-- <trash-icon size="1.5x" class="custom-class"></trash-icon> -->

                      <div class="dropdown" v-bind:class="{ 'show': triggerDropdown == index }">
                        <more-vertical-icon size="1.5x" class="custom-class dropdown-trigger" v-on:click="dropdownToggle(index)"></more-vertical-icon>
                        <span class="dropdown-menu">
                          <router-link v-if="isAdmin" :to="'/admin/manager/edit/' + item.id" class="dropdown-item">
                            <button class="btn">Edit</button>
                          </router-link>
                          <router-link v-if="!isAdmin" :to="'/manager/manager/edit/' + item.id" class="dropdown-item">
                            <button class="btn">Edit</button>
                          </router-link>
                          <button class="btn dropdown-item" @click="Delete(item.id)">Delete</button>
                        </span>
                      </div>
                    </td>
                  </template>
                </tr>
                <tr v-if="managers.length == 0">
                  <template>
                    No manager till now.
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
import { managerService } from "../../../_services/manager.service";
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
      triggerDropdown: null,
      on: false,
      isActive: null,
      managers: [],
      isAdmin: true,
      checkuser: ""
    };
  },
  mounted() {
    const currentUser = authenticationService.currentUserValue;
    this.checkuser = currentUser.data.user.id;
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
      managerService.listService().then(response => {
        //handle response
        if (response.status) {
          this.managers = response.data;
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
        managerService.Delete(e).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            this.dialog = false;
            //load new data
            this.getResults();
            //               router.push("/admin/manager");
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
