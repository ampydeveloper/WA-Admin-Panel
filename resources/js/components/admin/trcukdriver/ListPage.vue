<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <router-link to="/admin/truckdriver/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </v-col>
        <v-col cols="12" md="12">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left"></th>
                  <th class="text-left">Driver Name</th>
                  <th class="text-left">Email</th>
                  <th class="text-left">Active</th>
                  <th class="text-left">Licence</th>
                  <th class="text-left">expiry_date</th>
                  <th class="text-left">salary_type</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in drivers" :key="item.name">
                  <td>
                    <div
                      class="v-avatar v-list-item__avatar"
                      style="height: 40px; min-width: 40px; width: 40px;"
                    >
                      <img v-if="item.user.user_image" :src="'../../'+item.user.user_image" alt="John" />
                      <img v-if="!item.user.user_image" src="/images/avatar.png" alt="driver" />
                    </div>
                  </td>
                  <td>{{ item.user.first_name }}</td>
                  <td>{{ item.user.email }}</td>
                  <td>
                    <v-chip
                      v-if="!item.user.is_active"
                      class="ma-2"
                      color="red"
                      text-color="white"
                    >Deactivate</v-chip>
                    <v-chip
                      v-if="item.user.is_active"
                      class="ma-2"
                      color="green"
                      text-color="white"
                    >Activate</v-chip>
                  </td>

                  <td>{{ item.driver_licence }}</td>
                  <td>{{ item.expiry_date }}</td>
                  <td>{{ item.salary_type }}</td>
                  <td>
                    <router-link
                      :to="'/admin/truckdriver/view/' + item.user.id"
                      class="nav-item nav-link"
                    >
                      <user-icon size="1.5x" class="custom-class"></user-icon>
                    </router-link>
                    <router-link
                      :to="'/admin/truckdriver/edit/' + item.user.id"
                      class="nav-item nav-link"
                    >
                      <edit-icon size="1.5x" class="custom-class"></edit-icon>
                    </router-link>
                    <v-btn color="blue darken-1" text @click="Delete(item.user.id)">
                      <trash-icon size="1.5x" class="custom-class"></trash-icon>
                    </v-btn>
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
import { driverService } from "../../../_services/driver.service";
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
      drivers: []
    };
  },
  getList() {},
  mounted: function() {
    driverService.listDrivers().then(response => {
      //handle response
      if (response.status) {
        console.log(response.data);
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
  methods: {
    Action() {},
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
            //redirect to login
            this.dialog = false;
            //               router.push("/admin/service");
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
