<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col cols="6" md="6"></v-col>
        <v-col cols="6" md="6">
          <v-form ref="form" v-model="valid" lazy-validation>
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
            <v-btn color="success" class="mr-4" @click="validate">Submit</v-btn>

            <v-col cols="12" sm="12">
              You have an account already?<router-link to="/login">Login</router-link>
            </v-col>

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
  data: () => ({
    registerForm: {
      first_name: "",
      last_name: "",
      email: "",
      role_id: 4,
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
        // this.submitted = true;
        // this.loading = true;

        this.registerForm.first_name = this.firstname;
        this.registerForm.last_name = this.lastname;
        this.registerForm.email = this.email;
        this.registerForm.password = this.password;
        this.registerForm.password_confirmation = this.confirm_password;
        this.registerForm.user_image = "";
        authenticationService.register(this.registerForm).then(response => {
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
