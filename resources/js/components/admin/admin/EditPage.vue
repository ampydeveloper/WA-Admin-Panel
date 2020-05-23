<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <h2>Edit Manager Profile</h2>
        </v-col>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-col cols="12" md="12">
              <div
                class="v-avatar v-list-item__avatar"
                style="height: 40px; min-width: 40px; width: 40px;"
              >
                <img :src="avatar" alt="John" />
              </div>

              <file-pond
                name="uploadImage"
                ref="pond"
                label-idle="Drop files here..."
                allow-multiple="false"
                accepted-file-types="image/jpeg, image/png"
                v-bind:server="serverOptions"
                v-bind:files="myFiles"
                v-on:updatefiles="handleFilePondUpdateFile"
                v-on:processfile="handleProcessFile"
              />
            </v-col>
            <v-col cols="12" md="12">
              <v-text-field
                v-model="addForm.first_name"
                :rules="FnameRules"
                label="First name"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="12">
              <v-text-field
                v-model="addForm.last_name"
                :rules="LnameRules"
                label="Last name"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-text-field
                v-model="addForm.email"
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
import { adminService } from "../../../_services/admin.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";

export default {
  components: {
    //      'image-component': imageVUE,
  },
  data() {
    return {
      valid: true,
      avatar: null,
      apiUrl: environment.apiUrl,
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
    adminService.getAdmin(this.$route.params.id).then(response => {
      if (response.status) {
        this.addForm.user_id = response.data.id;
        if (response.data.user_image) {
          this.addForm.user_image = response.data.user_image;
        }
        if (response.data.user_image) {
          this.avatar = '../../../'+response.data.user_image;
        } else {
          this.avatar = "/images/avatar.png";
        }
        this.addForm.first_name = response.data.first_name;
        this.addForm.last_name = response.data.last_name;
        this.addForm.email = response.data.email;
        this.addForm.phone = response.data.phone;
        this.addForm.user_image = response.data.user_image;
      } else {
        router.push("/admin/manager");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
    });
  },
  methods: {
    GetImage(e) {
      this.avatar = URL.createObjectURL(e);
      this.addForm.user_image = e;
    },
    handleProcessFile: function(error, file) {
      this.addForm.user_image = file.serverId;
    },
    handleFilePondUpdateFile(files) {
      const reader = new FileReader();
      reader.onload = e => {
        console.log(e.target.result);
      };
      this.myFiles = files.map(files => files.file);
    },

    update() {
      if (this.$refs.form.validate()) {
        adminService.edit(this.addForm, this.$route.params.id).then(response => {
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
        });
      }
    }
  }
};
</script>
