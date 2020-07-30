<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <v-tabs class="custom-tabs-wdout-modif" v-model="tab" background-color="transparent" color="basil" grow>
          <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="custom-tab-content">
          <v-tab-item v-for="item in items" :key="item">
	   <!-- customer info tabs -->
            <v-card class="" color="basil" flat v-if="item == 'Customer Info'">
             	 <customer-info />
            </v-card>
            <!-- Payments tab -->
            <v-card class="payment-tabs" color="basil" flat v-if="item == 'Payment'">
              <customer-payment />
            </v-card>
            <!-- Farms tab -->
            <v-card class="" color="basil" flat v-if="item == 'Farms'">
              <customer-farm />
            </v-card>
            <!-- Records tab -->
            <v-card class="record-tabs" color="basil" flat v-if="item == 'Records'">
              <customer-record />
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
      CustomerInfo: () => import('./tab/info'),
      CustomerFarm: () => import('./tab/farm'),
      CustomerPayment: () => import('./tab/payment'),
      CustomerRecord: () => import('./tab/records'),
    },

     data() {
    	return {
	tab: null,
        items: ["Customer Info", "Payment", "Farms", "Records"],
        }
    }
  }
</script>
