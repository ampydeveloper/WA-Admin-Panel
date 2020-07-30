<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12">
          <h4 class="main-title mb-0">Edit Skidsteer Insurance</h4>
        </v-col>

        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="save">
            <v-row>
              <v-col cols="12" md="12">
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.insurance_number"
                    label="Insurance number"
                    required
                    :rules="[v => !!v || 'Insurance number is required']"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-menu
                    v-model="menu1"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="date"
                        label="Insurance Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        required
                        :rules="[v => !!v || 'Insurance date is required']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu1 = false"></v-date-picker>
                  </v-menu>
                </v-col>
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
                        v-model="date1"
                        label="Insurance Expiry Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        required
                        :rules="[v => !!v || 'Insurance expiry date is required']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date1" @input="menu2 = false" :min="setDate"></v-date-picker>
                  </v-menu>
                </v-col>
              </v-col>

              <v-col cols="12" md="12">
                <v-btn
                type="submit" :loading="loading" :disabled="loading"
                  color="success"
                  class="mr-4 custom-save-btn ml-4 mt-4"
                  @click="save"
                >Update Truck Insurance Detail</v-btn>
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
import { authenticationService } from "../../../_services/authentication.service";
export default {
  data() {
    return {
      loading: false,
      menu2: false,
      menu1: false,
      valid: true,
      apiUrl: environment.apiUrl,
      date: "",
      date1: "",
      setDate: new Date().toISOString().substr(0, 10),
      addForm: {
        vehicle_id: "",
        insurance_number: "",
        insurance_date: "",
        insurance_expiry: ""
      }
    };
  },
  mounted: function() {
    truckService
      .getTruckSingleInsurance(this.$route.params.id)
      .then(response => {
        //handle response
        if (response.status) {
          this.addForm.id = response.data.id;
          this.addForm.vehicle_id = response.data.vehicle_id;
          this.date = new Date(response.data.insurance_date)
            .toISOString()
            .substr(0, 10);
          this.date1 = new Date(response.data.insurance_expiry)
            .toISOString()
            .substr(0, 10);
          this.addForm.insurance_number = response.data.insurance_number;
        }
      });
  },
  methods: {
    save: function(e) {
      //stop page to reload
      e.preventDefault();

      this.addForm.insurance_date = this.date;
      this.addForm.insurance_expiry = this.date1;
      if (this.$refs.form.validate()) {
        if(this.loading) {
          return false;
        }
        //start loading
        this.loading = true;

        truckService.updateInsurance(this.addForm).then(response => {
          //stop loading
          this.loading = false;
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            const currentUser = authenticationService.currentUserValue;
            if (currentUser.data.user.role_id == 1) {
              const url = "/admin/truck/service/" + this.addForm.vehicle_id;
              router.push(url);
            } else {
              const url = "/manager/truck/service/" + this.addForm.vehicle_id;
              router.push(url);
            }
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
      }
    }
  }
};
</script>
