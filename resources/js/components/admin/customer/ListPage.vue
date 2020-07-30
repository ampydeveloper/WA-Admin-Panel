<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <div class="add-icon">
          <router-link v-if="isAdmin" to="/admin/customer/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
          <router-link v-if="!isAdmin" to="/manager/customer/add" class="nav-item nav-link">
            <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon>
          </router-link>
        </div>
        <v-col cols="12" md="12">
      <table id="example" class="table table-striped table-bordered" style="width:100%">

              <thead>
                <tr>
                  <th class="text-left"></th>
                </tr>
              </thead>
              <tbody>
                <tr class="multi-customers" v-for="customer in customers" :key="customer.name">
                  <td class="single-customer">
                  <span>
                   <router-link v-if="isAdmin" :to="'/admin/customer/details/' + customer.id" class="nav-item nav-link">{{ customer.prefix }}  {{ customer.first_name }} {{ customer.last_name }}</router-link>
                   <router-link v-if="!isAdmin" :to="'/manager/customer/details/' + customer.id" class="nav-item nav-link">{{ customer.prefix }}  {{ customer.first_name }} {{ customer.last_name }}</router-link>
                   </span> 
		<v-simple-table>
			<thead>
			<tr>
			 <th>Sno</th>
			<th>Manager Name</th>
			<th>Phone Number</th>
			<th>Email</th>
			<th>Fram Address</th>
			<th>City</th>
			<th>State/Province</th>
			<th>Zip/Postal</th>
			<th>No Of Jobs</th>
			<th>Last Services</th>
			</tr>
			</thead>
			<tbody>
			<tr v-for="(farm, index) in customer.farmlist">
			   <td>{{index+1}}</td>
			    <td>
				<span v-for="manager in farm.farm_manager">{{manager.first_name}}<br></span>
			    </td>
			    <td>
				<span v-for="manager in farm.farm_manager">{{manager.phone}}<br></span>
			    </td>
		            <td>
				<span v-for="manager in farm.farm_manager">{{manager.email}}<br></span>
			    </td>
			   <td>{{farm.farm_address}}</td>
			   <td>{{farm.farm_city}}</td>
			   <td>{{farm.farm_province}}</td>
			   <td>{{farm.farm_zipcode}}</td>
			   <td>N/A</td>
   			   <td>N/A</td>
			</tr>
			</tbody>
		  </v-simple-table>
		
                 
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
import { customerService } from "../../../_services/customer.service";
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
      search: "",

      headers: [
        {
          text: "Sno",
          align: "left",
          sortable: false,
          value: "index"
        },
        { text: "Manager", value: "farm_manager.first_name" },
        { text: "Phone Number", value: "farm_manager.phone" },
        { text: "Email", value: "farm_manager.email" },
        { text: "Farm Address", value: "farm_address" },
        { text: "City", value: "farm_city" },
        { text: "State/Province", value: "farm_province" },
        { text: "Zip/Postal", value: "farm_zipcode" },
        { text: "No Of Jobs", value: "" },
        { text: "Last Services", value: "" }
      ],
      items: [],
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
