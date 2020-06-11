<template>
  <v-app>
    <v-container>
      <v-row>
<h4 class="main-title">List Manager</h4>
        <div class="add-icon">
          <router-link to="/admin/manager/add" class="nav-item nav-link">
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
                  <th class="text-left">Address</th>
                  <th class="text-left">Contact Number</th>
                  <th class="text-left">Email Address</th>
		  <th class="text-left">Salary</th>
                  <th class="text-left">Active</th>
                  <th class="text-left">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in managers" :key="item.name">
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
                   <router-link :to="'/admin/manager/view/' + item.id" class="nav-item nav-link">
                     {{ item.first_name }} {{ item.last_name }}
                    </router-link>
	           </td>
                  <td>{{ item.address }} {{ item.city }} {{ item.state }} {{ item.country }} {{ item.zip_code }}</td>
                  <td>{{ item.phone }}</td>
                  <td>{{ item.email }}</td>
		<td>${{ item.manager.salary }}</td>
                   <td>
                    <v-chip
                      v-if="!item.is_active"
                      class="ma-2"
                      color="red"
                      text-color="white"
                    >No</v-chip>
                    <v-chip
                      v-if="item.is_active"
                      class="ma-2"
                      color="green"
                      text-color="white"
                    >Yes</v-chip>
                  </td>
                  <td class="action-col">
                    <!-- <router-link :to="'/admin/manager/view/' + item.id" class="nav-item nav-link">
                      <user-icon size="1.5x" class="custom-class"></user-icon>
                    </router-link> -->
                    <router-link :to="'/admin/manager/edit/' + item.id" class="nav-item nav-link">
                      <!-- <edit-icon size="1.5x" class="custom-class"></edit-icon> -->
                      <span class="custom-action-btn">Edit</span>
                    </router-link>
                  
                    <v-btn color="blue darken-1" text @click="Delete(item.id)">
                      <!-- <trash-icon size="1.5x" class="custom-class"></trash-icon> -->
                       <span class="custom-action-btn">Delete</span>
                    </v-btn>
                    <!--              <v-menu
                bottom
                origin="center center"
                transition="scale-transition"
              >
            <template v-slot:activator="{ on }">
              <v-btn
                color="primary"
                dark
                v-on="on"
              >
                More
              </v-btn>
            </template>
            <v-list>
              <v-list-item>
                <v-list-item-title @click="Action()"  v-if="!item.is_active">Activate</v-list-item-title>
                <v-list-item-title @click="Action()"  v-if="item.is_active">Deactivate</v-list-item-title>
                <v-list-item-title v-on="on">
                <v-row justify="center">
                    <v-dialog v-model="dialog" persistent max-width="600px">
                      <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark v-on="on">Delete</v-btn>
                      </template>
                      <v-card>
                        <v-card-title>
                          <span class="headline">User Delete</span>
                        </v-card-title>
                        <v-card-text>
                          <v-container>
                            <v-row>
                                Are you sure you want delete this user?
                            </v-row>
                          </v-container>
                        </v-card-text>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn color="blue darken-1" text @click="Close">No</v-btn>
                          <v-btn color="blue darken-1" text @click="Delete(item.id)">Yes</v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                </v-row>
                </v-list-item-title>
              </v-list-item>
            </v-list>
                    </v-menu>-->
                  </td>
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
      managers: []
    };
  },
  mounted() {
    this.getResults();
  },
  methods: {
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
    }
  }
};
</script>
