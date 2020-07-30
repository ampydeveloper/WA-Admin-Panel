<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12">
            <v-row>
              <v-col cols="12" md="12">
                    <v-form ref="customerForm" v-model="valid" lazy-validation>
                     <v-col cols="12" md="12">
                        <h4 class="main-title">Farm Section</h4>
                        <v-row>
                          <v-col cols="12" md="12">
                            <file-pond
                              name="uploadImage"
                              ref="pond"
                              label-idle="Farm Images"
                              v-bind:allow-multiple="false"
                              v-bind:server="serverOptions"
                              v-bind:files="myFiles"
                              v-on:addfilestart="setUploadIndex"
                              allow-file-type-validation="true"
                              accepted-file-types="image/jpeg, image/png"
                              v-on:processfile="handleProcessFile1"
                              v-on:processfilerevert="handleRemoveFile1"
                            />
                            <div class="v-messages theme--light error--text" role="alert" v-if="farmImgError">
                              <div class="v-messages__wrapper">
                                <div class="v-messages__message">Farm image is required</div>
                              </div>
                            </div>
                          </v-col>
                          <v-col cols="3" md="3" class="mt-4">
                            <v-text-field
                              type="text"
                              class="mt-m11"
                              @input="onChange"
                              v-model="search"
                              label="Search Place"
                              required
                              :rules="[v => !!v || 'Place is required']"
                            ></v-text-field>
                            <ul v-show="isOpen" class="autocomplete-results">
                              <li
                                v-for="(result, i) in items"
                                :key="i"
                                @click="setResult(result)"
                                class="autocomplete-result"
                              >{{ result.place_name }}</li>
                            </ul>
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
                        <h4 class="main-title">Manager Details</h4>
                        <template v-for="(input, index) in addForm.manager_details">
                        <v-row>
                          <div style="width: 100%">
                            <v-btn
                              color="success"
                              class="mr-4 custom-save-btn ml-4 mt-4 setPosition"
                              @click="deleteRow(index)"
                            >
                              <trash-icon size="1.5x" class="custom-class"></trash-icon>
                            </v-btn>
                          </div>
                          <v-col cols="12" md="12">
                            <div
                              class="v-avatar v-list-item__avatar"
                              style="height: 80px; min-width: 80px; width: 80px;"
                            >
                              <img :src="'../../../'+input.manager_image" />
                            </div>
                            <file-pond
                              name="uploadImage"
                              ref="pond"
                              label-idle="Add Profile Pic"
                              v-bind:allow-multiple="false"
                              v-bind:server="serverOptions"
                              v-bind:files="myFiles"
                              v-on:addfilestart="setUploadIndex(index)"
                              allow-file-type-validation="true"
                              accepted-file-types="image/jpeg, image/png"
                              v-on:processfile="handleProcessFile2"
                              v-on:processfilerevert="handleRemoveFile2(index)"
                            />
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-select
                              v-model="input.manager_prefix"
                              :items="prefixs"
                              label="Prefix"
                              :rules="[v => !!v || 'Prefix is required']"
                            ></v-select>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.manager_name"
                              label="Name"
                              required
                              :rules="[v => !!v || 'Manager name is required']"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.email"
                              :rules="emailRules"
                              name="email"
                              label="E-mail"
                              required
                            ></v-text-field>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.manager_phone"
                              :rules="phoneRules"
                              label="Phone"
                              required
                              maxlength="10"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.manager_address"
                              label="Address"
                              required
                              :rules="[v => !!v || 'address is required']"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.manager_city"
                              label="City"
                              required
                              :rules="[v => !!v || 'City is required']"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.manager_province"
                              label="State"
                              required
                              :rules="[v => !!v || 'Province is required']"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.manager_zipcode"
                              :rules="[v => !!v || 'Zip code is required']"
                              label="zipcode"
                              required
                            ></v-text-field>
                          </v-col>
                          <v-col cols="3" md="3">
                            <v-text-field
                              v-model="input.manager_id_card"
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
                              v-bind:allow-multiple="false"
                              v-bind:server="serverOptions"
                              v-bind:files="myFiles"
                              v-on:addfilestart="setUploadIndex(index)"
                              allow-file-type-validation="true"
                              accepted-file-types="image/jpeg, image/png"
                              v-on:processfile="handleProcessFile3"
                              v-on:processfilerevert="handleRemoveFile3"
                              required
                              :rules="[v => !!v || 'Upload id card is required']"
                            />
                          </v-col>
                            <div class="v-messages theme--light error--text" role="alert" v-if="docError">
                              <div class="v-messages__wrapper">
                                <div class="v-messages__message">Id card image is required</div>
                              </div>
                            </div>
                        </v-row>
                        </template>
                      </v-col>
                      <v-btn
                          color="success"
                          class="mr-4 custom-save-btn ml-4 mt-4 setLeftPosition"
                          @click="addRow"
                        >Add More</v-btn>
                        <v-btn
                          color="success"
                          class="mr-4 custom-save-btn ml-4 mt-4 setRightPosition"
                          @click="update"
                        >Submit</v-btn>
                    </v-form>
              </v-col>
            </v-row>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { customerService } from "../../../_services/customer.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import { authenticationService } from "../../../_services/authentication.service";
