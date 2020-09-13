<template>
  <v-container id="dashboard" fluid tag="section" class="pt-0">
    <v-row>
      <v-col sm="12" cols="12">
        <!-- all jobs -->
        <table id="repeating-table" class="table table-striped table-bordered table-main">
          <thead>
            <tr>
              <th>#</th>
              <th>Job Summary</th>
              <th>Sort By</th>
              <th>Payment</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(job, index) in alljobs">
              <td>{{index+1}}</td>
              <td>
                {{job.start_date}}
                <br />
                {{job.id}}
                <br />
                ${{job.job_amount}}
                <br />
                {{job.service.service_name}}
              </td>
              <td>
                {{job.customer.first_name}}
                <br />
                {{job.manager.first_name}}
                <br />
                {{job.manager.phone}}
                <br />
                {{job.manager.email}}
                <br />
                {{job.manager.address}} {{job.manager.city}} {{job.manager.state}} {{job.manager.country}} {{job.manager.zip_code}}
              </td>
              <td>
                <template v-if="job.payment_status">
                  <span class="badges-item">Paid</span>
                </template>
                <template v-if="!job.payment_status">
                  <span class="badges-item">Unpaid</span>
                </template>
              </td>
              <td>
                <router-link
                  v-if="isAdmin"
                  :to="'/admin/jobs/chat/' + job.id"
                  class="nav-item nav-link"
                >View chat</router-link>
                <router-link
                  v-if="!isAdmin"
                  :to="'/manager/jobs/chat/' + job.id"
                  class="nav-item nav-link"
                >View chat</router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { router } from "../../../../_helpers/router";
import { jobService } from "../../../../_services/job.service";
import { environment } from "../../../../config/test.env";
import { PlusCircleIcon } from "vue-feather-icons";
import { authenticationService } from "../../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon,
  },
  data() {
    return {
      alljobs: "",
      isAdmin: true,
    };
  },
  created() {},
  mounted() {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    this.getResults();
  },
  methods: {
    getResults() {
      jobService.jobrepeating().then((response) => {
        //handle response
        if (response.status) {
          this.alljobs = response.data;
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
        if (!$.fn.dataTable.isDataTable("#repeating-table")) {
        $("#repeating-table").DataTable({
          aoColumnDefs: [
            {
              bSortable: false,
              aTargets: [-1, -2, -3],
            },
          ],
          oLanguage: { sSearch: "" },
          drawCallback: function (settings) {
            $("#repeating-table_paginate .paginate_button.previous").html(
              $("#table-chevron-left").html()
            );
            $("#repeating-table_paginate .paginate_button.next").html(
              $("#table-chevron-right").html()
            );
          },
        });
        $("#repeating-table_filter input").attr("placeholder", "Search Jobs");
        $("#repeating-table_paginate .paginate_button.previous").html(
          $("#table-chevron-left").html()
        );
        $("#repeating-table_paginate .paginate_button.next").html(
          $("#table-chevron-right").html()
        );
        }
        $("#repeating-table").css({ opacity: 1 });
      });
    }, 1000);
  },
};
</script>
