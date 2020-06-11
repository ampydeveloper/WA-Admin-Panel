<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <h4 class="main-title mb-0">Edit Truck Service</h4>
        </v-col>

        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="12" md="12">
          
                <v-col cols="12" md="12">
                  <v-menu
                    v-model="menu2"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="date"
                        label="Service Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
			required
                      :rules="[v => !!v || 'Service date is required']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu2 = false"></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.service_killometer"
                    label="Total Killometer"
                     required
                    :rules="killometerRules"
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="12" md="12">
                <v-btn color="success" class="mr-4 custom-save-btn ml-4 mt-4" @click="save">Submit</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { truckService } from "../../../_services/truck.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
export default {
  data() {
    return {
      menu2: false,
      menu1: false,
      valid: true,
      apiUrl: environment.apiUrl,
      avatar: null,
      date: "",
      setDate:new Date().toISOString().substr(0, 10),
      user_image: "",
      addForm: {
        vehicle_id: "",
        service_date: "",
        service_killometer: "",
      },
     killometerRules: [
        v => !!v || "Truck kilometer is required",
        v => /^\d*$/.test(v) || "Enter valid number",
      ],
    };
  },
  mounted: function() {
	truckService.getTruckSingleService(this.$route.params.id).then(response => {
		//handle response
		if (response.status) {
		this.addForm.id=response.data.id;
		this.addForm.vehicle_id=response.data.vehicle_id;
		this.date=new Date(response.data.service_date).toISOString().substr(0, 10);
		this.addForm.service_killometer=response.data.service_killometer;
		}
	});
 },
  methods: {
    save() {
      this.addForm.service_date = this.date;
      if (this.$refs.form.validate()) {
        truckService.updateTruckService(this.addForm).then(response => {
         //handle response
         if(response.status) {
             this.$toast.open({
               message: response.message,
               type: 'success',
               position: 'top-right'
             });
          //redirect to login
	const url = "/admin/truck/service/"+this.addForm.vehicle_id;
          router.push(url);
         } else {
             this.$toast.open({
               message: response.message,
               type: 'error',
               position: 'top-right'
             })
         }
       });
      }
    }
  }
};
</script>
