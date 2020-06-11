<template>
  <v-app>
    <v-container>
      <v-row>
        <v-tabs class="custom-tabs" v-model="tab" background-color="transparent" color="basil" grow>
          <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="custom-tab-content">
          <v-tab-item v-for="item in items" :key="item">
	   <!-- customer info tabs -->
            <v-card class="service-tab-content" color="basil" flat v-if="item == 'Customer Info'">
              <v-card-text>
             	 <customer-info />
              </v-card-text>
            </v-card>
            <!-- Frams tab -->
            <v-card class="insurance-tab-content" color="basil" flat v-if="item == 'Farms'">
              <v-card-text>
		Frams Details
              </v-card-text>
            </v-card>
            <!-- Payments tab -->
            <v-card class="insurance-tab-content" color="basil" flat v-if="item == 'Payments'">
              <v-card-text>
		payment Details
              </v-card-text>
            </v-card>
            <!-- Records tab -->
            <v-card class="insurance-tab-content" color="basil" flat v-if="item == 'Records'">
              <v-card-text>
		Records tab
              </v-card-text>
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
    },

     data() {
    	return {
	tab: null,
        items: ["Customer Info", "Farms", "Payments", "Records"],
        }
    }
  }
</script>
