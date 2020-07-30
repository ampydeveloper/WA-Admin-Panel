<template>
  <v-container fluid
    id="dashboard"
    fluid
    tag="section"
    class="pt-0"
  >
    <v-row>
 <v-col sm="12" cols="12">
      <!-- all jobs -->
  	    <table id="assinged" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sno</th>
                <th>Job Summary</th>
                <th>Sort By</th>
                <th>Techs</th>
                <th>Time</th>
		 <th>Distance</th>
		<th>Payment</th>
                <th>Chat</th>
            </tr>
        </thead>
        <tbody>
    <tr v-for="(job, index) in alljobs">
<td>{{index+1}}</td>
	<td>{{job.start_date}}<br> {{job.id}} <br>${{job.job_amount}} <br>{{job.service.service_name}}</td>
	<td>{{job.customer.first_name}}<br> {{job.manager.first_name}} <br> {{job.manager.phone}} <br>{{job.manager.email}}
<br>{{job.manager.address}} {{job.manager.city}} {{job.manager.state}} {{job.manager.country}} {{job.manager.zip_code}}
</td>
<td>
Truck Driver Name<br><template v-if="job.truck_driver">{{job.truck_driver.first_name}}</template><template v-if="!job.truck_driver">Not Assigned Yet</template>
Truck Number<br><template v-if="job.truck">{{job.truck.truck_number}}</template><template v-if="!job.truck">Not Assigned Yet</template>

skidsteer Driver Name<br><template v-if="job.skidsteer_driver">{{job.skidsteer_driver.first_name}}</template><template v-if="!job.skidsteer_driver">Not Assigned Yet</template>
skidsteer Number</br><template v-if="job.skidsteer">{{job.skidsteer.truck_number}}</template><template v-if="!job.skidsteer">Not Assigned Yet</template>
</td>
<td>
Start Time<br><template>9:30 pm</template>
End Time<br><template>12:30 Pm</template>
Time Taken<br><template>3</template>
</td>
<td>3000 dummy</td>
<td><template v-if="job.payment_status">Paid</template> <template v-if="!job.payment_status">Unpaid</template></td>
<td> <router-link :to="'/admin/jobs/chat/' + job.id" class="nav-item nav-link">View chat</router-link>
<router-link v-if="!isAdmin" :to="'/manager/jobs/chat/' + job.id" class="nav-item nav-link">View chat</router-link>
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
    PlusCircleIcon
  },
  data() {
    return {
      alljobs:'',
      isAdmin: true,
    };
  },
  created() {
  
  },
   mounted() {
    const currentUser = authenticationService.currentUserValue;
    if(currentUser.data.user.role_id == 1){
    this.isAdmin = true;
    }else{
    this.isAdmin = false;
    }
    this.getResults();
   },
    methods: {
     getResults() {
      jobService.jobassigned().then(response => {
        //handle response
        if (response.status) {
          this.alljobs = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right"
          });
        }
      });
    }
},
updated() {
setTimeout(function() {
     $(document).ready(function() {
	    $('#assinged').DataTable();
	} );
  }, 1000);
    }
};
</script>
