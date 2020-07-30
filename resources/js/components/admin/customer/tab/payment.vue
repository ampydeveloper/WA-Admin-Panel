<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col sm="12" cols="12">
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Sno</th>
                  <th class="text-left">Card No</th>
                  <th class="text-left">Expiry</th>
                  <th class="text-left">Status</th>
                  <th class="text-left">Primary</th>
                  <th class="text-left">Add On</th>
                </tr>
              </thead>
              <tbody>
               <template v-if="cards">
                <tr  v-for="(card, index) in cards">
                  <td>{{index+1}}</td>
                  <td>XXXXXXX{{card.card_number}}</td>
                  <td>{{card.card_exp_month}}/{{card.card_exp_year}}</td>
                  <td v-if="card.card_status">Active</td>
                  <td v-if="!card.card_status">Deactivate</td>
                  <td v-if="card.card_primary">Primary</td>
                  <td v-if="!card.card_primary">Secondary</td>
                  <td>{{card.created_at | formatDate}}</td>
                </tr>
                </template>
                <tr v-if="!cards">No Card Found</tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>

import { required } from "vuelidate/lib/validators";
import { PlusCircleIcon } from "vue-feather-icons";
import { customerService } from "../../../../_services/customer.service";
import { router } from "../../../../_helpers/router";
export default {
  components: {
    PlusCircleIcon
  },
  data() {
    return {
      prefix: ["One", "Two", "Three", "Four"],
      cards:'',
    };
  },
 mounted: function() {
    customerService.getCustomerCard(this.$route.params.id).then(response => {
      //handle response
      if (response.status) {
        this.cards = response.data;
      } else {
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
    });
  },
};
</script>
