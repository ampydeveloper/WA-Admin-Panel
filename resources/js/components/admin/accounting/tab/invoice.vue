<template>
  <v-container id="dashboard" fluid tag="section" class="pl-0">
    <table id="invoice-table" class="table table-striped table-bordered table-main">
      <thead>
        <tr>
          <th>Date</th>
          <th>Customer</th>
          <th>Invoice Number</th>
          <th>Service</th>
          <th>Amount</th>
          <th>In QuickBook</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invoice in invoiceJobs">
          <td>{{invoice.created_at}}</td>
          <td>
            <router-link
              v-if="isAdmin"
              :to="'/admin/customer/details/'+invoice.id"
              class="nav-item nav-link"
            >{{invoice.customer.first_name}}</router-link>
            <router-link
              v-if="!isAdmin"
              :to="'/manager/customer/details/'+invoice.id"
              class="nav-item nav-link"
            >{{invoice.customer.first_name}}</router-link>
          </td>
          <td>
            <router-link v-if="isAdmin" :to="'/admin/jobs'" class="nav-item nav-link">{{invoice.id}}</router-link>
            <router-link
              v-if="!isAdmin"
              :to="'/manager/jobs'"
              class="nav-item nav-link"
            >{{invoice.id}}</router-link>
          </td>
          <td>
            <router-link
              v-if="isAdmin"
              :to="'/admin/service/edit/'+invoice.service.id"
              class="nav-item nav-link"
            >{{invoice.service.service_name}}</router-link>
            <router-link
              v-if="!isAdmin"
              :to="'/manager/service/edit/'+invoice.service.id"
              class="nav-item nav-link"
            >{{invoice.service.service_name}}</router-link>
          </td>
          <td>${{invoice.amount}}</td>
          <td>
            <template v-if="!invoice.quick_book">
              <span class="badges-item">Not Sync</span>
            </template>
            <template v-if="invoice.quick_book">
              <span class="badges-item">Sync</span>
            </template>
          </td>
          <td>{{invoice.customer.email}}</td>
          <td>Download</td>
        </tr>
        <tr v-if="invoiceJobs.length == 0">
                      <td colspan="7">No jobs till now.</td>
                    </tr>
      </tbody>
    </table>
    <span id="table-chevron-left" class="d-none">
      <chevron-left-icon size="1.5x" class="custom-class"></chevron-left-icon>
    </span>
    <span id="table-chevron-right" class="d-none">
      <chevron-right-icon size="1.5x" class="custom-class"></chevron-right-icon>
    </span>
  </v-container>
</template>

<script>
import { router } from "../../../../_helpers/router";
import { environment } from "../../../../config/test.env";
import {
  PlusCircleIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from "vue-feather-icons";
import { accountingService } from "../../../../_services/accounting.service";
import { authenticationService } from "../../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
  },
  data() {
    return {
      invoiceJobs: [],
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
      accountingService.jobInvoices().then((response) => {
        //handle response
        if (response.status) {
          this.invoiceJobs = response.data;
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
        if (!$.fn.dataTable.isDataTable("#invoice-table")) {
          $("#invoice-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -2, -3, -4, -6],
              },
            ],
            oLanguage: { sSearch: "" },
            drawCallback: function (settings) {
              $("#invoice-table_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
              );
              $("#invoice-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });
          $("#invoice-table_filter input").attr(
            "placeholder",
            "Search Invoices"
          );
          $("#invoice-table_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $("#invoice-table_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
        }
        $("#invoice-table").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>
