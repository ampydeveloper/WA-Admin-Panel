<template>
      <v-app>
             <v-container>
      <v-row>
     
        
      <v-subheader>skidsteer</v-subheader>     
        <v-list-item>
            <v-list-item-content>
              <v-list-item-title>Name</v-list-item-title>
              <v-list-item-subtitle>{{skidsteer}}</v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>    
   </v-row>
    </v-container>
    </v-app>
</template>

<script>
 import { skidsteerService } from "../../../_services/skidsteer.service";
export default {
  data() {
    return {
        avatar: null,
        skidsteer: '',
    };
  },
   mounted: function() {
         skidsteerService.getSkidsteer(this.$route.params.id).then(response => {
              //handle response
              if(response.status) {
                  this.skidsteer = response.data;
              } else {
                  router.push("/admin/skidsteers"); 
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
