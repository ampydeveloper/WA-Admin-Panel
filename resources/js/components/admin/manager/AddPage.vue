<template>
  <v-app>
    <v-container fluid>
      <v-row>
     <h4 class="main-title text-left">Add New Manager</h4>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="update">
            <v-row>
             <div
                class="v-avatar v-list-item__avatar"
                style="height: 80px; min-width: 80px; width: 80px;"
              >
                <img :src="avatar" />
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
                <div class="v-messages theme--light error--text" role="alert" v-if="profileImgError">
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">Profile image is required</div>
                    </div>
                  </div>
              </v-col>
              <v-col cols="6" md="6" class="pl-0 manager-cols">
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Manager name</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.first_name"
                      required
                      :rules="[v => !!v || 'Manager name is required']"
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>E-mail</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.email"
                      :rules="emailRules"
                      name="email"
                      required
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Address</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.address"
                      required
                      :rules="[v => !!v || 'address is required']"
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>City</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.city"
                      required
                      :rules="[v => !!v || 'City is required']"
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>State</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.state"
                      required
                      :rules="[v => !!v || 'State is required']"
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Country</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.country"
                      required
                      :rules="[v => !!v || 'Country is required']"
                    ></v-text-field>
                  </v-col>
                </v-col>
              </v-col>

              <v-col cols="6" md="6" class="pl-0 manager-cols">
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Zipcode</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.manager_zipcode"
                      :rules="[v => !!v || 'Zip code is required']"
                      required
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Mobile Number</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.manager_phone"
                      :rules="phoneRules"
                      required
                      maxlength="10"
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Identification number</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.identification_number"
                      required
                      :rules="[v => !!v || 'Identification number is required']"
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" md="12" class="custom-col calendar-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Joining Date</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
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
                </v-col>

                <v-col cols="12" md="12" class="custom-col calendar-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Releaving date(if required)</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
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
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date1" @input="menu2 = false"></v-date-picker>
                  </v-menu>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col">
                  <v-col sm="4" class="label-align pt-0">
                    <label>Manager Salary</label>
                  </v-col>
                  <v-col sm="7" class="pt-0">
                    <v-text-field
                      v-model="addForm.salary"
                      required
                      :rules="salaryRules"
                    ></v-text-field>
                  </v-col>
                </v-col>
                <v-col cols="12" md="12" class="custom-col custom-img-holder">
                <!-- single id document -->
                <div class="col-img-holder">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
		    v-bind:required="true"
		   :rules="documentRules"
                    label-idle="Identification Document..."
                    v-bind:allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:addfilestart="setUploadIndex"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                    v-on:processfile="handleProcessFile1"
                v-on:processfilerevert="handleRemoveFile1"
                  />
		<div class="v-messages theme--light error--text" role="alert" v-if="docError">
		<div class="v-messages__wrapper"><div class="v-messages__message">Document upload is required</div></div>
		</div>
                  </div>
                  <!-- ends here -->
                </v-col>
              </v-col>

              <v-btn :loading="loading" :disabled="loading" color="success" type="submit" class="mr-4 custom-save-btn ml-4 manager-save" @click="update">Submit</v-btn>
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
      profileImgError: false,
      menu1: false,
      date: "",
      date1: "",
      apiUrl: environment.apiUrl,
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
      salaryRules: [
        v => !!v || "Manager salary is required",
        v => /^\d*$/.test(v) || "Enter valid number"
      ],
      documentRules: [
        v => !!v || "Manager salary is rdfdfdfequired"
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
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function(error, file) {
      this.addForm.user_image = file.serverId;
      this.avatar = "../../"+file.serverId;
      this.profileImgError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function(file){
      this.addForm.user_image = '';
      this.avatar = "/images/avatar.png";
    },
    handleProcessFile1: function(error, file) {
      this.docError = false
      this.uploadInProgress = false;
      this.addForm.document = file.serverId;
    },
    handleRemoveFile1: function(file){
      this.addForm.document = '';
      this.docError = true;
    },
    update: function(e) {
      //stop page to reload
      e.preventDefault();
      if(this.addForm.document == ''){
	      this.docError = true
       }

       if(this.uploadInProgress) {
        this.$toast.open({
              message: "Image uploading is in progress!",
              type: "error",
              position: "top-right"
            });
            return false;
      }

      //  if(this.addForm.user_image == '' || this.addForm.user_image == null){
	    //   this.profileImgError = true;
      //  }
      this.addForm.joining_date = this.date;
      this.addForm.releaving_date = this.date1;
      if (this.$refs.form.validate() && (!this.docError && !this.profileImgError)) {
        if(this.loading) {
          return false;
        }
        //start loader
        this.loading = true;
        managerService.add(this.addForm).then(response => {
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
             const currentUser = JSON.parse(localStorage.getItem("currentUser"));
              if(currentUser.data.user.role_id == 1){
	       router.push("/admin/manager");
              }else{
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
