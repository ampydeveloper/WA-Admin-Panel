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
                    <h2>Reset Password</h2>
                    <p>Please enter your new password.</p>
                </div>
              
              <v-form 
                ref="form" 
                v-model="valid" 
                lazy-validation 
                class="slide-right"
                autocomplete="off"
                >
                <div class="custom_input">
                <lock-icon size="1.5x" class="custom-class icons_custom"></lock-icon>
                
                <v-text-field
                  v-model="password"
                  :rules="[rules.required, rules.min]"
                  :type="show1 ? 'text' : 'password'"
                  name="password"
                  hint="At least 8 characters"
                  placeholder="Enter password"
                  autocomplete="nope"
                ></v-text-field>
              </div>
              
              <div class="custom_input">
                <lock-icon size="1.5x" class="custom-class icons_custom"></lock-icon>
               

                <v-text-field
                  v-model="confirm_password"
                  :rules="[rules.required, rules.min]"
                  :type="show2 ? 'text' : 'password'"
                  name="confirm_password"
                  hint="At least 8 characters"
                  placeholder="Confirmed password"
                  autocomplete="nope"
                ></v-text-field>
              </div>

                <div class="forget forget-login">
                <v-col cols="12" class="login-btn-div">
                  <div class="btn_grp">
                    <v-btn color="success" class="mr-4 login_btn" @click="validate">Update</v-btn>
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
import { LockIcon } from "vue-feather-icons";
import { router } from "../_helpers/router";
import { authenticationService } from "../_services/authentication.service";
export default {
    components: {
    LockIcon
  },
  data: () => ({
    show1: false,
    show2: false,
    password: "",
    confirm_password: "",
    valid: true,
    rules: {
      required: value => !!value || "Password is equired.",
      min: v => v.length >= 8 || "Password Min 8 characters"
    }
  }),
  computed: {
    passwordConfirmationRule() {
      return () =>
        this.password === this.confirm_password || "Password must match";
    },
  },
  methods: {
    validate() {
	console.log(this.$route.params);
      const currentUser = authenticationService.currentUserValue;
      if (this.$refs.form.validate()) {
        authenticationService
          .changePassword({
            password: this.password,
            password_confirmation: this.confirm_password
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
              router.push("/login");
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
