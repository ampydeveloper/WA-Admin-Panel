<template>
      <v-app>
             <v-container fluid>
      <v-row>
   <h2>View Truck</h2>
      <v-subheader>Truck</v-subheader>     
        <v-list-item>
            <v-list-item-content>
              <v-list-item-title>Name</v-list-item-title>
              <v-list-item-subtitle>{{truck}}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>    
   </v-row>
    </v-container>
    </v-app>
</template>

<script>
 import { truckService } from "../../../_services/truck.service";
import { PlusCircleIcon } from 'vue-feather-icons'
export default {
components: {
      PlusCircleIcon
      },
  data() {
    return {
        avatar: null,
        truck: [],
    };
  },
   mounted: function() {
         truckService.getTruck(this.$route.params.id).then(response => {
              //handle response
              if(response.status) {
                  this.truck = response.data;
              } else {
                  router.push("/admin/trucks"); 
                  this.$toast.open({
                    message: response.message,
                    type: 'error',
                    position: 'top-right'
                  })
              }
            });
    },
  methods: {
    
    }
};
</script>
