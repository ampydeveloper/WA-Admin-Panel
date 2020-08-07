<template class="bg_login_img">
  <v-app id="login_bg">
    <div class="login_form">
        <v-row>
            <v-col cols="6" md="7" class="img_bg_outside">
                <div class="green-overlay"></div>
                <div class="img_bg"></div>
                <div class="back-text">
                    <h3>Wellington</h3>
                    <h3>Agricultural Services</h3>
                    <p>Affordable solutions for smaller farms.</p>
                </div>
            </v-col>
            <v-col cols="12" md="5" class="login_box-outer">
            <div class="login_box">
                <div class="login_txt">
                    <h2>Recover your password</h2>
                    <p>Please enter your email address and we'll send you instructions on how to reset your password.</p>
                </div>
              
              <v-form 
                ref="form" 
                v-model="valid" 
                lazy-validation 
                class="slide-right"
                autocomplete="off"
                >
                <label for="email">Email</label>
                <div class="custom_input">
                <user-icon size="1.5x" class="custom-class icons_custom"></user-icon>
                <v-text-field
                    v-model="email"
                    :rules="emailRules"
                    name="email"
                    placeholder="Enter email"
                    id="email"
                    autocomplete="off"
                >
                </v-text-field>
                </div>
                <div class="forget forget-login">
                <v-col cols="12" class="login-btn-div">
                  <div class="btn_grp">
                    <v-btn class="login_btn" @click="validate">Recover Password</v-btn>
                    <v-btn @click="goToEvents()" class="login_btn">Back To Login</v-btn>
                  </div>
                </v-col>
              </div>
              </v-form>
            </div>
          </v-col>
        </v-row>
    </div>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { UserIcon } from "vue-feather-icons";
import { router } from "../_helpers/router";
import { authenticationService } from "../_services/authentication.service";
export default {
    components: {
    UserIcon,
  },
  data: () => ({
    valid: true,
    email: "",
    emailRules: [
      v => !!v || "E-mail is required",
      v => /.+@.+/.test(v) || "E-mail must be valid"
    ]
  }),
  computed: {
    passwordConfirmationRule() {
      return () =>
        this.password === this.confirm_password || "Password must match";
    }
  },
  methods: {
    goToEvents: function () {
    router.push("/login");
    },
    validate() {
      const currentUser = authenticationService.currentUserValue;
      if (this.$refs.form.validate()) {
        authenticationService
          .forgetpassword({
            email: this.email,
          })
          .then(response => {
            //handle response
            if (response.status) {
              this.$toast.open({
                message: response.message,
                type: "success",
                position: "top-right"
              });
              //redirect to login
              //router.push("/forgot-password");
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