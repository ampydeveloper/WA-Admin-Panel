<template>
  <v-container
    id="dashboard"
    fluid
    tag="section"
    class="pt-0"
  >
    <v-row>
        <v-col sm="12" cols="12">
        <!-- all jobs -->
            <table id="jobsalary" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Employee Name</th>
                        <th>Contact Number</th>
                        <th>Designation</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Total Salary</th>
                        <th>View Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(salary, index ) in salaryJobs">
                        <td>{{index+1}}</td>
                        <td>
<router-link v-if="isAdmin" :to="'/admin/truckdriver/edit/' + salary.user.id" class="nav-item nav-link">{{salary.user.first_name}}</router-link>
<router-link v-if="!isAdmin" :to="'/manager/truckdriver/edit/' + salary.user.id" class="nav-item nav-link">{{salary.user.first_name}}</router-link>
</td>
                        <td>{{salary.user.phone}}</td>
                        <td>
			<template v-if="salary.user.driver.driver_type">Truck Driver</template>
	  		<template v-if="!salary.user.driver.driver_type">Skidsteer Driver</template>
			</td>
                        <td>{{salary.month}}</td>
                        <td>{{salary.year}}</td>
                        <td>${{salary.salary}}</td>
                        <td><router-link v-if="isAdmin" :to="'/admin/accounting/details/' + salary.user.id" class="nav-item nav-link">View Details</router-link>
<router-link v-if="!isAdmin" :to="'/manager/accounting/details/' + salary.user.id" class="nav-item nav-link">View Details</router-link>
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
import { environment } from "../../../../config/test.env";
import { PlusCircleIcon } from "vue-feather-icons";
import { accountingService } from "../../../../_services/accounting.service";
import { authenticationService } from "../../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon
  },
  data() {
    return {
      salaryJobs:'',
      isAdmin: true,
  };
  },
  mounted: function() {
    const currentUser = authenticationService.currentUserValue;
    if(currentUser.data.user.role_id == 1){
    this.isAdmin = true;
    }else{
    this.isAdmin = false;
    }
    this.invoiceList();
  },
  methods: {
	invoiceList(){
        accountingService.jobSalary().then(response => {
        //handle response
        if (response.status) {
          this.salaryJobs = response.data;
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
	    $('#jobsalary').DataTable();
	} );
  }, 1000);
    }
}

</script>
