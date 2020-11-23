<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Update FAQ
            <span class="right-bor"></span>
          </h4>
        </li>
        <li>
          <router-link to="/admin/dashboard" class="home_svg">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-home h-5 w-5 mb-1 stroke-current text-primary"
            >
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16px"
                height="16px"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-chevrons-right w-4 h-4"
              >
                <polyline points="13 17 18 12 13 7" />
                <polyline points="6 17 11 12 6 7" />
              </svg>
            </span>
          </router-link>
        </li>
        <li>
          <router-link to="/admin/faq">
            FAQ
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16px"
                height="16px"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-chevrons-right w-4 h-4"
              >
                <polyline points="13 17 18 12 13 7" />
                <polyline points="6 17 11 12 6 7" />
              </svg>
            </span>
          </router-link>
        </li>
        <li>Update</li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="12">
            <v-form
              ref="form"
              v-model="valid"
              class="v-form custom_form_field divide-50"
              lazy-validation
              id="form_field"
            >
              <input type="hidden" name="faq_id" value="" />
              <v-row>
                <v-col cols="12" md="12" class="pl-0 manager-cols">
               <div class="custom-col row">
                    <v-col sm="2" class="label-align pt-0">
                      <label>Question</label>
                    </v-col>
                    <v-col sm="4" class="pt-0 pb-0">
                      <v-text-field
                        v-model="addForm.question"
                        label="Enter Question"
                        required
                        :rules="[(v) => !!v || 'FAQ Question is required.']"
                      ></v-text-field>
                    </v-col>
                  </div>
                  <div class="custom-col row">
                    <v-col sm="2" class="label-align pt-0">
                      <label>Answer</label>
                    </v-col>
                    <v-col sm="4" class="pt-0 pb-0">
                      <v-textarea
                        v-model="addForm.answer"
                        label="Enter Answer"
                        required
                        :rules="[(v) => !!v || 'Answer is required.']"
                      ></v-textarea>
                    </v-col>
                  </div>
                </v-col>
                <v-col class="pt-0 pb-0" cols="12" md="12">
                  <div class="p-0 float-right">
                    <v-btn
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      type="submit"
                      class="custom-save-btn"
                      @click="update"
                      id="submit_btn"
                      >Update</v-btn
                    >
                    <router-link to="/admin/faq" class="btn-custom-danger"
                      >Cancel</router-link
                    >
                  </div>
                </v-col>
              </v-row>
            </v-form>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { faqService } from "../../../_services/faq.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    VueGoogleAutocomplete,
  },
  data() {
    return {
      loading: false,
      valid: true,
      apiUrl: environment.apiUrl,
      imgUrl: environment.imgUrl,
      uberMapApiUrl: environment.uberMapApiUrl,
      uberMapToken: environment.uberMapToken,
      cross: false,
      addForm: {
        faq_id: "",
        question: "",
        answer: "",
        image: null,
      },
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
  mounted: function () {
    faqService.getFaq(this.$route.params.id).then((response) => {
      //handle response
      if (response.status) {
        this.addForm = {
          faq_id: response.data.id,
          question: response.data.question,
          answer: response.data.answer,
        };
      } else {
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
  methods: {
    update() {
      if (this.$refs.form.validate()) {
        //start loading
        this.loading = true;
        faqService
          .edit(this.addForm, this.$route.params.id)
          .then((response) => {
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
              const currentUser = authenticationService.currentUserValue;
              if (currentUser.data.user.role_id == 1) {
                router.push("/admin/faq");
              } else {
                router.push("/manager/faq");
              }
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
  },
};
</script>