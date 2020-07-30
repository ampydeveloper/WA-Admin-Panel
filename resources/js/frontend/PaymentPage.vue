<template>
  <div>
    <stripe-elements
      ref="elementsRef"
      :pk="publishableKey"
      :amount="amount"
      locale="en"
      @token="tokenCreated"
      @loading="loading = $event"
    >
    </stripe-elements>
    <button @click="submit">Pay ${{amount}}</button>
  </div>
</template>
 
<script>
import { paymentService } from "../_services/payment.service";
import { router } from "../_helpers/router";
import { StripeElements } from 'vue-stripe-checkout';
import { environment } from '../config/test.env';
export default {
  components: {
    StripeElements
  },
  data: () => ({
    loading: false,
    addForm:{ 
      customer_id: null,
      job_id:null,
      stripeToken:null,
      amount: null,
      newCard:1,
    },
    amount: null,
    token:null,
    charge: null,
    publishableKey: environment.stripePublishableKey, 
  }),
 mounted: function() {
     if (this.$route.params.unique_id) {
        paymentService.paymentJob(this.$route.params.unique_id).then(response => {
          //handle response
          if (response.data) {
           	this.addForm.job_id = response.data.id;
		        this.amount = response.data.job_amount;
            this.addForm.customer_id = response.data.customer_id;
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
  methods: {
    submit () {
       this.$refs.elementsRef.submit();
    },
    tokenCreated (token) {
      this.token = token;
     this.charge = {
        source: token.id,
        amount: this.amount,
      }
      this.sendTokenToServer(this.charge);
    },
    sendTokenToServer (charge) {
     this.addForm.amount = charge.amount;
     this.addForm.stripeToken = charge.source,
    paymentService.stripeCharge(this.addForm).then(response => {
          //handle response
          if (response.status) {
             this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
           router.push("/home");
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
    },
  }
}
</script> 
