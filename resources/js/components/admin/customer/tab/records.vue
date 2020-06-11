<template>
  <v-app>
    <v-container>
      <v-row>
        <h4 class="main-title">Customer Section</h4>
        <v-col cols="12" md="12">
          <v-form ref="form" class="form-layout">
          <div class="single-row">
            <v-col sm="3">
              <v-select
                :items="prefix"
                label="Prefix"
                outlined
              ></v-select>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Name"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Email"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Phone"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Address"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="City"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Province"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Zip Code"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="12">
              <v-switch
                :label="`Is Active`"
              ></v-switch>
            </v-col>
          </div>
          <div class="single-row">
            <h4 class="sub-title">Farm Section</h4>
            <v-col sm="3">
              <v-text-field
              label="Address"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="App/State/Unit"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="City"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Province"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-text-field
              label="Zip Code"
              outlined
              >
              </v-text-field>
            </v-col>
            <v-col sm="3">
              <v-switch
                :label="`Is Active`"
              ></v-switch>
            </v-col>
          </div>
          <div class="single-row">
            <h4 class="sub-title">Manager Section</h4>

          </div>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
   </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import {
  PlusCircleIcon
} from "vue-feather-icons";
import { router } from "../../../_helpers/router";
export default {
  components: {
    PlusCircleIcon
  },
  data() {
    return {
      prefix: ['One', 'Two', 'Three', 'Four'],
    }
  },
};

</script>
