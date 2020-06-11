<template>
  <v-app>
    <v-container>
      <v-row>
        <h4 class="main-title">Services List</h4>
        <div class="add-icon">
          <router-link to="/admin/service/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
        <v-col cols="12" md="12">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Service Name</th>
                  <th class="text-left">Service Rate</th>
                  <th class="text-left">Price</th>
                  <th class="text-left">type</th>
                  <th class="text-left">time</th>
                  <th class="text-left">Descriptions</th>
                  <th class="text-left">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in services" :key="item.name">
                  <td>
                    <div
                      class="v-avatar v-list-item__avatar"
                      style="height: 40px; min-width: 40px; width: 40px;"
                    >
                      <img :src="'../../'+item.service_image" alt="John" />
                    </div>
                  </td>
                  <td>{{ item.service_name }}</td>
                  <td v-if="item.service_rate == 1">Per Load</td>
                  <td v-if="item.service_rate == 2">Round</td>
                  <td>${{ item.price }}</td>
                  <td v-if="item.slot_type == 1">Morning</td>
                  <td v-if="item.slot_type == 2">Afternoon</td>
                  <td>
                    <span v-for="(tSlot, index) in item.timeSlots">
                      <label>{{tSlot.slot_start+'-'+tSlot.slot_end}}</label>
                      <label v-if="item.timeSlots.length-1 != index">, &nbsp;</label>
                    </span>
                  </td>
                  <td>{{ item.description }}</td>
                  <td class="action-col">
                    <!-- <router-link :to="'/admin/service/view/' + item.id" class="nav-item nav-link">
                      <user-icon size="1.5x" class="custom-class"></user-icon>
                    </router-link>-->
                    <router-link :to="'/admin/service/edit/' + item.id" class="nav-item nav-link">
                      <!-- <edit-icon size="1.5x" class="custom-class"></edit-icon> -->
                      <span class="custom-action-btn">Edit</span>
                    </router-link>
                    <v-btn color="blue darken-1" text @click="Delete(item.id)">
                      <!-- <trash-icon size="1.5x" class="custom-class"></trash-icon> -->
                      <span class="custom-action-btn">Delete</span>
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
import { jobService } from "../../../_services/job.service";
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
      services: []
    };
  },
  mounted() {
    this.getResults();
  },

  methods: {
    getResults() {
      jobService.listService().then(response => {
        //handle response
        if (response.status) {
          this.services = response.data;
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
        jobService.Delete(e).then(response => {
          //handle response
          if (response.status) {
            this.getResults();
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
