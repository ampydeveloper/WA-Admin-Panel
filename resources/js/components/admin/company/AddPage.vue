<template>
  <v-app>
    <v-container>
      <v-row>
        <h2>Add Company Customer</h2>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="12" md="12">
               <v-row>
                <v-col cols="12" md="12">
               <div
                class="v-avatar v-list-item__avatar"
                style="height: 40px; min-width: 40px; width: 40px;"
              >
                <img :src="customer_img" />
              </div>
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Add Profile pic..."
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                    v-on:processfile="handleProcessFile"
                  />
                </v-col>
		<v-col cols="3" md="3">
		 <v-select 
		  v-model="addForm.prefix"
		  :items="prefixs"
		  label="Prefix"
	          :rules="[v => !!v || 'Prefix is required']"
		  dense
		></v-select>
		</v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.customer_name"
                    label="Name"
                    required
                    :rules="[v => !!v || 'Customer name is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.email"
                    :rules="emailRules"
                    name="email"
                    label="E-mail"
                    required
                  ></v-text-field>
                </v-col>
                 <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.phone"
                    :rules="phoneRules"
                    label="Phone"
                    required
                    maxlength="10"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.address"
                    label="Address"
                    required
                    :rules="[v => !!v || 'address is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.city"
                    label="City"
                    required
                    :rules="[v => !!v || 'City is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.province"
                    label="Province"
                    required
                    :rules="[v => !!v || 'Province is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.zipcode"
                    :rules="[v => !!v || 'Zip code is required']"
                    label="zipcode"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
               <v-switch v-model="addForm.is_active" class="mx-2" label="Is Active" ></v-switch>
                </v-col>
               </v-row>
              </v-col>

              <v-col cols="12" md="12">
                <h3>Farm Section</h3>
                <v-row>
                  <v-col cols="12" md="12">
                    <file-pond
                      name="uploadImage"
                      ref="pond"
                      label-idle="Farm Images"
                      allow-multiple="true"
                      v-bind:server="serverOptions"
                      v-bind:files="myFiles"
                      allow-file-type-validation="true"
                      accepted-file-types="image/jpeg, image/png"
                      v-on:processfile="handleProcessFile1"
                    />
                  </v-col>
                  <v-col cols="3" md="3">
                    <vue-google-autocomplete
                      ref="address"
                      id="map"
                      class="form-control"
                      placeholder="Please type your address"
                      v-on:placechanged="getAddressData"
                      country="us"
                      v-model="addForm.farm_address"
                    ></vue-google-autocomplete>
                  </v-col>
                  <v-col cols="3" md="3">
                    <v-text-field
                      v-model="addForm.farm_unit"
                      label="Apt/Unit"
                      required
                      :rules="[v => !!v || 'Farm apt/unit is required']"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="3" md="3">
                    <v-text-field
                      v-model="addForm.farm_city"
                      label="City"
                      required
                      :rules="[v => !!v || 'Farm city is required']"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="3" md="3">
                    <v-text-field
                      v-model="addForm.farm_province"
                      label="Province"
                      required
                      :rules="[v => !!v || 'Farm province is required']"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="3" md="3">
                    <v-text-field
                      v-model="addForm.farm_zipcode"
                      label="Zip Code"
                      required
                      :rules="[v => !!v || 'Farm zip code is required']"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="3" md="3">
                    <v-switch v-model="addForm.farm_active" class="mx-2" label="Is Active"></v-switch>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" md="12">
              <h3>Manager Details</h3>
		 <v-row>
                <v-col cols="12" md="12">
 <div
                class="v-avatar v-list-item__avatar"
                style="height: 40px; min-width: 40px; width: 40px;"
              >
                <img :src="manager_img" />
              </div>
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Add Profile Pic"
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                    v-on:processfile="handleProcessFile2"
                  />
                </v-col>
                <v-col cols="3" md="3">
		 <v-select 
		  v-model="addForm.manager_prefix"
		  :items="prefixs"
		  label="Prefix"
	          :rules="[v => !!v || 'Prefix is required']"
		  dense
		></v-select>
		</v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_name"
                    label="Name"
                    required
                    :rules="[v => !!v || 'Manager name is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_email"
                    :rules="emailRules"
                    name="email"
                    label="E-mail"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_phone"
                    :rules="phoneRules"
                    label="Phone"
                    required
                    maxlength="10"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_address"
                    label="Address"
                    required
                    :rules="[v => !!v || 'address is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_city"
                    label="City"
                    required
                    :rules="[v => !!v || 'City is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_province"
                    label="State"
                    required
                    :rules="[v => !!v || 'Province is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_zipcode"
                    :rules="[v => !!v || 'Zip code is required']"
                    label="zipcode"
                    required
                  ></v-text-field>
                </v-col>
		<v-col cols="3" md="3">
                  <v-text-field
                    v-model="addForm.manager_id_card"
                    :rules="[v => !!v || 'Card Id number is required']"
                    label="Id CradNo"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="4" md="4">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Upload Id Card Image"
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                    v-on:processfile="handleProcessFile3"
                  />
                </v-col>
               </v-row>            
              </v-col>

              <v-btn color="success" class="mr-4" @click="update">Submit</v-btn>
            </v-row>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { companyService } from "../../../_services/company.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import VueGoogleAutocomplete from "vue-google-autocomplete";
