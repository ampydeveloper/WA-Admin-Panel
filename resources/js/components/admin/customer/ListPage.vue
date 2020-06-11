<template>
  <v-app>
    <v-container>
      <v-row>
        <div class="add-icon">
          <router-link to="/admin/customer/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
        <v-col cols="12" md="12">
          <v-simple-table class="custom-table">
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="customer in customers" :key="customer.name">
                  <td>
                   <router-link :to="'/admin/customer/details/' + customer.id" class="nav-item nav-link">{{ customer.prefix }}               	{{ customer.first_name }} {{ customer.last_name }}</router-link> 
                    <v-data-table
                      :headers="headers"
                      :items="customer.customer_manager"
                      hide-default-footer
                      class="elevation-1"
                    >
                      <!-- <template slot="items" slot-scope="props">
                        <td class="text-xs-right">{{ props.index }}</td>
                        <td class="text-xs-right">{{ props.item.first_name }}</td>
                        <td class="text-xs-right">{{ props.item.phone }}</td>
                        <td class="text-xs-right">{{ props.item.email }}</td>
                        <td class="text-xs-right">{{ props.item.farms.farm_address }}</td>
                        <td class="text-xs-right">{{ props.item.farms.farm_city }}</td>
                        <td class="text-xs-right">{{ props.item.farms.farm_province }}</td>
                        <td class="text-xs-right">{{ props.item.farms.farm_zipcode }}</td>
                        <td class="text-xs-right">0</td>
                        <td class="text-xs-right">05/07/2020</td>
                      </template> -->
                    </v-data-table>
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
import { customerService } from "../../../_services/customer.service";
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
      search: "",

      headers: [
        {
          text: "Sno",
          align: "left",
          sortable: false,
          value: "index"
        },
        { text: "Manager", value: "first_name" },
        { text: "Phone Number", value: "phone" },
        { text: "Email", value: "email" },
        { text: "Farm Address", value: "farms.farm_address" },
        { text: "City", value: "farms.farm_city" },
        { text: "State/Province", value: "farms.farm_province" },
        { text: "Zip/Postal", value: "farms.farm_zipcode" },
        { text: "No Of Jobs", value: "" },
        { text: "Last Services", value: "" }
      ],
      items: [],
      customers: []
    };
  },
  getList() {},
  mounted() {
    this.getResults();
  },
  methods: {
    getTagNames: tags => {
      return tags.map(tag => tag.name);
    },
    getResults() {
      customerService.listCustomer().then(response => {
        //handle response
        if (response.status) {
          this.customers = response.data;
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
        customerService.Delete(e).then(response => {
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
            //router.push("/admin/customer");
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
