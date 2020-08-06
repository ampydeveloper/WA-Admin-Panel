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
        <v-col cols="6" md="6">
<div class="login_box">
          <v-form ref="form" v-model="valid" lazy-validation class="slide-right">
            <v-col cols="12" md="12">
              <v-text-field v-model="firstname" :rules="FnameRules" label="First name" required></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-text-field v-model="lastname" :rules="LnameRules" label="Last name" required></v-text-field>
            </v-col>

            <v-col cols="12" md="12">
              <v-text-field
                v-model="email"
                :rules="emailRules"
                name="email"
                label="E-mail"
                required
              ></v-text-field>
            </v-col>
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

	    <v-radio-group v-model="registerForm.role_id" :mandatory="false">
	      <v-radio label="As Customer" value="4"></v-radio>
	      <v-radio label="As Haulers" value="6"></v-radio>
	    </v-radio-group>

            <v-btn color="success" class="mr-4" @click="validate">Submit</v-btn>

            <v-col cols="12" sm="12">
              You have an account already?<router-link to="/login">Login</router-link>
            </v-col>

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
import { router } from "../../_helpers/router";
import { authenticationService } from "../../_services/authentication.service";
export default {
  data: () => ({
    loading: false,
    registerForm: {
      first_name: "",
      last_name: "",
      email: "",
      role_id: "",
      password: ""
    },
    valid: true,
    show1: false,
    show2: false,
    firstname: "",
    FnameRules: [v => !!v || "First name is required"],
    lastname: "",
    LnameRules: [v => !!v || "Last name is required"],
    email: "",
    emailRules: [
      v => !!v || "E-mail is required",
      v => /.+@.+/.test(v) || "E-mail must be valid"
    ],
    password: "",
    confirm_password: "",
    rules: {
      required: value => !!value || "Password is equired.",
      min: v => v.length >= 8 || "Password Min 8 characters"
    }
  }),
  computed: {
    passwordConfirmationRule() {
      return () =>
        this.password === this.confirm_password || "Password must match";
    }
  },
  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        this.loading = true;
        this.registerForm.first_name = this.firstname;
        this.registerForm.last_name = this.lastname;
        this.registerForm.email = this.email;
        this.registerForm.password = this.password;
        this.registerForm.password_confirmation = this.confirm_password;
        authenticationService.register(this.registerForm).then(response => {
          //handle response
          this.loading = false;
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
/* #login_bg > img {
  max-width: 100%;
} */
#login_bg {
  background-image: url("/images/login-bg4.jpg");
  background-position: 50%;
  background-repeat: no-repeat;
  background-size: cover;
}
.login_form .row {
  background: #fff;
  box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
}
.login_form .img_bg img {
  max-width: 100%;
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
.v-application .primary--text {
  color: #5c8545 !important;
}
@media only screen and (max-width: 992px) {
  .img_bg_outside {
    display: none;
  }
}
</style>
