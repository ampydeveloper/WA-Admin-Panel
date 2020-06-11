<template>
  <v-app>
    <v-container>
      <v-row>
        <v-tabs class="custom-tabs" v-model="tab" background-color="transparent" color="basil" grow>
          <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab" class="custom-tab-content">
          <v-tab-item v-for="item in items" :key="item">
            <v-card class="service-tab-content" color="basil" flat v-if="item == 'Service'">
              <v-card-text>
                <v-data-table
                  :headers="headers"
                  :items="truck"
                  :sort-desc="[false, true]"
                  multi-sort
                  class="elevation-1"
                >
                  <template v-slot:item.id="{ item }">
                     <span class="custom-action-btn"> <router-link :to="'/admin/truck/editservice/' +item.id">
               Edit
              </router-link></span>
                    <span class="custom-action-btn" @click="DeleteService(item.id)">Delete</span>
                  </template>
                </v-data-table>
              </v-card-text>
              <router-link :to="'/admin/truck/addservice/' +vehicle_id" class="nav-item nav-link">
                <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Service</v-btn>
              </router-link>
            </v-card>
            <v-card class="insurance-tab-content" color="basil" flat v-if="item == 'Insurance'">
              <v-card-text>
                <v-data-table
                  :headers="headers1"
                  :items="insurance"
                  :sort-desc="[false, true]"
                  multi-sort
                  class="elevation-1"
                >
                  <template v-slot:item.id="{ item }">
                    <span class="custom-action-btn"> <router-link :to="'/admin/truck/editinsurance/' +item.id">
               Edit
              </router-link></span>
                    <span class="custom-action-btn" @click="Delete(item.id)">Delete</span>
                  </template>
                </v-data-table>
              </v-card-text>
              <router-link :to="'/admin/truck/addinsurance/' +vehicle_id" class="nav-item nav-link">
                <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4">Add Insurance</v-btn>
              </router-link>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { truckService } from "../../../_services/truck.service";
import { PlusCircleIcon } from "vue-feather-icons";
export default {
  components: {
    PlusCircleIcon
  },
  data() {
    return {
      tab: null,
      items: ["Service", "Insurance"],
      headers: [
        { text: "Servicekm", value: "service_killometer" },
        {
          text: "Date",
          align: "start",
          sortable: false,
          value: "service_date"
        },
        { text: "Action", value: "id" }
      ],
      headers1: [
        {
          text: "Insurance Name",
          align: "start",
          sortable: false,
          value: "insurance_number"
        },
        { text: "Insurance Date", value: "insurance_date" },
        { text: "Insurance Expiry Date", value: "insurance_expiry" },
        { text: "Action", value: "id" }
      ],
      avatar: null,
      truck: [],
      insurance: [],
      vehicle_id: ""
    };
  },
 
  mounted() {
  this.getResults();
  },
  methods: {
	getResults() {
  this.vehicle_id = this.$route.params.id;
    truckService.getTruckService(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        this.truck = response.data;
      } else {
        router.push("/admin/trucks");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
    });

    truckService.getTruckInsurance(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        this.insurance = response.data;
      } else {
        router.push("/admin/trucks");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
    });
	},
    DeleteService(e) {
      if (e) {
        truckService.DeleteService(e).then(response => {
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
            //router.push("/admin/manager");
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
  Delete(e) {
      if (e) {
        truckService.DeleteInsurance(e).then(response => {
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
            //router.push("/admin/manager");
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
  }
};
</script>
