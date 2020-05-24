<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <h2>Add Driver</h2>
        </v-col>

        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="5" md="5">
                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Driver Image"
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="user_image"
                    v-on:processfile="handleProcessFile"
                  />
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_name"
                    :rules="[v => !!v || 'Driver name is required']"
                    label="Driver Name"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.email"
                    :rules="emailRules"
                    name="email"
                    label="Email Address"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_licence"
                    :rules="[v => !!v || 'driver licence number is required']"
                    label="Driver Licence Number"
                    required
                  ></v-text-field>
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
                        v-model="date"
                        label="Licence Expiry Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu2 = false" :min="setDate"></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="12">
                  <v-radio-group
                    v-model="addForm.salary_type"
                    :rules="[v => !!v || 'Field is required']"
                  >
                    <v-radio label="Per Hour" value="0"></v-radio>
                    <v-radio label="Per Load" value="1"></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_salary"
                    label="Driver Salary"
                    required
                    :rules="[v => !!v || 'Driver salary is required']"
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="5" md="5">
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_address"
                    :rules="[v => !!v || 'Address is required']"
                    label="Address"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_city"
                    :rules="[v => !!v || 'City is required']"
                    label="City"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_state"
                    :rules="[v => !!v || 'State is required']"
                    label="State"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_country"
                    :rules="[v => !!v || 'Country is required']"
                    label="Country"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_zipcode"
                    :rules="[v => !!v || 'Zip code is required']"
                    label="zipcode"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.driver_phone"
                     :rules="phoneRules"
                    label="Mobile Number"
                    required

                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Upload Document"
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:processfile="handleProcessFile1"
                    :rules="[v => !!v || 'Document is required']"
                  />
                </v-col>
              </v-col>

              <v-col cols="12" md="12">
                <v-btn color="success" class="mr-4" @click="save">Submit</v-btn>
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
import { driverService } from "../../../_services/driver.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
export default {
  components: {
    //      'image-component': imageVUE,
  },

  data() {
    return {
      menu2: false,
      valid: true,
      apiUrl: environment.apiUrl,
      avatar: null,
      date: "",
      user_image: "",
      setDate:new Date().toISOString().substr(0, 10),
      addForm: {
        driver_name: "",
        email: "",
        driver_licence: "",
        expiry_date: "",
        salary_type: "",
        document: "",
        user_image: "",
        driver_address: "",
        driver_city: "",
        driver_state: "",
        driver_country: "",
        driver_zipcode: "",
        driver_phone: "",
        driver_type: 1
        
      },
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+/.test(v) || "E-mail must be valid"
      ],
      phoneRules: [
        v => !!v || "Phone number is required",
        v => /^\d*$/.test(v) || "Enter valid number",
	v => v.length >= 10 || "Enter valid number length"
      ],
      myFiles: []
    };
  },
  computed: {

    serverOptions() {
      const currentUser = JSON.parse(localStorage.getItem("currentUser"));
      return {
        url: this.apiUrl,
        withCredentials: false,
        process: {
          url: "uploadImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token
          }
        }
      };
    },
    url() {
      if (this.file) {
        let parsedUrl = new URL(this.file);
        return [parsedUrl.pathname];
      } else {
        return null;
      }
    }
  },
  created() {},
  methods: {
    handleProcessFile: function(error, file) {
      this.addForm.user_image = file.serverId;
    },
    handleProcessFile1: function(error, file) {
      this.addForm.document = file.serverId;
    },
    save() {
      this.addForm.expiry_date = this.date;
      if (this.$refs.form.validate()) {
                     driverService.add(this.addForm).then(response => {
                      //handle response
                      if(response.status) {
                          this.$toast.open({
                            message: response.message,
                            type: 'success',
                            position: 'top-right'
                          });
                       //redirect to login
                       router.push("/admin/truckdrivers");
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
