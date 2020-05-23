<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <h2>Add Truck</h2>
        </v-col>

        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="8" md="8">
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.company_name"
                    :rules="[v => !!v || 'Company name is required']"
                    label="Company Name"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.truck_number"
                    :rules="[v => !!v || 'Truck number is required']"
                    label="Truck Number"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.chaase_number"
                    :rules="[v => !!v || 'Chaase number is required']"
                    label="Chaase Number"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.insurance_number"
                    :rules="[v => !!v || 'Insurance number is required']"
                    label="Insurance Number"
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
                        label="Insurance Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu2 = false"></v-date-picker>
                  </v-menu>
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
                        v-model="date1"
                        label="Insurance expiry date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date1" @input="menu1 = false"></v-date-picker>
                  </v-menu>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="addForm.total_killometer"
                    label="Total Killometer"
                    required
                    :rules="[v => !!v || 'Truck Total killometer is required']"
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
import { skidsteerService } from "../../../_services/skidsteer.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
export default {
  components: {
    //      'image-component': imageVUE,
  },

  data() {
    return {
      menu2: false,
      menu1: false,
      valid: true,
      apiUrl: environment.apiUrl,
      avatar: null,
      date: "",
      date1: "",
      user_image: "",
      addForm: {
        vehicle_type: 2,
        company_name: "",
        truck_number: "",
        chaase_number: "",
        insurance_number: "",
        insurance_date: "",
        document: "",
        total_killometer: "",
        insurance_expiry: ""
      },

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
    handleProcessFile1: function(error, file) {
      this.addForm.document = file.serverId;
    },
    save() {
      this.addForm.insurance_date = this.date;
      this.addForm.insurance_expiry = this.date1;
      if (this.$refs.form.validate()) {
        skidsteerService.add(this.addForm).then(response => {
         //handle response
         if(response.status) {
             this.$toast.open({
               message: response.message,
               type: 'success',
               position: 'top-right'
             });
          //redirect to login
          router.push("/admin/skidsteers");
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
