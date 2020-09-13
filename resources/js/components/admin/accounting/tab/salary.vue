<template>
  <v-container id="dashboard" fluid tag="section" class="pl-0">
    <table id="salary-table" class="table table-striped table-bordered table-main">
      <thead>
        <tr>
          <!-- <th>Image</th> -->
          <th>Employee</th>
          <th>Contact</th>
          <th>Designation</th>
          <th>Month</th>
          <th>Year</th>
          <th>Salary</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(salary, index ) in salaryJobs">
          <!-- <td>{{index+1}}</td> -->
          <td>
            <router-link
              v-if="isAdmin"
              :to="'/admin/truckdriver/edit/' + salary.user.id"
              class="nav-item nav-link"
            >{{salary.user.first_name}}</router-link>
            <router-link
              v-if="!isAdmin"
              :to="'/manager/truckdriver/edit/' + salary.user.id"
              class="nav-item nav-link"
            >{{salary.user.first_name}}</router-link>
          </td>
          <td>{{salary.user.phone}}</td>
          <td>
            <template v-if="salary.user.driver.driver_type">Truck Driver</template>
            <template v-if="!salary.user.driver.driver_type">Skidsteer Driver</template>
          </td>
          <td>{{salary.month}}</td>
          <td>{{salary.year}}</td>
          <td>${{salary.salary}}</td>
          <td>
            <router-link
              v-if="isAdmin"
              :to="'/admin/accounting/details/' + salary.user.id"
              class="nav-item nav-link"
            >View Details</router-link>
            <router-link
              v-if="!isAdmin"
              :to="'/manager/accounting/details/' + salary.user.id"
              class="nav-item nav-link"
            >View Details</router-link>
          </td>
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
      salaryJobs: "",
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
      accountingService.jobSalary().then((response) => {
        //handle response
        if (response.status) {
          this.salaryJobs = response.data;
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
        if (!$.fn.dataTable.isDataTable("#salary-table")) {
          $("#salary-table").DataTable({
            aoColumnDefs: [
              {
                bSortable: false,
                aTargets: [-1, -5, -6],
              },
            ],
            oLanguage: { sSearch: "" },
            drawCallback: function (settings) {
              $("#salary-table_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
              );
              $("#salary-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });
          $("#salary-table_filter input").attr(
            "placeholder",
            "Search Salaries"
          );
          $("#salary-table_paginate .paginate_button.previous").html(
            $("#table-chevron-left").html()
          );
          $("#salary-table_paginate .paginate_button.next").html(
            $("#table-chevron-right").html()
          );
        }
        $(".table-main").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>
