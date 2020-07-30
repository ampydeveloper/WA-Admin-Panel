<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h4 class="main-title">Edit Hauler</h4>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="12" md="12">
                <v-row>
                  <v-col cols="12" md="12">
                    <div
                      class="v-avatar v-list-item__avatar"
                      style="height: 80px; min-width: 80px; width: 80px;"
                    >
            <button type="submit" class="close AClass" style="margin-right: 13px; margin-top: -25px; font-size: 30px;" v-if="cross" @click="Remove()">
               <span>&times;</span>
             </button>
                      <img :src="avatar" />
                    </div>
                    <file-pond
                      name="uploadImage"
                      ref="pond"
                      label-idle="Add Profile pic..."
                       v-bind:allow-multiple="false"
                      v-bind:server="serverOptions"
                      v-bind:files="myFiles"
                  v-on:addfilestart="setUploadIndex"
                      allow-file-type-validation="true"
                      accepted-file-types="image/jpeg, image/png"
                      v-on:processfile="handleProcessFile"
                  v-on:processfilerevert="handleRemoveFile"
                    />
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
              </v-col>

              <v-btn
                :loading="loading"
                :disabled="loading"
                color="success"
                class="mr-4 custom-save-btn ml-4 mt-4"
                @click="update"
              >Submit</v-btn>
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
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    VueGoogleAutocomplete
  },
  data() {
    return {
      loading: false,
      prefixs: ["Ms.", "Mr.", "Mrs."],
      valid: true,
      avatar: null,
      customer_img: "",
      uploadInProgress: false,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      uberMapApiUrl: environment.uberMapApiUrl,
      uberMapToken: environment.uberMapToken,
      cross: false,
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
        customer_role: 6
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
    this.customer_img = "/images/avatar.png";
  },
  mounted: function() {
    companyService.getHauler(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        console.log(response.data.joining_date);
        this.addForm = {
          prefix: response.data.prefix,
          customer_name: response.data.first_name,
          city: response.data.city,
          email: response.data.email,
          province: response.data.state,
          user_image: response.data.user_image,
          phone: response.data.phone,
          zipcode: response.data.zip_code,
          address: response.data.address,
          is_active: response.data.is_active,
        };
        if(response.data.user_image){
          this.cross=true;
          this.avatar = this.imgUrl+response.data.user_image;
        }else{
           this.avatar = "/images/avatar.png";
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
  methods: {
    Remove(){
     this.avatar = "/images/avatar.png";
     this.cross=false;
     this.addForm.user_image = '';
    },
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function(error, file) {
      this.avatar = this.imgUrl + file.serverId;
      this.addForm.user_image = file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function(file){
      this.addForm.user_image = '';
      this.avatar = "/images/avatar.png";
      this.cross=false;
    },
    update() {
      if(this.uploadInProgress) {
        this.$toast.open({
              message: "Image uploading is in progress!",
              type: "error",
              position: "top-right"
            });
            return false;
      }
      if (this.$refs.form.validate()) {
        //start loading
        this.loading = true;
        companyService.edit(this.addForm, this.$route.params.id).then(response => {
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
	    if(currentUser.data.user.role_id == 1){
	     router.push("/admin/hauler");
	    }else{
	     router.push("/manager/hauler");
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
