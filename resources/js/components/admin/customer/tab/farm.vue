<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <div v-for="(updateForm, parentIndex) in totalForm">
          <v-col cols="12" md="12">
            <v-form class="customer-form" ref="form" v-model="valid" lazy-validation>
              <v-row>
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
                        allow-file-type-validation="true"
                        accepted-file-types="image/jpeg, image/png"
                        v-on:addfilestart="setUploadIndex(parentIndex)"
                        v-on:processfile="handleProcessFile1"
                        v-on:processfilerevert="handleRemoveFile1(parentIndex)"
                        :disabled="formDisable.includes(parentIndex) ? true:false"
                      />
                    </v-col>

                    <v-col cols="3" md="3" class="mt-4">
                      <v-text-field
                        type="text"
                        @input="onChange(updateForm.farm.farm_address)"
                        v-model="updateForm.farm.farm_address"
                        class="mt-m11"
                        label="Search Place"
                        required
                        :rules="[v => !!v || 'Place is required']"
                        :disabled="formDisable.includes(parentIndex) ? true:false"
                      ></v-text-field>
                      <ul v-show="isOpen && !formDisable.includes(parentIndex)" class="autocomplete-results">
                        <li
                          v-for="(result, i) in items"
                          :key="i"
                          @click="setResult(result, parentIndex)"
                          class="autocomplete-result"
                        >{{ result.place_name }}</li>
                      </ul>
                    </v-col>

                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Apt/Unit</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="updateForm.farm.farm_unit"
                          required
                          :rules="[v => !!v || 'Farm apt/unit is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">City</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="updateForm.farm.farm_city"
                          required
                          :rules="[v => !!v || 'Farm city is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Province</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="updateForm.farm.farm_province"
                          required
                          :rules="[v => !!v || 'Farm province is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Zip Code</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="updateForm.farm.farm_zipcode"
                          required
                          :rules="[v => !!v || 'Farm zip code is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-switch v-model="updateForm.farm.farm_active" class="mx-2" label="Is Active"></v-switch>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12" md="12">
                  <h3>Manager Details</h3>
                </v-col>
		<template  v-for="(manager, childIndex) in updateForm.managers">
                <v-col cols="12" md="12">
                  <v-row>
                    <v-col cols="12" md="12">
                      <div
                        class="v-avatar v-list-item__avatar"
                        style="height: 40px; min-width: 40px; width: 40px;"
                      >
                        <img :src="'../../../'+manager.manager_image" />
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
                        v-on:addfilestart="setUploadIndex(parentIndex, childIndex)"
                        v-on:processfile="handleProcessFile2"
                        v-on:processfilerevert="handleRemoveFile2(parentIndex, childIndex)"
                        :disabled="formDisable.includes(parentIndex) ? true:false"
                      />
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Prefix</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-select
                          v-model="manager.manager_prefix"
                          :items="prefixs"
                          label="Select"
                          :rules="[v => !!v || 'Prefix is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-select>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Name</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_name"
                          required
                          :rules="[v => !!v || 'Manager name is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">E-mail</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_email"
                          :rules="emailRules"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                          class="disabled-tag"
                          name="email"
                          required
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Phone</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_phone"
                          :rules="phoneRules"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                          class="disabled-tag"
                          required
                          maxlength="10"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Address</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_address"
                          required
                          :rules="[v => !!v || 'address is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">City</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_city"
                          required
                          :rules="[v => !!v || 'City is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">State</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_province"
                          required
                          :rules="[v => !!v || 'Province is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">zipcode</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_zipcode"
                          :rules="[v => !!v || 'Zip code is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                          required
                        ></v-text-field>
                      </v-col>
                    </v-col>
                    <v-col cols="3" md="3">
                      <v-col cols="4" sm="4" class="pl-0 pt-0 pb-0">
                        <label class="ft-normal">Id CardNo</label>
                      </v-col>
                      <v-col cols="8" sm="8" class="p-0 ml-m4">
                        <v-text-field
                          v-model="manager.manager_id_card"
                          :rules="[v => !!v || 'Card Id number is required']"
                          class="disabled-tag"
                          :disabled="formDisable.includes(parentIndex) ? true:false"
                          required
                        ></v-text-field>
                      </v-col>
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
                        v-on:addfilestart="setUploadIndex(parentIndex, childIndex)"
                        v-on:processfile="handleProcessFile3"
                        v-on:processfilerevert="handleRemoveFile3(parentIndex, childIndex)"
                        :disabled="formDisable.includes(parentIndex) ? true:false"
                      />
                    </v-col>
                  </v-row>
                </v-col>
                </template>
                <v-col cols="2" md="2">
                      <v-switch class="mx-2" label="Edit" @click="enableForm(parentIndex)"></v-switch>
                    </v-col>
                <v-btn
                  :loading="loading == parentIndex ? true:false"
                  :disabled="loading == parentIndex ? true:false"
                  color="success"
                  class="mr-4 custom-save-btn ml-4"
                  @click="update(parentIndex)"
                >Submit</v-btn>
              </v-row>
            </v-form>
          </v-col>
        </div>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { customerService } from "../../../../_services/customer.service";
