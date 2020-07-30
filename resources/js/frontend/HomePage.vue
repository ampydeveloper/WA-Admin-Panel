<template>
 
    <v-row>
     <v-col cols="12" md="12">

    <v-carousel v-model="model">
      <v-carousel-item
        v-for="(color, i) in colors"
        :key="color"
      >
        <v-sheet
          :color="color"
          height="100%"
          tile
        >
          <v-row
            class="fill-height"
            align="center"
            justify="center"
          >
            <div class="display-3">Slide {{ i + 1 }}</div>
          </v-row>
        </v-sheet>
      </v-carousel-item>
    </v-carousel>
  </v-carousel>
   <v-icon @click="model--">mdi-minus</v-icon>
      {{ model }}
      <v-icon @click="model++">mdi-plus</v-icon>
</v-col>
<v-col cols-12="12" md="12" v-if="services">
<v-row>
<template v-for="service in services" >
  <v-col cols-12="4" md="4" >


  <v-card
    class="mx-auto"
    max-width="400"
  >
    <v-img v-if="service.service_image"
      class="white--text align-end"
      height="200px"
     :src="service.service_image"
    >
    </v-img>
    <v-card-subtitle class="pb-0">Service Name: {{service.service_name}}</v-card-subtitle>

    <v-card-text class="text--primary">

<span v-if="service.service_rate == 1">Service Type: Per Load</span>
<span v-if="service.service_rate == 2">Service Type: Round</span>
</p></div>

      <div>
           <span v-for="(type, index) in service.slot_type">
			       <label v-if="type == 1">Morning</label>
			       <label v-if="type == 2">Afternoon</label>
			   </span>
      </div>
<div>
   <span v-for="(tSlot, index) in service.timeSlots">
                      <label>{{tSlot.slot_start+'-'+tSlot.slot_end}}</label>
                      <label v-if="service.timeSlots.length-1 != index">, &nbsp;</label>
                    </span>
</div>
<div>{{service.description}}</div>
    </v-card-text>

    <v-card-actions>
      <v-btn
        color="orange"
        text
      >
     <router-link :to="'/book-job/' + service.id" class="nav-item nav-link">Book Now</router-link> 
      </v-btn>
    </v-card-actions>
  </v-card>
</v-col>
</template>
  </v-row>
</v-col>

  </v-row>
</template>

<script>
import { authenticationService } from "../_services/authentication.service";
import { serviceService } from "../_services/service.service";
import { router } from "../_helpers/router";
  export default {
    data () {
      return {
         colors: [
          'primary',
          'secondary',
          'yellow darken-2',
          'red',
          'orange',
        ],
        model: 0,
        services:'',
      }
    },
  mounted() {
    this.getResults();
  },
  methods: {
     getResults() {
      serviceService.listService().then(response => {
        //handle response
        if (response.status) {
          this.services = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right"
          });
        }
      });
   },
    logout() {
      authenticationService.logout();
      router.push("/login");
    }
  }
  }
</script>
