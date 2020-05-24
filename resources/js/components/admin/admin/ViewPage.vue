<template>
      <v-app>
             <v-container>
      <v-row>
         
   </v-row>
    </v-container>
    </v-app>
</template>

<script>
     import { adminService } from "../../../_services/admin.service"
export default {
   components: {
//      'image-component': imageVUE,
  },
  data() {
    return {
        valid: true,
        avatar: null,
        addForm: {
        first_name: '',
        last_name: '',
        email: '',
        is_active: '',
         user_image: null,
         phone: '',
        },   
    };
  },
  created() {
        managerService.getAdmin(this.$route.params.id).then(response => { 
          if(response.status) {
              this.addForm.user_id = response.data.id; 
              if(response.data.user_image){
                this.addForm.user_image = response.data.user_image; 
               }
              if(response.data.user_image){  
                  this.avatar = response.data.user_image; 
              }else{        
                  this.avatar = '/images/avatar.png';   
              }        
              this.addForm.first_name = response.data.first_name;   
              this.addForm.last_name = response.data.last_name;    
              this.addForm.email = response.data.email;       
              this.addForm.phone = response.data.phone;  
              this.addForm.is_active = response.data.is_active;   
              console.log(this.addForm)
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