import { router } from "../../../../_helpers/router";
import { environment } from "../../../../config/test.env";
import VueGoogleAutocomplete from "vue-google-autocomplete";
export default {
  components: {
    VueGoogleAutocomplete
  },
  data() {
    return {
      loading: null,
      docError: false,
      editSwitch: false,
      search: null,
      isOpen: false,
      prefixs: ["Ms.", "Mr.", "Mrs."],
      isLoading: false,
      uploadIndex: null,
      uploadChildIndex: null,
      items: [],
      model: null,
      valid: true,
      manager_img: "",
      apiUrl: environment.apiUrl,
      uberMapApiUrl: environment.uberMapApiUrl,
      uberMapToken: environment.uberMapToken,
      farmAndManagerForm: {
        farm: '',
        managers: Array()
      },
      totalForm: Array(),
      formDisable: Array(),
      addForm: {
        farm_id: "",
        farm_images: [],
        latitude: "",
        longitude: "",
        farm_address: "",
        farm_unit: "",
        farm_city: "",
        farm_province: "",
        farm_zipcode: "",
        farm_active: true
      },
      managerForm: {
        manager_id: "",
        manager_image: "",
        manager_prefix: "",
        manager_name: "",
        manager_email: "",
        manager_phone: "",
        manager_address: "",
        manager_city: "",
        manager_province: "",
        manager_zipcode: "",
        manager_id_card: "",
        manager_card_image: ""
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
    // this.$refs.address.focus();
    customerService.getFarmAndManager(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        for (
          var total = 0;
          total < response.data.length;
          total++
        ) {
          //make blank initially
          this.farmAndManagerForm = {
            farm: '',
            managers: []
          }

          var farmDetails = response.data[total];
          var managerDetails = response.data[total].farm_manager;
          //set farm values
          this.addForm = {
            farm_id: farmDetails.id,
            farm_images: JSON.parse(farmDetails.farm_image),
            latitude: farmDetails.latitude,
            longitude: farmDetails.longitude,
            farm_address: farmDetails.farm_address,
            farm_unit: farmDetails.farm_unit,
            farm_city: farmDetails.farm_city,
            farm_province: farmDetails.farm_province,
            farm_zipcode: farmDetails.farm_zipcode,
            farm_active: farmDetails.farm_active == 1 ? true : false
          };

          this.farmAndManagerForm.farm = this.addForm

          for(var fm=0; fm<managerDetails.length; fm++) {
            this.managerForm = {
              manager_id: managerDetails[fm].id,
              manager_image: managerDetails[fm].user_image,
              manager_prefix: managerDetails[fm].prefix,
              manager_name: managerDetails[fm].first_name,
              manager_email: managerDetails[fm].email,
              manager_phone: managerDetails[fm].phone,
              manager_address: managerDetails[fm].address,
              manager_city: managerDetails[fm].city,
              manager_province: managerDetails[fm].state,
              manager_zipcode: managerDetails[fm].zip_code,
              manager_id_card: managerDetails[fm].manager.identification_number,
              manager_card_image: managerDetails[fm].manager.document
            };
            //add to manager info
            this.farmAndManagerForm.managers.push(this.managerForm);
          } 

          //add into total forms
          this.totalForm.push(this.farmAndManagerForm);
          //add into disabled forms
          this.formDisable.push(total);
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
    this.manager_image = "/images/avatar.png";
  },
  methods: {
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
    setResult(result, index) {
      this.search = result.text;
      
      this.totalForm[index].latitude = result.center[1];
      this.totalForm[index].longitude = result.center[0];
      this.totalForm[index].farm_address = result.text;

      this.isOpen = false;
    },
    setUploadIndex(parentIndex, childIndex=null) {
      this.uploadIndex = parentIndex;
      this.uploadChildIndex = childIndex;
    },
    //farm images process
    handleProcessFile1: function(error, file) {
      this.totalForm[this.uploadIndex].farm.farm_images.push(file.serverId);
      //set default as null
      this.uploadIndex = null;
      this.uploadChildIndex = null;
    },
    //farm image remove process
    handleRemoveFile1: function(parentIndex) {
    },
    //manager image process
    handleProcessFile2: function(error, file) {
      this.totalForm[this.uploadIndex].managers[this.uploadChildIndex].manager_image = file.serverId;
      //set default as null
      this.uploadIndex = null;
      this.uploadChildIndex = null;
    },
    //manager image process
    handleRemoveFile2: function(parentIndex, childIndex) {
      this.totalForm[parentIndex].managers[childIndex].manager_image = '';
      this.totalForm[parentIndex].managers[childIndex].manager_image = 'images/avatar.png';
    },
    //manager id card image process
    handleProcessFile3: function(error, file) {
      this.totalForm[this.uploadIndex].managers[this.uploadChildIndex].manager_card_image = file.serverId;
      //set default as null
      this.uploadIndex = null;
      this.uploadChildIndex = null;
    },
    //manager id card image removal process
    handleRemoveFile3: function(parentIndex, childIndex) {
      this.totalForm[parentIndex].managers[childIndex].manager_card_image = '';
    },
    enableForm(formId) {
      var index = this.formDisable.indexOf(formId);
      if (index > -1) {
        //remove if found
        this.formDisable.splice(index, 1);
      } else {
        //add into array
        this.formDisable.push(formId);
      }
    },
    update(formId) {
      //validate if image uploading is in-progress
      if(this.uploadIndex != null) {
        this.$toast.open({
              message: "Image uploading is in progress!",
              type: "error",
              position: "top-right"
            });
            return false;
      }
      //validate if image uploading is in-progress

      if (this.$refs.form[formId].validate()) {
      //start loading
      this.loading = formId;
      customerService
        .updateFarmManager(this.totalForm[formId])
        .then(response => {
          //stop loading
          this.loading = null;
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
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
