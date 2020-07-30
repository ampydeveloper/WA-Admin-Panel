<template>
  <v-container
    id="dashboard"
    fluid
    tag="section"
  >
    <v-row>
<h4 class="main-title">Overview: Driver Login Successfully.</h4>
      <v-col
        cols="12"
        lg="12"
      >

  <div class="dashboard">
 <v-row>
     <v-col
        cols="12"
        sm="6"
        lg="3"
      >
      <v-card >
	<h5>Ongoing Jobs <span>10</span></h5>
     </v-card>
      </v-col>

      <v-col
        cols="12"
        sm="6"
        lg="3"
      >
   <v-card ><h5>Pending Jobs <span>10</span></h5></v-card>
      </v-col>

      <v-col
        cols="12"
        sm="6"
        lg="3"
      >
       <v-card ><h5>Delivered Jobs<span>10</span></h5></v-card>
      </v-col>

      <v-col
        cols="12"
        sm="6"
        lg="3"
      >
     <v-card ><h5>Monthly Earning<span>10</span></h5></v-card>
      </v-col>
 </v-row>
<v-row>
   <v-col
        cols="12"
        sm="8"
        lg="8"
      >
   </v-col>
   <v-col
        cols="12"
        sm="4"
        lg="4"
      >
   </v-col>
</v-row>
  </div>
 </v-col>
    </v-row>
  </v-container>
</template>

<script>


export default {
  data() {
    return {
   
    };
  },
  created() {
  
  }
};
</script>
