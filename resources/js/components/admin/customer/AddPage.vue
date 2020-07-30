<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h4 class="main-title">Add Customer</h4>
        <v-col cols="12" md="12">
          <v-row>
            <v-col cols="12" md="12">
              <form-wizard
                @on-complete="update"
                title
                subtitle
                finishButtonText="Finish"
                shape="circle"
                color="gray"
                error-color="#e74c3c"
              >
                <tab-content title="Customer Info" :before-change="customerValidation">
                  <v-form ref="customerForm" v-model="valid" lazy-validation>
                    <v-row>
                      <v-col cols="12" md="12">
                        <div
                          class="v-avatar v-list-item__avatar"
                          style="height: 80px; min-width: 80px; width: 80px;"
                        >
                          <img :src="customer_img" />
                        </div>
                        <file-pond
                          name="uploadImage"
                          ref="pond"
                          label-idle="Add Profile pic..."
                          v-bind:allow-multiple="false"
                          v-bind:server="serverOptions"
                          v-bind:files="myFiles"
                          v-on:addfilestart="CustomerUploadIndex"
                          allow-file-type-validation="true"
                          accepted-file-types="image/jpeg, image/png"
                          v-on:processfile="handleProcessFile"
                          v-on:processfilerevert="handleRemoveFile"
                        />
                        <div
                          class="v-messages theme--light error--text"
                          role="alert"
                          v-if="profileImgError"
                        >
                          <div class="v-messages__wrapper">
                            <div class="v-messages__message">Profile image is required</div>
                          </div>
                        </div>
                      </v-col>
                      <v-col cols="3" md="3">
                        <v-select
                          v-model="addForm.prefix"
                          :items="prefixs"
                          label="Prefix"
                          :rules="[v => !!v || 'Prefix is required']"
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
                        <v-switch v-model="addForm.is_active" class="mx-2" label="Is Active"></v-switch>
                      </v-col>
                    </v-row>
                  </v-form>
                </tab-content>
                <tab-content title="Farm Info" :before-change="farmValidation">
                  <v-form ref="farmForm" v-model="valid" lazy-validation>
                    <v-col cols="12" md="12">
                      <h4 class="main-title">Farm Section</h4>
                      <v-row>
                        <v-col cols="12" md="12">
                          <file-pond
                            name="uploadImage"
                            ref="pond"
                            label-idle="Farm Images"
                            allow-multiple="true"
                            v-bind:server="serverOptions"
                            v-bind:files="myFiles"
                            v-on:addfilestart="CustomerUploadIndex"
                            allow-file-type-validation="true"
                            accepted-file-types="image/jpeg, image/png"
                            v-on:processfile="handleProcessFile1"
                            v-on:processfilerevert="handleRemoveFile1"
                          />
                          <div
                            class="v-messages theme--light error--text"
                            role="alert"
                            v-if="farmImgError"
                          >
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
                  </v-form>
                </tab-content>
                <tab-content title="Manager details">
                  <v-form ref="managerForm" v-model="valid" lazy-validation>
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
                              style="height: 40px; min-width: 40px; width: 40px;"
                            >
                              <img :src="'../../'+input.manager_image" />
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
                              v-model="input.manager_email"
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
                              v-on:processfilerevert="handleRemoveFile3(index)"
                            />
                          </v-col>
                        </v-row>
                      </template>
                    </v-col>
                    <v-btn
                      color="success"
                      class="mr-4 custom-save-btn ml-4 mt-4 setLeftPosition"
                      @click="addRow"
                    >Add More</v-btn>
                    <v-btn
                      type="button"
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      class="mr-4"
                      @click="AddAnotherFarm"
                    >Add Another Farm</v-btn>
                  </v-form>
                </tab-content>
              </form-wizard>
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
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { TrashIcon } from "vue-feather-icons";

