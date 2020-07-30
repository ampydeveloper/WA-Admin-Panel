<template>
      <v-app>
             <v-container fluid>
      <v-row>
   <h2>View Truck Document</h2>
	  <v-col cols="12" md="12">
		<h3>RC</h3>
                  <div v-if="rc" style="height:200px; width:200px">
                    <img :src="rc" alt="John" style="height:200px;" />
                  </div>
<h3>insurance</h3>
<div v-if="insurance" style="height:200px; width:200px">
                    <img :src="insurance" alt="John" style="height:200px;" />
                  </div>
                </v-col>
       
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
        rc: null,
	insurance: null,
    };
  },
   mounted: function() {
         truckService.getTruck(this.$route.params.id).then(response => {
              //handle response
              if(response.status) {
                  this.truck = response.data;
                  this.rc = "../../../" +response.data.document;
	           this.insurance = "../../../" +response.data.insurance_document;
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
