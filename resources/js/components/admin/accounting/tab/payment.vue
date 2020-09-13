<template>
  <v-container id="dashboard" fluid tag="section" class="pl-0">
   
        <table id="payment-table" class="table table-striped table-bordered table-main">
          <thead>
            <tr>
              <th>Payment Date</th>
              <th>Customer</th>
              <th>Invoice Number</th>
              <th>Payment</th>
              <th>Payment Type</th>
              <th>In QuickBook</th>
              <th>View</th>
              <th>Get Payment</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="payment in paymentJobs">
              <td>{{payment.jobpayment.updated_at | formatDate}}</td>
              <td>
                <router-link
                  v-if="isAdmin"
                  :to="'/admin/customer/details/'+payment.customer.id"
                  class="nav-item nav-link"
                >{{payment.customer.first_name}}</router-link>
                <router-link
                  v-if="!isAdmin"
                  :to="'/manager/customer/details/'+payment.customer.id"
                  class="nav-item nav-link"
                >{{payment.customer.first_name}}</router-link>
              </td>
              <td>{{payment.invoice_number}}</td>
              <td>${{payment.job_amount}}</td>
              <td>{{payment.jobpayment.payment_mode}}</td>
              <td>
                <template v-if="!payment.quick_book">
                  <span class="badges-item">Not Sync</span>
                </template>
                <template v-if="payment.quick_book">
                  <span class="badges-item">Sync</span>
                </template>
              </td>
              <td>View Details</td>
              <td>Get Payment</td>
            </tr>
          </tbody>
        </table>
    
  </v-container>
</template>

<script>
import { router } from "../../../../_helpers/router";
import { environment } from "../../../../config/test.env";
import { PlusCircleIcon } from "vue-feather-icons";
import { accountingService } from "../../../../_services/accounting.service";
import { authenticationService } from "../../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon,
  },
  data() {
    return {
      paymentJobs: "",
      isAdmin: true,
    };
  },
  mounted: function () {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    this.invoiceList();
  },
  methods: {
    invoiceList() {
      accountingService.jobPayments().then((response) => {
        //handle response
        if (response.status) {
          this.paymentJobs = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
  },
  updated() {
    setTimeout(function () {
      $(document).ready(function () {
         if (!$.fn.dataTable.isDataTable("#payment-table")) {
         $("#payment-table").DataTable({
          aoColumnDefs: [
            {
              bSortable: false,
              aTargets: [-1, -2, -3, -5, -6],
            },
          ],
          oLanguage: { sSearch: "" },
          drawCallback: function (settings) {
            $(
              "#payment-table_paginate .paginate_button.previous"
            ).html($("#table-chevron-left").html());
            $(
              "#payment-table_paginate .paginate_button.next"
            ).html($("#table-chevron-right").html());
          },
        });
        $("#payment-table_filter input").attr(
          "placeholder",
          "Search Payments"
        );
        $(
          "#payment-table_paginate .paginate_button.previous"
        ).html($("#table-chevron-left").html());
        $("#payment-table_paginate .paginate_button.next").html(
          $("#table-chevron-right").html()
        );
         }
         $(".table-main").css({'opacity':1});
      });
    }, 1000);
  },
};
</script>
