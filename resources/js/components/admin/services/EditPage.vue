<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="12" md="12">
          <h2>Edit Service</h2>
        </v-col>
        <v-col cols="12" md="12">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-col cols="12" md="12">
              <div
                class="v-avatar v-list-item__avatar"
                style="height: 40px; min-width: 40px; width: 40px;"
              >
                <img :src="'../../../'+editForm.service_image" alt="John" />
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
                v-model="editForm.service_name"
                :rules="nameRules"
                label="Service Name"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-text-field
                type="number"
                max="100"
                min="0"
                v-model="editForm.price"
                :rules="priceRules"
                label="Service Price"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-textarea
                clearable
                clear-icon="cancel"
                v-model="editForm.description"
                :rules="descRules"
                label="Description"
                required
              ></v-textarea>
            </v-col>
            <v-btn color="success" class="mr-4" @click="update">Update</v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { jobService } from "../../../_services/job.service";
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
      editForm: {
        id: "",
        service_name: "",
        price: "",
        description: "",
        service_image: ""
      },
      nameRules: [v => !!v || "Service name is required"],
      priceRules: [v => !!v || "Service price is invalid/required"],
      descRules: [v => !!v || "Service description is required"],
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
  mounted: function() {
    jobService.getService(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        this.editForm.id = response.data.id;
        this.editForm.service_name = response.data.service_name;
        this.editForm.price = response.data.price;
        this.editForm.description = response.data.description;
        this.editForm.service_image = response.data.service_image;
      } else {
        router.push("/admin/services");
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
    },
    handleProcessFile: function(error, file) {
      this.editForm.service_image = file.serverId;
    },
    update() {
      if (this.$refs.form.validate()) {
        jobService.edit(this.editForm).then(response => {
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
            //redirect to login
            router.push("/admin/services");
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
