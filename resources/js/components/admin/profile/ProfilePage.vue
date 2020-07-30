<template>
  <v-app>
    <v-container fluid>
      <v-row>
      <h4 class="main-title text-left">Edit Profile</h4>
        <v-col cols="12" md="12">
          
          <v-form ref="form" v-model="valid" enctype="multipart/form-data" lazy-validation @submit="update">
            <v-col cols="12" md="12">
              <div
                class="v-avatar v-list-item__avatar"
                style="height: 80px; min-width: 80px; width: 80px; position:relative;"
              >
              <button type="button" class="close AClass" style="margin-right: 13px; margin-top: -25px; font-size: 30px;" v-if="cross" @click="Remove()">
               <span>&times;</span>
             </button>
                <img :src="avatar" />
              </div>
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
            <v-col cols="12" md="12" class="pt-0">
              <v-col sm="2" class="label-align pt-0">
                <label>First name</label>
              </v-col>
              <v-col sm="4" class="pt-0">
              <v-text-field
                v-model="updateForm.first_name"
                :rules="FnameRules"
                required
              ></v-text-field>
              </v-col>
            </v-col>

            <v-col cols="12" md="12" class="pt-0">
              <v-col sm="2" class="label-align pt-0">
                <label>Last name</label>
              </v-col>
              <v-col sm="4" class="pt-0">
              <v-text-field
                v-model="updateForm.last_name"
                :rules="LnameRules"
                required
              ></v-text-field>
              </v-col>
            </v-col>

            <v-col cols="12" md="12" class="pt-0">
              <v-col sm="2" class="label-align pt-0">
                <label>E-mail</label>
              </v-col>
              <v-col sm="4" class="pt-0">
              <v-text-field
                v-model="updateForm.email"
                :rules="emailRules"
                name="email"
                required
              ></v-text-field>
              </v-col>
            </v-col>
            <!-- <v-btn color="success" class="mr-4" @click="update">Submit</v-btn> -->

            <v-btn color="success" type="submit" :loading="loading" :disabled="isvalid"  class="mr-4 custom-save-btn ml-4" @click="update">Submit</v-btn>

            <v-btn color="success" v-if="updateForm.user_id != 1" class="mr-4" @click="Delete(updateForm.user_id)">Delete Account</v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { authenticationService } from "../../../_services/authentication.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";

export default {
  components: {
  },
  data() {
    return {
      myFiles: "",
      valid: true,
      isvalid:false,
      loading: false,
      apiUrl: environment.apiUrl,
      baseUrl: environment.baseUrl,
      avatar: null,
      test: "",
      cross: false,
      uploadInProgress: false,
      updateForm: {
        user_id: null,
        first_name: "",
        last_name: "",
        email: "",
        user_image: ""
      },
      FnameRules: [v => !!v || "First name is required"],
      LnameRules: [v => !!v || "Last name is required"],
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+/.test(v) || "E-mail must be valid"
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
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    this.updateForm.user_id = currentUser.data.user.id;
    this.updateForm.user_image = currentUser.data.user.user_image;
    if(currentUser.data.user.user_image) { 
      this.cross=true;
      this.avatar = "../../"+currentUser.data.user.user_image;
    } else {
      this.avatar = "/images/avatar.png";
    }
    this.updateForm.first_name = currentUser.data.user.first_name;
    this.updateForm.last_name = currentUser.data.user.last_name;
    this.updateForm.email = currentUser.data.user.email;
  },
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    handleProcessFile: function(error, file) {
      this.updateForm.user_image = file.serverId;
      this.avatar = "../../"+file.serverId;
      this.uploadInProgress = false;
      this.cross=false;
    },
    handleRemoveFile: function(file){
      this.updateForm.user_image = '';
      this.avatar = "/images/avatar.png";
      this.cross=false;
    },
    update: function(e) {
      //stop page to reload
      e.preventDefault();

      if(this.uploadInProgress) {
        this.$toast.open({
              message: "Profile image uploading is in progress!",
              type: "error",
              position: "top-right"
            });
            return false;
      }
      if (this.$refs.form.validate()) {
        if(this.loading) {
          return false;
        }

        //start loading
        this.loading = true;

        authenticationService.updateProfile(this.updateForm).then(response => {
          //stop loading
          this.loading = false;
          //handle response
          if (response.status) {
            //change header image
            document.getElementById("userImage").src =  "../../"+this.updateForm.user_image;
            //load from local storage
            var getStorage = JSON.parse(localStorage.getItem("currentUser"));
            getStorage.data.user.user_image = this.updateForm.user_image;
            //remove from local storage
            getStorage.data.user = response.data;
            localStorage.removeItem("currentUser");
            //add again to local storage
            localStorage.setItem("currentUser", JSON.stringify(getStorage));
            //change header image
            if(this.updateForm.user_image){
            	document.getElementById("userImage").src = "../../"+this.updateForm.user_image;
            }else{
              document.getElementById("userImage").src = "/images/avatar.png";
	    }
		

            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            //router.push("/admin/profile");
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
      }
    },
  Remove(){
    this.avatar = "/images/avatar.png";
    this.cross=false;
    this.updateForm.user_image = '';
  },
  Delete(e) {
      if (e) {
        authenticationService.Delete(e).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            this.dialog = false;

            //reload table
            // remove user from local storage to log user out
            localStorage.removeItem("currentUser");
	    router.push("/login");

          } else {
            this.dialog = false;
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
      }
    },
  }
};
</script>
<style>
.AClass{
    right:0px;
    position: absolute;
}

</style>
