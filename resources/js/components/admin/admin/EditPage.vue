<template>
  <v-app>
    <v-container>
      <v-row>
     <h2>Edit Admin</h2>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation @submit="update">
            <v-col cols="12" md="12">
              <div v-if="avatar"
                class="v-avatar v-list-item__avatar"
                style="height: 80px; min-width: 80px; width: 80px;"
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
                accepted-file-types="image/jpeg, image/png"
                v-bind:server="serverOptions"
                v-bind:files="myFiles"
               v-on:addfilestart="setUploadIndex"
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
                v-model="addForm.first_name"
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
                v-model="addForm.last_name"
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
                v-model="addForm.email"
                :rules="emailRules"
                name="email"
                required
              ></v-text-field>
              </v-col>
            </v-col>
            <v-btn type="submit" :loading="loading" :disabled="loading" color="success" class="mr-4 custom-save-btn" @click="update">Submit</v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { adminService } from "../../../_services/admin.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";

export default {
  components: {
    //      'image-component': imageVUE,
  },
  data() {
    return {
      loading: false,
      valid: true,
      avatar: null,
     uploadInProgress:false,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      cross: false,
      addForm: {
        first_name: "",
        last_name: "",
        email: "",
        user_image: null,
        phone: ""
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
          url: 'uploadImage',
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
   this.loading = true;
    adminService.getAdmin(this.$route.params.id).then(response => {
      if (response.status) {
        this.addForm.user_id = response.data.id;
        if (response.data.user_image) {
          this.cross=true;
          this.addForm.user_image = response.data.user_image;
          this.avatar = this.imgUrl+response.data.user_image;
        }
        else {
          this.avatar = "/images/avatar.png";
        }
        this.addForm.first_name = response.data.first_name;
        this.addForm.last_name = response.data.last_name;
        this.addForm.email = response.data.email;
        this.addForm.user_image = response.data.user_image;
      } else {
        router.push("/admin/manager");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
      this.loading = false;
    });
  },
  methods: {
     setUploadIndex() {
      this.uploadInProgress = true;
      },
    handleProcessFile: function(error, file) {
      this.addForm.user_image = file.serverId;
      this.avatar = this.imgUrl+file.serverId;
      this.uploadInProgress = false;
      this.cross=false;
    },
    handleRemoveFile: function(file){
      this.addForm.user_image = '';
      this.avatar = "/images/avatar.png";
      this.cross=false;
    },
    Remove(){
	    this.avatar = "/images/avatar.png";
	    this.cross=false;
	    this.addForm.user_image = '';
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
        adminService.edit(this.addForm, this.$route.params.id).then(response => {
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
            router.push("/admin/admin");
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
          this.loading = false;
        });
      }
    }
  }
};
</script>
