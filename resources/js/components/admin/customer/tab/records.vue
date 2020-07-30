<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h4 class="main-title">Customer Records</h4>
        <v-col sm="12" cols="12" class="cust-record-content">
          <v-col sm="2" class="p-0">
            <div class="single-record">
              <span class="record-timeline">Last 12 Months</span>
              <span class="record-price">${{records.monthamount}}</span>
            </div>
          </v-col>
          <v-col sm="2" class="p-0">
            <div class="single-record">
              <span class="record-timeline">Life Time</span>
              <span class="record-price">${{records.allamount}}</span>
            </div>
          </v-col>
          <v-col sm="2" class="p-0">
            <div class="single-record">
              <span class="record-timeline">Total Farms</span>
              <span class="record-price">{{records.farmrecord}}</span>
            </div>
          </v-col>
          <v-col sm="2" class="p-0">
            <div class="single-record">
              <span class="record-timeline">Total Jobs</span>
              <span class="record-price">{{records.totaljobs}}</span>
            </div>
          </v-col>
          <v-col sm="2" class="p-0">
            <div class="single-record">
              <span class="record-timeline">Member Since</span>
              <span class="record-price">{{records.memebersince | formatDate}}</span>
            </div>
          </v-col>
        </v-col>

        <v-col sm="12" cols="12">
         <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Job ID</th>
                <th>Farm Location</th>
                <th>Start Date</th>
                <th>Start Time</th>
                
                <th>Tech</th>
		 <th>Price</th>
		<th>Status</th>
            </tr>
        </thead>
        <tbody>
    <tr  v-for="record in jobs">
                  <td>{{record.id}}</td>
                  <td>{{record.farm.farm_address}} {{record.farm.farm_unit}} {{record.farm.farm_city}} {{record.farm.farm_province}} {{record.farm.farm_zipcode}}</td>
                  <td>{{record.created_at | formatDate}}</td>
                  <td>{{record.time_slots_id}}</td>
                  <td>
			<template v-if="record.truck_driver_id">Truck driver name</template>
			<template v-if="!record.truck_driver_id">Not Assigned Yet</template>
	                <template v-if="record.skidsteer_driver_id">Skidsteer driver name</template>
			<template v-if="!record.skidsteer_driver_id">Not Assigned Yet</template>
                   </td>
                  <td>${{record.job_amount}}</td>
                  <td v-if="!record.repeating_job">Scheduled</td>
	          <td v-if="record.repeating_job">Rescheduled</td>
                </tr>
</tbody>
    </table>
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
      records: "",
      jobs: "",
    };
  },

  mounted() {
    this.getResult();
  },
  methods: {
    getResult() {
      customerService
        .getCustomerRecord(this.$route.params.id)
        .then(response => {
          //handle response
          if (response.status) {
            this.records = response.data;
            this.jobs = response.data.jobs;
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
    },
 
  },
updated() {
setTimeout(function() {
     $(document).ready(function() {
	    $('#example').DataTable();
	} );
  }, 1000);
    }
};
</script>
