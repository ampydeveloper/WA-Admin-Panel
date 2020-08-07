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
              <h2>Login</h2>
              <p>Welcome back, please login to your account.</p>
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
                <v-col cols="12" md="6" class="remember-me-div">
                  <v-checkbox
                    v-model="readonly"
                    class="mx-2 custom_checkbox remember-me"
                    label="Remember me"
                  ></v-checkbox>
                </v-col>
                <v-col cols="12" md="6" class="forget-password-div">
                  <div class="forget_password">
                    <router-link to="/forgot-password">Forgot Password?</router-link>
                  </div>
                </v-col>
              </div>
              <div class="forget forget-login">
                <v-col cols="12" class="login-btn-div">
                  <div class="btn_grp">
                    <v-btn class="login_btn" @click="onSubmit">Login</v-btn>
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
import { UserIcon, LockIcon } from "vue-feather-icons";
import { router } from "../../_helpers/router";
import { authenticationService } from "../../_services/authentication.service";

export default {
  name: "login",
  components: {
    UserIcon,
    LockIcon,
  },
  data() {
    return {
      valid: true,
      show1: false,
      email: "",
      readonly: "",
      emailRules: [
        (v) => !!v || "Email is required.",
        (v) => /.+@.+/.test(v) || "Email must be valid.",
      ],
      password: "",
      rules: {
        required: (value) => !!value || "Password is required.",
        min: (v) => v.length >= 8 || "Password must be minimum 8 characters.",
      },
      submitted: false,
      loading: false,
      returnUrl: "",
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
          (response) => {
            if (response === "Home") {
              var paymentUrl = localStorage.getItem("payment");
              if (paymentUrl != "undefined") {
                var r = paymentUrl.indexOf("payment");
                if (r) {
                  router.push(paymentUrl);
                } else {
                  this.$router.push({ name: response }).catch((error) => {});
                }
              } else {
                this.$router.push({ name: response }).catch((error) => {});
              }
            } else {
              router.push(response);
            }
          },
          (error) => {
            // Can accept an Object of options
            this.$toast.open({
              message: error,
              type: "error",
              position: "top-right",
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
        .then((response) => {
          self.SocialLogin(provider, response);
        })
        .catch((err) => {
          console.log({ err: err });
        });
    },

    SocialLogin(provider, response) {
      this.$http
        .post("/sociallogin/" + provider, response)
        .then((response) => {
          if (response.status) {
            this.$toast.open({
              message: "Logged In Successfully.",
              type: "success",
              position: "top-right",
              // all other options may go here
            });

            //store into local storage
            localStorage.setItem("currentUser", JSON.stringify(response.data));
            //redirect now
            this.$router.go("/home");
          }
        })
        .catch((err) => {
          this.$toast.open({
            message: "Internal server error!",
            type: "error",
            position: "top-right",
            // all other options may go here
          });
        });
    },
  },
};
</script>
<style>
.v-application {
  font-family: "Montserrat", sans-serif;
}
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

.slide-right label {
  font-size: 15px;
  color: #626262;
  font-weight: 300;
}

#login_bg {
  background-image: url("/images/login-bg4.jpg");
  background-position: 50%;
  background-repeat: no-repeat;
  background-size: cover;
}

.login_form .img_bg_outside {
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  position: relative;
  height: 100vh;
}
.login_form .img_bg {
  background-image: url("/images/loginImage.jpg");
  background-position: center left;
  background-size: cover;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
.login_form .green-overlay {
  background: rgb(17 178 118 / 0.5);
  height: 100vh;
  background-position: center left;
  background-size: cover;
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
/* .login_form .img_bg img {
  max-width: 100%;
  height: 100vh;
} */
.login_txt h2 {
  color: #2c2c2c;
  font-size: 18.48px;
  font-weight: 500;
  line-height: 1.2;
  margin-bottom: 15px;
}
.login_txt p {
  color: #626262 !important;
  font-size: 15px !important;
  font-weight: 300 !important;
  margin-bottom: 25px !important;
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
  background: #11b276 !important;
  color: #fff;
  font-size: 14px !important;
  text-transform: capitalize;
  font-weight: 300 !important;
  border-radius: 6px !important;
  outline: none;
}
.social_btn button {
  margin-right: 20px;
}
.login_box {
  padding-right: 27px;
  padding-left: 15px;
  transform: translate(0, -50%);
  position: relative;
  top: 48%;
}
.v-text-field {
  padding-top: 0px;
  margin-top: 0px;
}
.v-input input {
  max-height: 38px;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 5px;
  padding-left: 40px !important;
  font-size: 14px;
  font-weight: 300;
}
.v-application .error--text {
  border: none;
  color: #ea5455 !important;
  caret-color: #ea5455 !important;
}
.v-input__control {
  margin-bottom: 10px;
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
  align-items: baseline !important;
  margin-top: -10px;
  justify-content: space-between;
}
.forget .custom_checkbox {
  margin: 0px !important;
  padding: 0;
}
.forget .custom_checkbox label {
  top: 5px;
}
.v-application .forget .primary--text {
  color: #11b276 !important;
}
.custom_input {
  position: relative;
}
.custom_input .icons_custom {
  position: absolute;
  top: 11px;
  left: 13px;
  color: rgba(0, 0, 0, 0.4);
  font-size: 10px;
}
.login_form .remember-me-div {
  padding: 0px;
}
.login_form .remember-me-div .v-input__control {
  margin: 0;
}
.login_form .remember-me label {
  line-height: 1.5;
  letter-spacing: 0.01rem;
  font-family: Montserrat, Helvetica, Arial, sans-serif;
  padding-left: 0px;
  color: #626262;
  font-size: 15px;
  font-weight: 300;
}
.login_form .forget-password-div {
  padding: 10px 0 0 0;
}
.login_form .forget-password-div .forget_password {
  float: right;
}
.login_form .forget-password-div .forget_password a {
  color: #11b276;
  font-size: 15px;
  font-weight: 300;
}
.login_form .forget-password-div .forget_password a:hover {
  text-decoration: none;
  color: #11b276;
}
.forget.forget-login {
  margin: 0;
}
.forget .login-btn-div {
  padding: 0;
}
.forget .login-btn-div .btn_grp {
  padding: 0;
}
@media only screen and (max-width: 992px) {
  .img_bg_outside {
    display: none;
  }
  #login_bg {
    background: #fff !important;
    color: rgba(0, 0, 0, 0.87) !important;
  }
  .login_form {
    margin: 0 15px;
  }
  .login_form .row {
    background: transparent !important;
    box-shadow: none !important;
  }
  .login_form .login_box-outer {
    position: static;
  }
  .login_box {
    position: absolute;
    background: #fff;
    box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
    width: calc(100% - 30px);
    padding: 30px;
    /* transform: initial; */
  }
  .forget {
    display: inline-block !important;
    width: 100%;
  }
  .forget .v-input__slot {
    margin: 0;
  }
  .login_form .forget-password-div {
    width: 100%;
    max-width: 100%;
    padding: 0;
  }
  .login_form .forget-password-div .forget_password {
    margin-bottom: 20px;
  }
}
.img_bg_outside .back-text {
  position: absolute;
  z-index: 1;
  left: 40px;
  right: 0;
  bottom: 30px;
  font-size: 26px;
  color: #fff;
  font-weight: 300;
}
.img_bg_outside .back-text h3 {
  font-size: 70px;
  font-weight: 400;
}
</style>