export default {
  components: {
    VueGoogleAutocomplete
  },
  data() {
    return {
     docError: false,
    prefixs: ['Ms.', 'Mr.', 'Mrs.'],
    isLoading: false,
    items: [],
    model: null,
      valid: true,
      avatar: null,
      menu2: false,
      menu1: false,
      date: "",
      date1: "",
      customer_img: "",
      manager_img: "",
      apiUrl: environment.apiUrl,
      addForm: {
        prefix: "",
        customer_name: "",
        email: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        user_image: null,
        zipcode: '',
        is_active: true,
        farm_images: [],
        latitude: '',
	longitude: '',
	farm_address: '',
	farm_unit: '',
	farm_city: '',
	farm_province: '',
	farm_zipcode: '',
	farm_active: true,
	manager_image: '',
	manager_prefix: '',
	manager_name: '',
	manager_email: '',
	manager_phone: '',
	manager_address: '',
	manager_city: '',
	manager_province: '',
	manager_zipcode: '',
	manager_id_card: '',
	manager_card_image: '',
	customer_role: 6,
        manager_role: 7,
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
      rules: [
        value =>
          !value ||
          value.size < 2000000 ||
          "Avatar size should be less than 2 MB!"
      ],
      myFiles: []
    };
  },
  mounted() {
    this.$refs.address.focus();
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
  created() {
    this.customer_img = "/images/avatar.png";
    this.manager_image = "/images/avatar.png";
  },
  methods: {
    getAddressData: function(addressData, placeResultData, id) {
      console.log(addressData.route)
      this.addForm.latitude = addressData.latitude;
      this.addForm.longitude = addressData.longitude;
      this.addForm.farm_address = addressData.route;
    },
    handleProcessFile: function(error, file) {
       this.customer_img = "../../"+file.serverId;
      this.addForm.user_image = file.serverId;
    },
  //farm images process
   handleProcessFile1: function(error, file) {
      this.addForm.farm_images.push(file.serverId);
    },
    //manager image process
    handleProcessFile2: function(error, file) {
       this.manager_img = "../../"+file.serverId;
      this.addForm.manager_image = file.serverId;
    },
    //manager id card image process
    handleProcessFile3: function(error, file) {
      this.addForm.manager_card_image = file.serverId;
      //this.docError = false;
    },
    update() {
      console.log(this.addForm);
      if (this.$refs.form.validate()) {
        companyService.add(this.addForm).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            router.push("/admin/customer");
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
