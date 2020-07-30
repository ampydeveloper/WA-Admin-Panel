<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12">
          <h4 class="main-title mb-0">Add Truck Insurance</h4>
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
                    :rules="[v => !!v || 'Insurance number is required.']"
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
                    <v-date-picker v-model="date" @input="menu1 = false" :min="setNextDate"></v-date-picker>
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
                >Add Truck Insurance Detail</v-btn>
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
import moment from "moment";

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
      setNextDate: "",
      addForm: {
        vehicle_id: "",
        insurance_number: "",
        insurance_date: "",
        insurance_expiry: ""
      }
    };
  },
  mounted() {
    this.getResults();
  },
  methods: {
    getResults() {
      truckService.getLastInsu(this.$route.params.id).then(response => {
        if (response.status) {
          this.setDate = moment(response.data.insurance_expiry)
            .add(1, "d")
            .format("YYYY-MM-DD");
          this.setNextDate = moment(response.data.insurance_expiry)
            .add(1, "d")
            .format("YYYY-MM-DD");
        }
      });
    },
    save: function(e) {
      //stop page to reload
      e.preventDefault();

      this.addForm.vehicle_id = this.$route.params.id;
      this.addForm.insurance_date = this.date;
      this.addForm.insurance_expiry = this.date1;
      if (this.$refs.form.validate()) {
        if(this.loading) {
          return false;
        }
        //start loading
        this.loading = true;

        truckService.addInsurance(this.addForm).then(response => {
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
              const url = "/admin/truck/service/" + this.$route.params.id;
              router.push(url);
            } else {
              const url = "/manager/truck/service/" + this.$route.params.id;
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
