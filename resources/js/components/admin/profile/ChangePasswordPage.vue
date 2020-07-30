<template>
    <v-app>
             <v-container>
      <v-row>
 	<h4 class="main-title text-left">Change Password</h4>
       <v-col
          cols="12"
          md="12"
          >
        <v-form
           ref="form"
           v-model="valid"
           lazy-validation
         >
       <v-col cols="12" sm="12">
          <v-col sm="2" class="label-align pt-0">
            <label>Password</label>
          </v-col>
          <v-col sm="4" class="pt-0">
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
          </v-col>
            </v-col>

            <v-col cols="12" sm="12" class="pt-0">
            <v-col sm="2" class="label-align pt-0">
              <label>Change Password</label>
            </v-col>
            <v-col sm="4" class="pt-0">
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
        </v-col>
           <v-btn color="success" class="mr-4 ml-3 custom-save-btn" @click="validate">Update</v-btn>
   
  </v-form>
       </v-col>
             </v-row>
    </v-container>
    </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { authenticationService } from "../../../_services/authentication.service";
  export default {
    data: () => ({
           show1: false,
      show2: false,
      password: '',
      confirm_password: '',
      valid: true,
      rules: {
          required: value => !!value || 'Password is equired.',
          min: v => v.length >= 8 || 'Password Min 8 characters'
      },
    }),
    computed: {
        passwordConfirmationRule() {
          return () => (this.password === this.confirm_password) || 'Password must match'
        },
    },
    methods: {
        validate () {
          if( this.$refs.form.validate() ){
            authenticationService.changePassword({password: this.password, password_confirmation:this.confirm_password}).then(response => {
              //handle response
              if(response.status) {
                  this.$toast.open({
                    message: response.message,
                    type: 'success',
                    position: 'top-right'
                  });
               //redirect to login
              } else {
                  this.$toast.open({
                    message: response.message,
                    type: 'error',
                    position: 'top-right'
                  })
              }
            });
          }
       }
    }
  }
</script>
