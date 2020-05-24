<template>
      <v-app>
             <v-container>
      <v-row>
     
        
      <v-subheader>Service</v-subheader>     
        <v-list-item>
            <v-list-item-content>
              <v-list-item-title>Name</v-list-item-title>
              <v-list-item-subtitle>{{editForm.service_name}}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
      
         <v-list-item>
        <v-list-item-content>
          <v-list-item-title>Price</v-list-item-title>
          <v-list-item-subtitle>{{editForm.price}}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
        <v-list-item>
        <v-list-item-content>
          <v-list-item-title>Description</v-list-item-title>
          <p>{{editForm.description}}</p>
        </v-list-item-content>
      </v-list-item>
         
   </v-row>
    </v-container>
    </v-app>
</template>

<script>
 import { jobService } from "../../../_services/job.service";
export default {
  data() {
    return {
        avatar: null,
        editForm: {
            id: '',
            service_name:'',
            price:'',
            description:'',
            service_image:'',
        },
    };
  },
   mounted: function() {
         jobService.getService(this.$route.params.id).then(response => {
              //handle response
              if(response.status) {
                  this.editForm.id = response.data.id;
                this.editForm.service_name = response.data.service_name;
                this.editForm.price = response.data.price;
                this.editForm.description = response.data.description;
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
