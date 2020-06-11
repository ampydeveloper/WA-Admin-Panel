<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <router-link to="/admin/admin/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </v-col>
        <v-col cols="12" md="12">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Name</th>
                  <th class="text-left">Email</th>
                  <!-- <th class="text-left">Active</th> -->
                  <th class="text-left">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in managers" :key="item.name">
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
                  <td>
                    <!-- <router-link :to="'/admin/admin/view/' + item.id" class="nav-item nav-link">
                      <user-icon size="1.5x" class="custom-class"></user-icon>
                    </router-link> -->
                    <router-link :to="'/admin/admin/edit/' + item.id" class="nav-item nav-link">
                      <edit-icon size="1.5x" class="custom-class"></edit-icon>
                    </router-link>
                    <v-btn color="blue darken-1" text @click="Delete(item.id)">
                      <trash-icon size="1.5x" class="custom-class"></trash-icon>
                    </v-btn>
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
  PlusCircleIcon
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
export default {
  components: {
    UserIcon,
    EditIcon,
    TrashIcon,
    PlusCircleIcon
  },
  data() {
    return {
      dialog: false,
      on: false,
      managers: [],
      currentUser: ''
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
      });
    },

    Delete(e) {
      if (e) {
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
        });
      }
    },
    Close() {
      this.dialog = false;
    }
  }
};
</script>
