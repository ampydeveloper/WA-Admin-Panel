<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <h2>Admin Profile</h2>
        </v-col>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" enctype="multipart/form-data" lazy-validation>
            <v-col cols="12" md="12">
              <div
                class="v-avatar v-list-item__avatar"
                style="height: 40px; min-width: 40px; width: 40px;"
              >
                <img :src="'../../'+updateForm.user_image" />
              </div>
              <file-pond
                name="uploadImage"
                ref="pond"
                label-idle="Drop files here..."
                allow-multiple="false"
                v-bind:server="serverOptions"
                v-bind:files="myFiles"
                v-on:processfile="handleProcessFile"
              />
            </v-col>
            <v-col cols="12" md="12">
              <v-text-field
                v-model="updateForm.first_name"
                :rules="FnameRules"
                label="First name"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-text-field
                v-model="updateForm.last_name"
                :rules="LnameRules"
                label="Last name"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-text-field
                v-model="updateForm.email"
                :rules="emailRules"
                name="email"
                label="E-mail"
                required
              ></v-text-field>
            </v-col>
            <v-btn color="success" class="mr-4" @click="update">Submit</v-btn>
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
import FilePond from "../../../filepond";
import { environment } from "../../../config/test.env";

export default {
  components: {
    //      'image-component': imageVUE,
  },
  data() {
    return {
      myFiles: "",
      valid: true,
      apiUrl: environment.apiUrl,
      baseUrl: environment.baseUrl,
      avatar: null,
      test: "",
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
    if (currentUser.data.user.image) {
      this.avatar = currentUser.data.user.image;
    } else {
      this.avatar = "/images/avatar.png";
    }
    this.updateForm.first_name = currentUser.data.user.first_name;
    this.updateForm.last_name = currentUser.data.user.last_name;
    this.updateForm.email = currentUser.data.user.email;
  },
  methods: {
    handleProcessFile: function(error, file) {
      this.updateForm.user_image = file.serverId;
    },
    GetImage(e) {
      this.avatar = URL.createObjectURL(e);
    },
    update() {
      if (this.$refs.form.validate()) {
        authenticationService.updateProfile(this.updateForm).then(response => {
          //handle response
          if (response.status) {
            //load from local storage
            var getStorage = JSON.parse(localStorage.getItem("currentUser"));
            getStorage.data.user.user_image = this.updateForm.user_image;
            //remove from local storage
            localStorage.removeItem("currentUser");
            //add again to local storage
            localStorage.setItem("currentUser", JSON.stringify(getStorage));
            //change header image
            document.getElementById("userImage").src = this.baseUrl+this.updateForm.user_image;

            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            //               router.push("/admin/profile");
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
