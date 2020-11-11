<template>
  <!-- all jobs -->
  <table
    id="repeating-table"
    class="table table-striped table-bordered table-main"
  >
    <thead>
      <tr>
        <th>#</th>
        <th>Job Summary</th>
        <th>Customer / Manager / Farm Location</th>
        <th>Payment Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(job, index) in alljobs">
        <td>{{ index + 1 }}</td>
        <td>
          <span class="basic-info">{{ job.start_date | formatDateLic }}</span>
          <span class="basic-big">#JOB100{{ job.id }}</span>
          <span class="basic-grey"
            >${{ job.job_amount ? job.job_amount : 0 }}</span
          >
          <span class="basic-grey">{{
            job.service ? job.service.service_name : ""
          }}</span>
        </td>
        <td>
          <span class="basic-big">{{
            job.customer ? job.customer.first_name : ""
          }}</span>
          <span class="basic-grey"
            >{{ job.manager ? job.manager.first_name : "" }} ({{
              job.manager ? job.manager.email : ""
            }})</span
          >
          <span class="basic-grey" v-if="job.manager"
            >{{ job.manager.address }} {{ job.manager.city }}
            {{ job.manager.state }} {{ job.manager.country }}
            {{ job.manager.zip_code }}</span
          >
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
            >View chat</router-link
          >
          <router-link
            v-if="!isAdmin"
            :to="'/manager/jobs/chat/' + job.id"
            class="nav-item nav-link"
            >View chat</router-link
          >
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
import { router } from "../../../../_helpers/router";
import { jobService } from "../../../../_services/job.service";
import { environment } from "../../../../config/test.env";
import { PlusCircleIcon, SearchIcon } from "vue-feather-icons";
import { authenticationService } from "../../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon,
    SearchIcon,
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
      jobService.joblist().then((response) => {
        //handle response
        if (response.status) {
          this.alljobs = response.data.repeatingJobs;
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
            oLanguage: {
              sSearch: "",
              sEmptyTable: "No job till now.",
              infoEmpty: "No job found.",
            },
            drawCallback: function (settings) {
              $("#repeating-table_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
              );
              $("#repeating-table_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
              );
            },
          });
          $("#repeating-table_filter").append($("#search-input-icon2").html());
          $("#repeating-table_filter input").attr(
            "placeholder",
            "Search Jobs by Job ID / Customer"
          );
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
