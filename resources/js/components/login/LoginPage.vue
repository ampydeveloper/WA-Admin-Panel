<template class="bg_login_img">
  <v-app id="login_bg">
    <div class="login_form">
      <v-container>
        <v-row>
          <v-col cols="6" md="6" class="img_bg_outside">
            <div class="img_bg">
              <img src="images/login_img.png" />
            </div>
          </v-col>
          <v-col cols="12" md="6">
            <div class="login_box">
              <div class="login_txt">
                <h2>Login</h2>
                <p>Welcome back, please login to your account.</p>
              </div>
              <v-form ref="form" v-model="valid" lazy-validation class="slide-right" autocomplete="off">
                <label for="email">E-mail</label>
                <div class="custom_input">
                  <user-icon size="1.5x" class="custom-class icons_custom"></user-icon>
                  <v-text-field
                    v-model="email"
                    :rules="emailRules"
                    name="email"
                    placeholder="Enter email"
                    id="email"
                    autocomplete="off"
                  ></v-text-field>
                </div>
                <label for>Password</label>
                <div class="custom_input">
                  <lock-icon size="1.5x" class="custom-class icons_custom"></lock-icon>
                  <v-text-field
                    v-model="password"
                    :rules="[rules.required]"
                    :type="show1 ? 'text' : 'password'"
                    name="password"
                    placeholder="Enter password"
		    autocomplete="nope"
                  ></v-text-field>
                </div>
                <div class="forget">
                  <v-checkbox v-model="readonly" class="mx-2 custom_checkbox" label="Remember me"></v-checkbox>
                  <div class="forget_password">
                    <router-link to="/forgot-password">Forgot Password?</router-link>
                  </div>
                </div>
                <div class="btn_grp">
                  <v-btn class="login_btn" @click="onSubmit">Login</v-btn>
                  <div class="social_btn">
                    <button type="button" @click="AuthProvider('google')">Google</button>
                    <button type="button" @click="AuthProvider('facebook')">Facebook</button>
                  </div>
                </div>
                <v-col>
                  <div class="sign_up">
                    Don’t have an account?
                    <router-link to="/register">Sign Up</router-link>
                  </div>
                </v-col>

                <!-- <button type="button" @click="AuthProvider('facebook')">Facebook</button> -->
              </v-form>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { UserIcon, LockIcon } from "vue-feather-icons";
import { router } from "../../_helpers/router";
import { authenticationService } from "../../_services/authentication.service";

export default {
  name: "login",
  components: {
    UserIcon,
    LockIcon
  },
  data() {
    return {
      valid: true,
      show1: false,
      email: "",
      readonly: "",
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+/.test(v) || "E-mail must be valid"
      ],
      password: "",
      rules: {
        required: value => !!value || "Password is equired.",
        min: v => v.length >= 8 || "Password Min 8 characters"
      },
      submitted: false,
      loading: false,
      returnUrl: ""
    };
  },
  created() {
    // redirect to home if already logged in
    if (authenticationService.currentUserValue) {
      return router.push("/");
    }
    // get return url from route parameters or default to '/'
    this.returnUrl = this.$route.query.returnUrl || "/";
  },
  methods: {
    onSubmit() {
      if (this.$refs.form.validate()) {
        this.submitted = true;

        // stop here if form is invalid

        this.loading = true;
        authenticationService.login(this.email, this.password).then(
          response => {
            if (response === "Home") {
              var paymentUrl = localStorage.getItem("payment");
              if (paymentUrl != "undefined") {
                var r = paymentUrl.indexOf("payment");
                if (r) {
                  router.push(paymentUrl);
                } else {
                  this.$router.push({ name: response }).catch(error => {});
                }
              } else {
                this.$router.push({ name: response }).catch(error => {});
              }
            } else {
              router.push(response);
            }
          },
          error => {
            // Can accept an Object of options
            this.$toast.open({
              message: error,
              type: "error",
              position: "top-right"
              // all other options may go here
            });
            this.loading = false;
          }
        );
      }
    },

    AuthProvider(provider) {
      var self = this;

      this.$auth
        .authenticate(provider)
        .then(response => {
          self.SocialLogin(provider, response);
        })
        .catch(err => {
          console.log({ err: err });
        });
    },

    SocialLogin(provider, response) {
      this.$http
        .post("/sociallogin/" + provider, response)
        .then(response => {
          if (response.status) {
            this.$toast.open({
              message: "Logged In Successfully.",
              type: "success",
              position: "top-right"
              // all other options may go here
            });

            //store into local storage
            localStorage.setItem("currentUser", JSON.stringify(response.data));
            //redirect now
            this.$router.go("/home");
          }
        })
        .catch(err => {
          this.$toast.open({
            message: "Internal server error!",
            type: "error",
            position: "top-right"
            // all other options may go here
          });
        });
    }
  }
};
</script>
<style>
@keyframes slideInFromLeft {
  0% {
    transform: translateX(-10%);
  }
  100% {
    transform: translateX(0);
  }
}

.slide-right {
	animation: 1s ease-out 0s 1 slideInFromLeft;
}

#login_bg {
  background-image: url("/images/login-bg4.jpg");
  background-position: 50%;
  background-repeat: no-repeat;
  background-size: cover;
}
.login_form {
  max-width: 1000px;
  width: 100%;
  margin: auto;
  padding: 20px;
  /* position: absolute;
  left: 0;
  right: 0;
  top: calc(50% - 265px); */
}
.login_txt {
  padding-bottom: 26px;
}
.login_form .row {
  background: #fff;
  box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
}
.login_form .img_bg img {
  max-width: 100%;
}
.login_txt h2 {
  color: #2c2c2c;
  font-size: 22px;
  font-weight: 500;
  margin-bottom: 10px;
}
.login_txt p {
  color: #626262;
  font-size: 16px;
  font-weight: 400;
  margin-bottom: 10px;
}
.sign_up {
  color: #2c2c2c;
  font-size: 18px;
  font-weight: 500;
  text-align: center;
}
.btn_grp {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0px;
  flex-direction: row-reverse;
}
#login_bg .login_form .btn_grp button.login_btn {
  background: #5c8546;
  color: #fff;
  font-size: 13px;
  text-transform: capitalize;
  font-weight: 400;
  padding: 10px 30px;
  border-radius: 7px;
  outline: none;
}
.social_btn button {
  margin-right: 20px;
}
.img_bg_outside {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #eff2f7;
}
.login_box {
  padding: 1rem;
}
.v-text-field {
  padding-top: 0px;
  margin-top: 0px;
}
.v-input input {
  max-height: 94px;
  padding: 13px;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 6px;
  padding-left: 48px !important;
}
.v-application .error--text {
  border: none;
}
.v-text-field.v-input--has-state > .v-input__control > .v-input__slot:before {
  border: none;
}
.v-text-field > .v-input__control > .v-input__slot:after,
.v-text-field > .v-input__control > .v-input__slot:before {
  content: none;
}
.forget {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.forget .custom_checkbox {
  margin: 0;
}
.forget .custom_checkbox label {
  top: 4px;
}
.forget .v-application .primary--text {
  color: #5c8545;
}
.custom_input {
  position: relative;
}
.custom_input .icons_custom {
  position: absolute;
  top: 12px;
  left: 15px;
}
.custom_input .icons_custom {
  color: rgba(0, 0, 0, 0.4);
}
@media only screen and (max-width: 992px) {
  .img_bg_outside {
    display: none;
  }
}
</style>
