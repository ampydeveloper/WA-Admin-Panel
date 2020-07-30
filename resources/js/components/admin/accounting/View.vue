<template>
  <v-container
    id="dashboard"
    fluid
    tag="section"
  >
    <v-row>
      <v-col cols="12" sm="12">
        <div class="account-upper">
          <div class="v-avatar v-list-item__avatar account-left" style="height: 110px; width: 110px;"><img :src="avatar" alt="account"></div>
          <div class="account-right">
            <h3 v-if="driverDetails.user.first_name">
	     <router-link v-if="isAdmin" :to="'/admin/truckdriver/edit/'+driverDetails.user.id">
              {{driverDetails.user.first_name}}
            </router-link>
	     <router-link v-if="!isAdmin" :to="'/manager/truckdriver/edit/'+driverDetails.user.id">
              {{driverDetails.user.first_name}}
            </router-link>
	    </h3>
            <h5>
            <template v-if="driverDetails.user.driver.driver_type">Truck Driver</template>
	    <template v-if="!driverDetails.user.driver.driver_type">Skidsteer Driver</template>
	    </h5>
            <router-link to="/admin/accounting">
              <v-btn color="success" class="custom-save-btn mt-2">Check Invoice</v-btn>
            </router-link>
            <router-link to="/admin/accounting">
              <v-btn color="success" class="custom-save-btn ml-4 mt-2">Send Payment</v-btn>
            </router-link>
          </div>
        </div>
        <div class="account-mid">
          <table id="" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Sno</th>
                    <th>Date</th>
                    <th>Total km</th>
                    <th>Salary</th>
                    <th>Total Time</th>
                    <th>Shift Time</th>
                    <th>Time Slot</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="(sal, index) in salary">
                  <td>{{index+1}}</td>
                  <td>{{sal.updated_at | formatDate}}</td>
                  <td>
                     <template v-if="sal.user.driver.salary_type">{{sal.load}}</template>
		     <template v-if="!sal.user.driver.salary_type">{{sal.distance}}</template>
		  </td>
                  <td>
                     <template v-if="sal.user.driver.salary_type">${{sal.user.driver.driver_salary}}/Per Load</template>
		     <template v-if="!sal.user.driver.salary_type">${{sal.user.driver.driver_salary}}/Per Hour</template>
		  </td>
                  <td>{{sal.job.job_time}}</td>
                  <td>Evening</td>
                  <td>4PM-7PM</td>
                  <td>${{sal.salary}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="account-bottom mt-6">
          <span class="tt-amt">TOTAL AMOUNT</span>
          <span class="amt-cash">${{total}}</span>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import { PlusCircleIcon } from "vue-feather-icons";
import { accountingService } from "../../../_services/accounting.service";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    PlusCircleIcon
  },
  data() {
    return {
      salary:'',
      total: '',
      driverDetails: '',
      isAdmin: true,
      imgUrl: environment.imgUrl,
      avatar:''
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
        accountingService.jobSingleSalary(this.$route.params.id).then(response => {
        //handle response
        if (response.status) {
          this.salary = response.data.salary;
	  this.total = response.data.total;
	  this.driverDetails = response.data.driver;
	  if(response.data.driver.user.user_image){
	     this.avatar = this.imgUrl+response.data.driver.user.user_image;
	  }else{
	     this.avatar = "/images/avatar.png";
	  }
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
	    $('#example').DataTable();
	} );
  }, 1000);
    }
}
</script>
