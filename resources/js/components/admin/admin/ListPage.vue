<template>
  <v-app>
    <v-container>
      <v-row>
        <div class="add-icon">
          <router-link to="/admin/admin/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
        <v-col cols="12" md="12" class="mt-5">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Name</th>
                  <th class="text-left">Email</th>
                  <!-- <th class="text-left">Active</th> -->
                  <th class="text-left">Options</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in managers" :key="item.name" v-on:click="selectTr" v-bind:class="{ 'selected' : isActive}">
		<template v-if="currentUser.id != item.id">
                  <td>
                    <div
                      class="v-avatar v-list-item__avatar"
                      style="height: 40px; min-width: 40px; width: 40px;"
                    >
                      <img v-if="item.user_image" :src="'../'+item.user_image" />
                      <img v-if="!item.user_image" src="/images/avatar.png" />
                    </div>
                  </td>
                  <td>{{ item.first_name }} {{ item.last_name }}</td>
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
                  </td> -->
                  <td class="action-col">
                    <div class="dropdown" v-bind:class="{ 'show': triggerDropdown == index }">
                      <more-vertical-icon size="1.5x" class="custom-class dropdown-trigger" v-on:click="dropdownToggle(index)"></more-vertical-icon>
                      <span class="dropdown-menu">
                        <router-link :to="'/admin/admin/edit/' + item.id" class="dropdown-item">
                          <button class="btn">Edit</button>
                        </router-link>
                        <button class="btn dropdown-item" v-if="item.id != 1" text @click="Delete(item.id)">Delete</button>
                      </span>
                    </div>
                  </td>
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
import { adminService } from "../../../_services/admin.service";
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
     loading: false,
      dialog: false,
      on: false,
      managers: [],
      currentUser: '',
      triggerDropdown: null,
      isActive: false,
    };
  },
  getList() {},
  mounted: function() {
  this.currentUser = authenticationService.currentUserValue.data.user;
    this.listAdmin();
  },
  methods: {
    Action() {},

    //list admin
    listAdmin() {
      this.loading = true;
      adminService.listAdmin().then(response => {
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
       this.loading = false;
      });
    },

    Delete(e) {
      if (e) {
       this.loading = true;
        adminService.Delete(e).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
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
              position: "top-right"
            });
          }
         this.loading = false;
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
    selectTr: function(){
      this.isActive = !this.isActive;
    }
  }
};
</script>
