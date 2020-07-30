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
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Job#</th>
                        <th>Service</th>
                        <th>Total</th>
                        <th>In quick book</th>
                        <th>Email</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="invoice in invoiceJobs">
                        <td>{{invoice.updated_at | formatDate}}</td>
                        <td><router-link v-if="isAdmin" :to="'/admin/customer/details/'+invoice.customer.id" class="nav-item nav-link">
                     {{invoice.customer.first_name}}
                    </router-link>
                     <router-link v-if="!isAdmin" :to="'/manager/customer/details/'+invoice.customer.id" class="nav-item nav-link">
                     {{invoice.customer.first_name}}
                    </router-link>
			</td>
                        <td><router-link v-if="isAdmin" :to="'/admin/jobs'" class="nav-item nav-link">
                      {{invoice.id}}
                    </router-link>
	<router-link v-if="!isAdmin" :to="'/manager/jobs'" class="nav-item nav-link">
                      {{invoice.id}}
                    </router-link>
			</td>
                       <td><router-link v-if="isAdmin" :to="'/admin/service/edit/'+invoice.service.id" class="nav-item nav-link">
                      {{invoice.service.service_name}}
                    </router-link>
<router-link v-if="!isAdmin" :to="'/manager/service/edit/'+invoice.service.id" class="nav-item nav-link">
                      {{invoice.service.service_name}}
                    </router-link>
		</td>
                        <td>${{invoice.job_amount}}</td>
                        <td>
			    <template v-if="!invoice.quick_book">Not Sync</template>
			    <template v-if="invoice.quick_book">Sync</template>
			</td>
                        <td>Email</td>
                        <td>Download</td>
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
      invoiceJobs:'',
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
        accountingService.jobInvoices().then(response => {
        //handle response
        if (response.status) {
          this.invoiceJobs = response.data;
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
