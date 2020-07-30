<template>
      <v-app>
             <v-container fluid>
      <v-row>
<h2>View Manager</h2>
         {{manager}}
   </v-row>
    </v-container>
    </v-app>
</template>

<script>
     import { managerService } from "../../../_services/manager.service"
export default {
   components: {
//      'image-component': imageVUE,
  },
  data() {
    return {
        valid: true,
        avatar: null,
     	manager: [],  
    };
  },
  created() {
        managerService.getManager(this.$route.params.id).then(response => { 
          if(response.status) {
             this.manager = response.data;
          } else {              
              router.push("/admin/manager");    
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
