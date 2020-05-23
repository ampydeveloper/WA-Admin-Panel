<template>
      <v-app>
             <v-container>
      <v-row>
     
        
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
export default {
  data() {
    return {
        avatar: null,
        trcuk: '',
    };
  },
   mounted: function() {
         truckService.getTruck(this.$route.params.id).then(response => {
              //handle response
              if(response.status) {
                  this.truck = response.data;
              } else {
                  router.push("/admin/services"); 
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
