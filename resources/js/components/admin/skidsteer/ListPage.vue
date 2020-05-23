<template>
      <v-app>
             <v-container>
      <v-row>
      <v-col
          cols="12"
          md="12"
        >
          <router-link to="/admin/skidsteer/add" class="nav-item nav-link"> <plus-circle-icon size="1.5x" class="custom-class"></plus-circle-icon></router-link>
            
            </v-col>
      <v-col
          cols="12"
          md="12"
        >
  <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
        <th class="text-left">Company Name</th>
        <th class="text-left">truck_number</th>
        <th class="text-left">chaase_number</th>
        <th class="text-left">insurance_number</th>
        <th class="text-left">Action</th>
          
        
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in trucks" :key="item.name">
            <td>{{item.company_name}}</td>
            <td>{{item.truck_number}}</td>
            <td>{{item.chaase_number}}</td>
            <td>{{item.insurance_number}}</td>
          <td> 
        <router-link :to="'/admin/skidsteer/view/' + item.id" class="nav-item nav-link"><user-icon size="1.5x" class="custom-class"></user-icon></router-link>
              <router-link :to="'/admin/skidsteer/edit/' + item.id" class="nav-item nav-link"><edit-icon size="1.5x" class="custom-class"></edit-icon></router-link>
            <v-btn color="blue darken-1" text @click="Delete(item.id)"><trash-icon size="1.5x" class="custom-class"></trash-icon></v-btn>

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
 import { skidsteerService } from "../../../_services/skidsteer.service";
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
    getList(){
     
    },
    mounted: function()  {
          skidsteerService.listSkidsteers().then(response => {
            //handle response
            if(response.status) {
             this.trucks = response.data;
            } else {

                this.$toast.open({
                  message: response.message,
                  type: 'error',
                  position: 'top-right'
                })
            }
          });
    },
    methods: {
        Action(){
            
        },
        Delete(e){
           if(e){
            skidsteerService.Delete(e).then(response => {
              //handle response
              if(response.status) {
                  this.$toast.open({
                    message: response.message,
                    type: 'success',
                    position: 'top-right'
                  });
               //redirect to login
               this.dialog = false 
//               router.push("/admin/service");
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