import { TrashIcon } from "vue-feather-icons";

export default {
  components: {
    VueGoogleAutocomplete,
    TrashIcon
  },
  data() {
    return {
      isLoading: false,
      items: [],
      isOpen: false,
      docImgError: false,
      search: null,
      loading: false,
      docError: false,
      prefixs: ["Ms.", "Mr.", "Mrs."],
      isLoading: false,
      uploadInProgress: false,
      farmImgError: false,
      valid: true,
      Mavatar: null, 
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      uberMapApiUrl: environment.uberMapApiUrl,
      uberMapToken: environment.uberMapToken,
      addForm: {
        customer_id:'',
        farm_images: [],
        latitude: "",
        longitude: "",
        farm_address: "",
        farm_unit: "",
        farm_city: "",
        farm_province: "",
        farm_zipcode: "",
        farm_active: true,
        manager_details: [],
      },
      UploadIndex: null,
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
        },
        revert:{
          url: "deleteImage",
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
    this.Mavatar = "/images/avatar.png";
    //add default form
    this.addRow();
  },
  methods: {
    addRow() {
      this.addForm.manager_details.push({
        manager_image: "",
        manager_prefix: "",
        manager_name: "",
        email: "",
        manager_phone: "",
        manager_address: "",
        manager_city: "",
        manager_province: "",
        manager_zipcode: "",
        manager_id_card: "",
        manager_card_image: "",
        manager_role: 5
      });
    },
    deleteRow(index) {
      this.addForm.manager_details.splice(index, 1);
    },
    setUploadIndex(index) {
      this.uploadInProgress = true;
      this.uploadIndex = index;
    },
    onChange(val) {
      this.items = "";
      // Items have already been loaded
      if (this.items.length > 1) return false;

      this.isLoading = true;
      // Lazily load input items
      axios
        .get(
          this.uberMapApiUrl + val + ".json?access_token=" + this.uberMapToken
        )
        .then(response => {
          this.items = response.data.features;
          this.isLoading = false;
          this.isOpen = true;
        });
    },
    setResult(result) {
      this.search = result.text;
      this.addForm.latitude = result.center[1];
      this.addForm.longitude = result.center[0];
      this.addForm.farm_address = result.text;
      this.isOpen = false;
    },
    //farm images process
    handleProcessFile1: function(error, file) {
      this.addForm.farm_images.push(file.serverId);
      this.farmImgError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function(file){
     this.addForm.farm_images = [];
      this.farmImgError = true;
    },
    //manager image process
    handleProcessFile2: function(error, file) {
      this.addForm.manager_details[this.uploadIndex].manager_image = file.serverId;
      this.Mavatar = this.imgUrl+file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile2: function(index){
      this.addForm.manager_details[index].manager_image = '';
    },
    //manager id card image process
    handleProcessFile3: function(error, file) {
      this.addForm.manager_details[this.uploadIndex].manager_card_image = file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile3: function(file){
    },

    _saveFarmInfo(customerId) {
      this.addForm.customer_id = customerId;
      customerService.addFarm(this.addForm).then(response => {
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
              router.push("/admin/customer");
            } else {
              router.push("/manager/customer");
            }
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
    },
    
    update() {
     if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      }
      if(this.addForm.farm_images.length == 0){
          this.farmImgError = true;
          return false;
      }
      if(this.addForm.manager_card_image){
          this.docError = true;
          return false;
      }
      if (this.$refs.customerForm.validate()) {
        //check if customer details exist if not then direct save farm
        if(localStorage.getItem("customerDetails") == null) {
          //save farm data
          this._saveFarmInfo(response.data.id);
        }
        //start loading
        this.loading = true;
        
        customerService.add(JSON.parse(localStorage.getItem("customerDetails"))).then(response => {
          //stop loading
          this.loading = false;
          //handle response
          if (response.status) {
            //remove customer details from localStorage
            localStorage.removeItem("customerDetails")
            //save farm data
            this._saveFarmInfo(response.data.id);
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

<style>
.setLeftPosition {
  float: left;
}
.setPosition {
  float: right;
}
.main-title {
  text-align: center;
  margin-top: 24px;
}
</style>
