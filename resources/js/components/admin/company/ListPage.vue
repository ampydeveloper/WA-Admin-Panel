<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <div class="add-icon">
          <router-link v-if="isAdmin" to="/admin/hauler/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
          <router-link v-if="!isAdmin" to="/manager/hauler/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
      <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(customer, index) in customers">
	       <td>   
                     <div
		        class="v-avatar v-list-item__avatar"
		        style="height: 60px; min-width: 60px; width: 60px;"
		      >
		        <img v-if="customer.user_image" :src="'../'+customer.user_image"/>
		        <img v-if="!customer.user_image" src="/images/avatar.png"/>
		      </div>
             </td>
               <td>{{customer.prefix}} {{customer.first_name}}</td>
               <td>{{customer.email}}</td>
               <td>{{customer.phone}}</td>
	       <td>{{customer.address}} {{customer.city}} {{customer.state}}, {{customer.zip_code}}</td>
               <td>
               <v-chip v-if="!customer.is_active" class="ma-2" color="red" text-color="white">No</v-chip>
                      <v-chip
                        v-if="customer.is_active"
                        class="ma-2"
                        color="green"
                        text-color="white"
                      >Yes</v-chip>
               </td>
               <td class="action-col">
                   <router-link
                        v-if="isAdmin"
                        :to="'/admin/hauler/edit/' + customer.id"
                        class="nav-item nav-link"
                      >
                        <span class="custom-action-btn">Edit</span>
                      </router-link>
                      <router-link
                        v-if="!isAdmin"
                        :to="'/manager/hauler/edit/' + customer.id"
                        class="nav-item nav-link"
                      >
                        <span class="custom-action-btn">Edit</span>
                      </router-link>

                      <v-btn color="blue darken-1" text @click="Delete(customer.id)">
                        <span class="custom-action-btn">Delete</span>
                      </v-btn>
               </td>
            </tr>
        </tbody>
            </table>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { companyService } from "../../../_services/company.service";
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
    PlusCircleIcon,
  },
  data() {
    return {
      customers: [],
      isAdmin: true,
    };
  },
  getList() {},
  mounted() {
    const currentUser = authenticationService.currentUserValue;
    if(currentUser.data.user.role_id == 1){
    this.isAdmin = true;
    }else{
    this.isAdmin = false;
    }
    this.getResults();
  },
  methods: {
    getTagNames: tags => {
      return tags.map(tag => tag.name);
    },
    getResults() {
      companyService.listHauler().then(response => {
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
        companyService.deleteHauler(e).then(response => {
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
            //router.push("/admin/company");
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
  },
updated() {
setTimeout(function() {
     $(document).ready(function() {
	    $('#example').DataTable();
	} );
  }, 1000);
    }
};
</script>
