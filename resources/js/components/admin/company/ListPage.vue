<template>
  <v-app>
    <v-container>
      <v-row>
        <div class="add-icon">
          <router-link to="/admin/company/add" class="nav-item nav-link">
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
                <tr v-for="item in customers" :key="item.name">
                  <td>
                    <div
                      class="v-avatar v-list-item__avatar"
                      style="height: 40px; min-width: 40px; width: 40px;"
                    >
                      <img v-if="item.user_image" :src="'../'+item.user_image" alt="John" />
                      <img v-if="!item.user_image" src="/images/avatar.png" alt="driver" />
                    </div>
                    {{ item.first_name }} {{ item.last_name }}
			 <v-data-table
			    :headers="headers"
			    :items="items"
			    hide-default-footer
			    class="elevation-1"
			  >
			    <template slot="items" slot-scope="props">
{{item}}
			      <td>{{ props.item.name }}</td>
			      <td class="text-xs-right">{{ props.item.first_name }}</td>
			      <td class="text-xs-right">{{ props.item.email }}</td>
			      <td class="text-xs-right">{{ props.item.phone }}</td>
			    </template>
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
      search: '',
        headers: [
        {
        text:'Name',
        align: 'left',
        sortable: false,
        value:'index'
        },
        { text: 'First Name', value: 'first_name' },
        { text: 'Email', value: 'email' },
        { text: 'Phone', value: 'phone' }
     ],
     items: [],
     customers: [],
 
    };
  },
  getList() {},
  mounted() {
    this.getResults();
  },
  methods: {
    getResults() {
      customerService.listCustomer().then(response => {
        //handle response
        if (response.status) {
         // this.customers = response.data;
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
