<template>
      <v-app>
             <v-container>
      <v-row>
<h4 class="main-title">Truck List</h4>
        <div class="add-icon">
          <router-link to="/admin/truck/add" class="nav-item nav-link"> <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon></router-link>
        </div>
      <v-col
          cols="12"
          md="12"
        >
  <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
        <th class="text-left">Company</th>
        <th class="text-left">Truck number</th>
        <th class="text-left">Chassis number</th>
        <th class="text-left">Total Km</th>
        <th class="text-left">Service Details</th>
        <th class="text-left">Documents</th>
        <th class="text-left">Status</th>
        <th class="text-left">Action</th>
       
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in trucks" :key="item.name">
	<td><router-link :to="'/admin/truck/view/' + item.id" class="nav-item nav-link">{{item.company_name}}</router-link></td>
	<td>{{item.truck_number}}</td>
	<td>{{item.chaase_number}}</td>
	<td>{{item.killometer}}</td>
        <td> <router-link :to="'/admin/truck/service/' + item.id" class="nav-item nav-link">View Service</router-link></td>
        <td><router-link :to="'/admin/truck/docview/' + item.id" class="nav-item nav-link">View Documents</router-link></td>
	<td v-if="item.status == 1">Available</td>
	<td v-if="item.status == 0">Unavailable</td>
          <td class="action-col"> 
            
            <router-link :to="'/admin/truck/edit/' + item.id" class="nav-item nav-link">
              <!--<edit-icon size="1.5x" class="custom-class"></edit-icon> -->
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
 import { truckService } from "../../../_services/truck.service";
 import { UserIcon, EditIcon, TrashIcon, PlusCircleIcon } from 'vue-feather-icons'
 import { router } from "../../../_helpers/router";
  export default {
      components: {
        UserIcon,
       EditIcon, TrashIcon, PlusCircleIcon
      },
    data () {
      return {
          dialog: false,
          on: false,
          trucks: [],
      }
    },
    mounted() {
    this.getResults();
   },
 
    methods: {
           getResults() {
      truckService.listTrucks().then(response => {
        //handle response
        if (response.status) {
          this.trucks = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right"
          });
        }
      });
    },
        Delete(e){
           if(e){
            truckService.Delete(e).then(response => {
              //handle response
              if(response.status) {
                  this.$toast.open({
                    message: response.message,
                    type: 'success',
                    position: 'top-right'
                  });
               //redirect to login
               this.dialog = false 
               this.getResults();
               //router.push("/admin/service");
              } else {
                  this.dialog = false 
                  this.$toast.open({
                    message: response.message,
                    type: 'error',
                    position: 'top-right'
                  })
              }
            });
           }
        },
        Close(){
          this.dialog = false 
        }
    }
  }
</script>
