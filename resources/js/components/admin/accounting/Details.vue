<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-tabs class="custom-tabs-wdout-modif" v-model="tab" background-color="transparent" color="basil" grow>
            <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="custom-tab-content">
          <v-tab-item v-for="item in items" :key="item">
            <v-card class="" color="basil" flat v-if="item == 'Invoices'">
                <customer-invoice />
            </v-card>
            <v-card class="" color="basil" flat v-if="item == 'Payments'">
                <customer-payment />
            </v-card>
            <v-card class="" color="basil" flat v-if="item == 'Salary'">
                <customer-salary />
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
  export default {
    components: {
      CustomerInvoice: () => import('./tab/invoice'),
      CustomerPayment: () => import('./tab/payment'),
      CustomerSalary: () => import('./tab/salary')
    },
    data() {
    	return {
            tab: null,
            items: ["Invoices", "Payments", "Salary"],
        }
    }
  }
</script>