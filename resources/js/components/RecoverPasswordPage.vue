<template class="bg_login_img">
  <v-app id="login_bg">
    <div class="login_form">
      <v-container>
        <v-row>
          <v-col cols="6" md="6" class="img_bg_outside">
            <div class="img_bg">
              <img :src="'../images/login_img.png'" />
            </div>
          </v-col>
          <v-col cols="6" md="6">
           <div class="login_box">
              <div class="login_txt">
		<h2>Reset Password</h2>
                <p>Please enter your new password.</p>
              </div>
            <v-form ref="form" v-model="valid" lazy-validation class="slide-right">
              <v-col cols="12" sm="12">
                <v-text-field
                  v-model="password"
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :rules="[rules.required, rules.min]"
                  :type="show1 ? 'text' : 'password'"
                  name="password"
                  label="Password"
                  hint="At least 8 characters"
                  counter
                  @click:append="show1 = !show1"
                ></v-text-field>
                <v-col cols="12" sm="12"></v-col>
                <v-text-field
                  v-model="confirm_password"
                  :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                  :rules="[rules.required, rules.min, passwordConfirmationRule]"
                  :type="show2 ? 'text' : 'password'"
                  name="confirm_password"
                  label="Confirm Password"
                  hint="At least 8 characters"
                  counter
                  @click:append="show2 = !show2"
                ></v-text-field>
              </v-col>
                <router-link to="/login" style="margin: 0px 15px;" class="back-btn">Back To Login</router-link>
              <v-btn color="success" class="mr-4 recover_btn" @click="validate">Reset</v-btn>
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
import { router } from "../_helpers/router";
import { authenticationService } from "../_services/authentication.service";
export default {
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
          .recoverPassword({
            hash_code: this.$route.params.hash_code,
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

.recover_btn {
  margin: 0px 9px 0px 0px;
  float: right;
  background: #5c8546 !important;
  color: #fff;
  font-size: 13px;
  text-transform: capitalize;
  font-weight: 400;
  padding: 10px 30px;
  border-radius: 7px;
  outline: none;
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
  position: absolute;
  left: 0;
  right: 0;
  top: calc(50% - 265px);
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
  color: #2c2c2c;
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
.v-application .primary--text {
  color: #5c8545 !important;
}
a.back-btn {
    padding: 10px;
    border: 1px solid rgba(0,0,0,.87);
    text-decoration: none;
    color: rgba(0,0,0,.87) !important;
}
@media only screen and (max-width: 992px) {
  .img_bg_outside {
    display: none;
  }
}
</style>