export default {
  components: {
    VueGoogleAutocomplete,
    FormWizard,
    TabContent,
    TrashIcon
  },
  data() {
    return {
      isLoading: false,
      items: [],
      isOpen: false,
      model: null,
      profileImgError: false,
      farmImgError: false,
      search: null,
      loading: false,
      docError: false,
      prefixs: ["Ms.", "Mr.", "Mrs."],
      isLoading: false,
      model: null,
      valid: true,
      avatar: null,
      Mavatar: null,
      menu2: false,
      menu1: false,
      date: "",
      date1: "",
      customer_img: "",
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      uberMapApiUrl: environment.uberMapApiUrl,
      uberMapToken: environment.uberMapToken,
      addForm: {
        prefix: "",
        customer_name: "",
        email: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        user_image: null,
        zipcode: "",
        is_active: true,
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
        customer_role: 4
      },
      uploadInProgress: false,
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
    this.Mavatar = "/images/avatar.png";
    //add default form
    this.addRow();
  },
  methods: {
    CustomerUploadIndex() {
      this.uploadInProgress = true;
    },
    customerValidation: function() {
      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      }

      return new Promise((resolve, reject) => {
        setTimeout(() => {
          if (
            this.addForm.user_image == "" ||
            this.addForm.user_image == null
          ) {
            this.profileImgError = false;
          }
          if (this.$refs.customerForm.validate() && !this.profileImgError) {
            resolve(true);
          } else {
            resolve(false);
          }
        }, 1000);
      });
    },
    farmValidation: function() {
      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      }
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          if (this.addForm.farm_images.length == 0) {
            this.farmImgError = true;
          }

          if (this.$refs.farmForm.validate() && !this.farmImgError) {
            resolve(true);
          } else {
            resolve(false);
          }
        }, 1000);
      });
    },
    addRow() {
      this.addForm.manager_details.push({
        manager_image: "images/avatar.png",
        manager_prefix: "",
        manager_name: "",
        manager_email: "",
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
    handleProcessFile: function(error, file) {
      this.customer_img = this.imgUrl + file.serverId;
      this.addForm.user_image = file.serverId;
      this.profileImgError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function(file) {
      this.customer_img = "";
      this.addForm.user_image = "";
    },
    //farm images process
    handleProcessFile1: function(error, file) {
      this.addForm.farm_images.push(file.serverId);
      this.farmImgError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function(file) {},
    //manager image process
    handleProcessFile2: function(error, file) {
      this.addForm.manager_details[this.uploadIndex].manager_image =
        file.serverId;
      this.Mavatar = this.imgUrl + file.serverId;
      this.uploadInProgress = false;
    },
    //manager image process
    handleRemoveFile2: function(index) {
      this.addForm.manager_details[index].manager_image = "";
      this.addForm.manager_details[index].manager_image = "images/avatar.png";
    },
    //manager id card image process
    handleProcessFile3: function(error, file) {
      this.addForm.manager_details[this.uploadIndex].manager_card_image =
        file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile3: function(index) {
      this.addForm.manager_details[this.uploadIndex].manager_card_image = "";
    },
    validateFirstStep() {
      return new Promise((resolve, reject) => {
        this.$refs.form.validate(valid => {
          resolve(valid);
        });
      });
    },
    AddAnotherFarm() {
      if (this.$refs.managerForm.validate()) {
        //start loading
        this.loading = true;
        //store form into localstorage
        localStorage.setItem("customerDetails", JSON.stringify(this.addForm));
        //stop loading
        this.loading = false;
        //redirect
        const currentUser = authenticationService.currentUserValue;
        if (currentUser.data.user.role_id == 1) {
          const AddFarmurl = "/admin/customer/addfarm";
          router.push(AddFarmurl);
        } else {
          const AddFarmurl = "/manager/customer/addfarm";
          router.push(AddFarmurl);
        }
      }
    },
    update() {
      //validate if image uploading is in-progress
      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      }
      //validate if image uploading is in-progress

      //validate manager id card image
      var managerInfo = this.addForm.manager_details;
      for (var i = 0; i < managerInfo.length; i++) {
        if (managerInfo[i].manager_card_image == "") {
          this.$toast.open({
            message: "Manager ID card image is required!",
            type: "error",
            position: "top-right"
          });

          return false;
        }
      }
      //validate manager id card image

      if (this.$refs.managerForm.validate()) {
        //start loading
        this.loading = true;
        customerService.add(this.addForm).then(response => {
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
