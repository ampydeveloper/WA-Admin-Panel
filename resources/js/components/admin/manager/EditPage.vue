<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h4 class="main-title">Edit Manager</h4>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="update">
            <v-row>
              <div
                class="v-avatar v-list-item__avatar"
                style="height: 80px; min-width: 80px; width: 80px;"
              >
            <button type="button" class="close AClass" style="margin-right: 13px; margin-top: -25px; font-size: 30px;" v-if="cross" @click="Remove()">
               <span>&times;</span>
             </button>
                <img :src="avatar" alt="Manager" />
              </div>

              <v-col cols="12" md="12" class="custom-img-holder">
                <file-pond
                  name="uploadImage"
                  ref="pond"
                  label-idle="Drop files here..."
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
              <v-col cols="6" md="6" class="pl-0 manager-cols">
                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.first_name"
                    label="Manager name"
                    required
                    :rules="[v => !!v || 'Manager name is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.email"
                    :rules="emailRules"
                    name="email"
                    label="E-mail"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.address"
                    label="Address"
                    required
                    :rules="[v => !!v || 'address is required']"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.city"
                    label="City"
                    required
                    :rules="[v => !!v || 'City is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.state"
                    label="State"
                    required
                    :rules="[v => !!v || 'State is required']"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.country"
                    label="Country"
                    required
                    :rules="[v => !!v || 'Country is required']"
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="6" md="6" class="pl-0 manager-cols">
                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.manager_zipcode"
                    :rules="[v => !!v || 'Zip code is required']"
                    label="zipcode"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.manager_phone"
                    :rules="phoneRules"
                    label="Mobile Number"
                    required
                    maxlength="10"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.identification_number"
                    label="Identification number"
                    required
                    :rules="[v => !!v || 'Identification number is required']"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12" class="custom-col calendar-col">
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
                        label="Joining Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        required
                        :rules="[v => !!v || 'Joining date is required']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu1 = false"></v-date-picker>
                  </v-menu>
                </v-col>

                <v-col cols="12" md="12" class="custom-col calendar-col">
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
                        label="Releaving date(if required)"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date1" @input="menu2 = false"></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-text-field
                    v-model="addForm.salary"
                    label="Manager Salary"
                    required
                    :rules="[v => !!v || 'Manager salary is required']"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" class="custom-col custom-img-holder">
                  <div class="col-img-holder">
                    <file-pond
                      name="uploadImage"
                      ref="pond"
                      label-idle="Identification Document..."
                      v-bind:allow-multiple="false"
                      v-bind:required="true"
                      v-bind:server="serverOptions"
                      v-bind:files="myFiles"
                      v-on:addfilestart="setUploadIndex"
                      allow-file-type-validation="true"
                      accepted-file-types="image/jpeg, image/png"
                      v-on:processfile="handleProcessFile1"
                      v-on:processfilerevert="handleRemoveFile1"
                    />
                    <div class="v-messages theme--light error--text" role="alert" v-if="docError">
                      <div class="v-messages__wrapper">
                        <div class="v-messages__message">Document upload is required</div>
                      </div>
                    </div>
                  </div>
                  <div class style="height: 200px; min-width: 200px; width: 200px;">
                    <img
                      style="width:100%"
                      v-if="documentImg"
                      :src="documentImg"
                      alt="Doc"
                    />
                  </div>
                </v-col>
              </v-col>

              <v-btn
              :loading="loading" :disabled="loading"
              type="submit"
                color="success"
                class="mr-4 custom-save-btn ml-4 manager-save"
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
import { managerService } from "../../../_services/manager.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";

export default {
  components: {
    //      'image-component': imageVUE,
  },
  data() {
    return {
      loading: null,
      docError: false,
      valid: true,
      avatar: null,
      menu2: false,
      uploadInProgress: false,
      menu1: false,
      documentImg:null,
      date: "",
      date1: "",
      cross: false,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      addForm: {
        first_name: "",
        city: "",
        email: "",
        state: "",
        country: "",
        user_image: null,
        phone: "",
        role_id: 2,
        document: "",
        joining_date: "",
        releaving_date: "",
        identification_number: "",
        salary: "",
        manager_phone: "",
        manager_zipcode: "",
        address: ""
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
    this.avatar = "/images/avatar.png";
  },
  mounted: function() {
    managerService.getManager(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        console.log(response.data.joining_date);
        this.addForm = {
          first_name: response.data.user.first_name,
          city: response.data.user.city,
          email: response.data.user.email,
          state: response.data.user.state,
          country: response.data.user.country,
          user_image: response.data.user.user_image,
          role_id: 2,
          document: response.data.document,
          identification_number: response.data.identification_number,
          salary: response.data.salary,
          manager_phone: response.data.user.phone,
          manager_zipcode: response.data.user.zip_code,
          address: response.data.user.address
        };
        if(response.data.user.user_image){
          this.cross=true;
          this.avatar = this.imgUrl+response.data.user.user_image;
        }else{
           this.avatar = "/images/avatar.png";
        }
        if(response.data.document){
         this.documentImg = this.imgUrl+response.data.document;
        }
        this.date = response.data.joining_date;
        this.date1 = response.data.releaving_date;
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
      this.cross=false;
      this.addForm.user_image = file.serverId;
      this.uploadInProgress = false;
      this.avatar = this.imgUrl+file.serverId;
    },
    handleRemoveFile: function(file){
      this.addForm.user_image = '';
      this.cross=false;
      this.avatar = "/images/avatar.png";
    },
    handleProcessFile1: function(error, file) {
      this.docError = false;
      this.addForm.document = file.serverId;
      this.documentImg = this.imgUrl+file.serverId;
      this.uploadInProgress = false;
    },
    handleRemoveFile1: function(file){
      this.addForm.document = '';
      this.documentImg = '';
      this.docError = true;
    },
    update: function(e) {
      //stop page to reload
      e.preventDefault();

      if (this.addForm.document == "") {
        this.docError = true;
      }

      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      }

      this.addForm.joining_date = this.date;
      this.addForm.releaving_date = this.date1;

      if (this.$refs.form.validate() && !this.docError) {
        if(this.loading) {
          return false;
        }
        //start loader
        this.loading = true;

        managerService
          .edit(this.addForm, this.$route.params.id)
          .then(response => {
              //stop loader
        this.loading = false;

            //handle response
            if (response.status) {
              this.$toast.open({
                message: response.message,
                type: "success",
                position: "top-right"
              });
              //redirect to login
              const currentUser = JSON.parse(
                localStorage.getItem("currentUser")
              );
              if (currentUser.data.user.role_id == 1) {
                router.push("/admin/manager");
              } else {
                router.push("/manager/manager");
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
