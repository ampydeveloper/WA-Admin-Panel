<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-col sm="12" cols="12">
          <table
            id="cs-payment-table"
            class="table table-striped table-bordered table-main dataTable"
          >
            <thead>
              <tr>
                <th class="text-left">#</th>
                <th class="text-left">Card</th>
                <th class="text-left">Expiry</th>
                <th class="text-left">Status</th>
                <th class="text-left">Primary</th>
                <th class="text-left">Created</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="cards">
                <tr v-for="(card, index) in cards">
                  <td>{{index+1}}</td>
                  <td>************{{card.card_number}}</td>
                  <td>{{card.card_exp_month}}/{{card.card_exp_year}}</td>
                  <td v-if="card.card_status">Active</td>
                  <td v-if="!card.card_status">Deactivate</td>
                  <td v-if="card.card_primary">Primary</td>
                  <td v-if="!card.card_primary">Secondary</td>
                  <td>{{card.created_at | formatDate}}</td>
                </tr>
              </template>
              <tr v-if="!cards">
                <td colspan="6">No payment till now.</td>
              </tr>
            </tbody>
          </table>
        </v-col>
      </v-row>
    </v-container>
    <span id="table-chevron-left" class="d-none">
      <chevron-left-icon size="1.5x" class="custom-class"></chevron-left-icon>
    </span>
    <span id="table-chevron-right" class="d-none">
      <chevron-right-icon size="1.5x" class="custom-class"></chevron-right-icon>
    </span>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import {
  PlusCircleIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from "vue-feather-icons";
import { customerService } from "../../../../_services/customer.service";
import { router } from "../../../../_helpers/router";
export default {
  components: {
    PlusCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
  },
  data() {
    return {
      prefix: ["One", "Two", "Three", "Four"],
      cards: "",
    };
  },
  mounted: function () {
    customerService.getCustomerCard(this.$route.params.id).then((response) => {
      //handle response
      if (response.status) {
        this.cards = response.data;
      } else {
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
        if (!$.fn.dataTable.isDataTable("#cs-payment-table")) {
          $("#cs-payment-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3, -4],
              },
            ],
            oLanguage: { sSearch: "" },
            drawCallback: function (settings) {
              $("#cs-payment-table_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
              );
              $("#cs-payment-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });
          $("#cs-payment-table_filter input").attr(
            "placeholder",
            "Search Payments"
          );
          $("#cs-payment-table_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $("#cs-payment-table_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
          $("#cs-payment-table").css({ opacity: 1 });
        }
      });
    }, 1000);
  },
};
</script>