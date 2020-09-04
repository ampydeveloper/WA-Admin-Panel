<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12">
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            class="v-form custom_form_field custom-fill"
            id="form_field"
          >
            <v-row>
              <v-col cols="12" md="12" class="customer-edit-first pt-0 pb-0">
                <div class="v-avatar v-list-item__avatar">
                  <button type="button" class="close" v-if="cross" @click="Remove()">
                    <span>&times;</span>
                  </button>
                  <img :src="avatar" />
                </div>
                <file-pond
                  name="uploadImage"
                  ref="pond"
                  label-idle="Update Profile Photo"
                  allow-multiple="false"
                  v-bind:server="serverOptions"
                  v-bind:files="myFiles"
                  v-on:addfilestart="setUploadIndex"
                  allow-file-type-validation="true"
                  accepted-file-types="image/jpeg, image/png"
                  v-on:processfile="handleProcessFile"
                  v-on:processfilerevert="handleRemoveFile"
                  :disabled="disabled == 0"
                />
              </v-col>
              <v-col cols="4" md="4" class="pt-0 pb-0">
                <v-select
                  v-model="addForm.prefix"
                  class="disabled-tag"
                  :items="prefixs"
                  label="Select Prefix"
                  :rules="[v => !!v || 'Prefix is required.']"
                  :disabled="disabled == 0"
                ></v-select>
              </v-col>
              <v-col cols="4" sm="4" class="pt-0 pb-0">
                <v-text-field
                  v-model="addForm.customer_name"
                  required
                  :disabled="disabled == 0"
                  label="Name"
                  class="disabled-tag"
                  :rules="[v => !!v || 'Customer name is required.']"
                ></v-text-field>
              </v-col>
              <v-col cols="4" md="4" class="pt-0 pb-0">
                <v-text-field
                  v-model="addForm.email"
                  :rules="emailRules"
                  name="email"
                  label="Email"
                  :disabled="disabled == 0"
                  class="disabled-tag"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="4" md="4" class="pt-0 pb-0">
                <v-text-field
                  v-model="addForm.phone"
                  :rules="phoneRules"
                  :disabled="disabled == 0"
                  label="Phone"
                  class="disabled-tag"
                  required
                  maxlength="10"
                ></v-text-field>
              </v-col>
              <v-col cols="4" md="4" class="pt-0 pb-0">
                <v-text-field
                  v-model="addForm.address"
                  :disabled="disabled == 0"
                  class="disabled-tag"
                  label="Address"
                  required
                  :rules="[v => !!v || 'Address is required.']"
                ></v-text-field>
              </v-col>
              <v-col cols="4" md="4" class="pt-0 pb-0">
                <v-text-field
                  v-model="addForm.city"
                  :disabled="disabled == 0"
                  class="disabled-tag"
                  label="City"
                  required
                  :rules="[v => !!v || 'City is required.']"
                ></v-text-field>
              </v-col>
              <v-col cols="4" md="4" class="pt-0 pb-0">
                <v-text-field
                  v-model="addForm.province"
                  :disabled="disabled == 0"
                  class="disabled-tag"
                  label="Province"
                  required
                  :rules="[v => !!v || 'Province is required.']"
                ></v-text-field>
              </v-col>
              <v-col cols="4" md="4" class="pt-0 pb-0">
                <v-text-field
                  v-model="addForm.zipcode"
                  label="Zipcode"
                  :rules="[v => !!v || 'Zipcode is required.']"
                  :disabled="disabled == 0"
                  class="disabled-tag"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="2" md="2">
                <v-switch v-model="addForm.is_active" class="mx-2" label="Status"></v-switch>
              </v-col>
              <v-col cols="2" md="2">
                <v-switch
                  v-model="editSwitch"
                  class="mx-2"
                  label="Edit"
                  @click="disabled = (disabled + 1) % 2"
                ></v-switch>
              </v-col>

              <v-col class="pt-0 pb-0" cols="12" md="12">
                <div class="p-0 float-right">
                  <v-btn
                    :loading="loading"
                    :disabled="loading"
                    color="success"
                    type="submit"
                    class="custom-save-btn mr-4"
                    @click="update"
                    id="submit_btn"
                  >Save</v-btn>
                </div>
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
import { customerService } from "../../../../_services/customer.service";
import { router } from "../../../../_helpers/router";
import { environment } from "../../../../config/test.env";
import { authenticationService } from "../../../../_services/authentication.service";
export default {
  data() {
    return {
      loading: false,
      prefixs: ["Ms.", "Mr.", "Mrs."],
      isLoading: false,
      editSwitch: false,
      disabled: 0,
      items: [],
      model: null,
      valid: true,
      avatar: null,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      uploadInProgress: false,
      isAdmin: true,
      cross: false,
      addForm: {
        id: "",
        prefix: "",
        customer_name: "",
        email: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        user_image: null,
        zipcode: "",
        is_active: "",
      },
      emailRules: [
        (v) => !!v || "Email is required.",
        (v) => /.+@.+/.test(v) || "Email must be valid.",
      ],
      phoneRules: [
        (v) => !!v || "Phone number is required.",
        (v) => /^\d*$/.test(v) || "Enter valid number.",
        (v) => v.length >= 10 || "Enter valid number.",
      ],
      rules: [
        (value) =>
          !value ||
          value.size < 2000000 ||
          "Image size should be less than 2 MB.",
      ],
      myFiles: [],
    };
  },
  watch: {
    editSwitch(newValue) {
      if (newValue == true) {
        // console.log('yes');
      } else {
        // console.log('no');
      }
    },
  },
  mounted: function () {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    customerService.getCustomer(this.$route.params.id).then((response) => {
      //handle response
      if (response.status) {
        this.addForm = {
          id: response.data.id,
          prefix: response.data.prefix,
          customer_name: response.data.first_name,
          phone: response.data.phone,
          email: response.data.email,
          city: response.data.city,
          province: response.data.state,
          country: response.data.country,
          user_image: response.data.user_image,
          address: response.data.address,
          zipcode: response.data.zip_code,
          is_active: response.data.is_active,
        };
        if (response.data.user_image) {
          this.cross = true;
          this.avatar = this.imgUrl + response.data.user_image;
        } else {
          this.avatar = "/images/avatar.png";
        }
      } else {
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
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
            Authorization: "Bearer " + currentUser.data.access_token,
          },
        },
        revert: {
          url: "deleteImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token,
          },
        },
      };
    },
    url() {
      if (this.file) {
        let parsedUrl = new URL(this.file);
        return [parsedUrl.pathname];
      } else {
        return null;
      }
    },
  },
  methods: {
    Remove() {
      this.avatar = "/images/avatar.png";
      this.cross = false;
      this.addForm.user_image = "";
    },
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function (error, file) {
      this.avatar = this.imgUrl + file.serverId;
      this.addForm.user_image = file.serverId;
    },
    handleRemoveFile: function (file) {
      this.addForm.user_image = "";
      this.cross = false;
      this.avatar = "/images/avatar.png";
    },
    update() {
      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress.",
          type: "error",
          position: "top-right",
        });
        return false;
      }
      if (this.$refs.form.validate()) {
        //start loading
        this.loading = true;
        customerService.edit(this.addForm).then((response) => {
          //stop loading
          this.loading = false;
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
            //redirect to login
            //router.push("/admin/customer");
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right",
            });
          }
        });
      }
    },
    Delete() {},
  },
};
</script>