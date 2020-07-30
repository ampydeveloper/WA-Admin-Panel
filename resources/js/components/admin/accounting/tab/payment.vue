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
            <table id="jobpayment" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Customer Name</th>
                        <th>Invoice Number</th>
                        <th>Payment Total</th>
                        <th>Payment Type</th>
                        <th>In quick book</th>
                        <th>View</th>
                        <th>Get Payment</th>
                    </tr>
                </thead>
                <tbody>
                  <tr v-for="payment in paymentJobs">
                        <td>{{payment.jobpayment.updated_at | formatDate}}</td>
                        <td><router-link v-if="isAdmin" :to="'/admin/customer/details/'+payment.customer.id" class="nav-item nav-link">
                     {{payment.customer.first_name}}
                    </router-link>
<router-link v-if="!isAdmin" :to="'/manager/customer/details/'+payment.customer.id" class="nav-item nav-link">
                     {{payment.customer.first_name}}
                    </router-link>
                         </td>
                        <td>{{payment.invoice_number}}</td>
                       <td>${{payment.job_amount}}</td>
                        <td>{{payment.jobpayment.payment_mode}}</td>
                        <td>
			    <template v-if="!payment.quick_book">Not Sync</template>
			    <template v-if="payment.quick_book">Sync</template>
			</td>
                        <td>View Details</td>
                        <td>Get Payment</td>
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
      paymentJobs:'',
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
        accountingService.jobPayments().then(response => {
        //handle response
        if (response.status) {
          this.paymentJobs = response.data;
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
	    $('#jobpayment').DataTable();
	} );
  }, 1000);
    }
}

</script>
