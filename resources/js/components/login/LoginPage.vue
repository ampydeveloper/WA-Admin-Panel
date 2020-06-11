<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="6" md="6"></v-col>
        <v-col cols="6" md="6">
          <v-form ref="form" v-model="valid" lazy-validation>
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
                :rules="[rules.required]"
                :type="show1 ? 'text' : 'password'"
                name="password"
                label="Password"
                counter
                @click:append="show1 = !show1"
              ></v-text-field>
            </v-col>
            <v-btn color="success" class="mr-4" @click="onSubmit">Login</v-btn>

            <button type="button" @click="AuthProvider('google')">Google</button>

            <v-col cols="12" sm="12">
              Don’t have an account?<router-link to="/register">Sign Up</router-link>
            </v-col>

            <!-- <button type="button" @click="AuthProvider('facebook')">Facebook</button> -->
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";

import { router } from "../../_helpers/router";
import { authenticationService } from "../../_services/authentication.service";

export default {
name: "login",
  data() {
    return {
      valid: true,
      show1: false,
      email: "",
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
		  if(response == 'Home'){
		      this.$router.push({name: response}).catch(error => { })
		   }else{
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
          if(response.status) {

            this.$toast.open({
              message: "Logged In Successfully.",
              type: "success",
              position: "top-right"
              // all other options may go here
            });

            //store into local storage
            localStorage.setItem("currentUser", JSON.stringify(response.data));
            //redirect now
            this.$router.go('/home');
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
