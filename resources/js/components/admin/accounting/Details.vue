<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
           Accounting
            <span class="right-bor"></span>
          </h4>
        </li>
        <li>
          <router-link to="/admin/dashboard" class="home_svg">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-home h-5 w-5 mb-1 stroke-current text-primary"
            >
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16px"
                height="16px"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-chevrons-right w-4 h-4"
              >
                <polyline points="13 17 18 12 13 7" />
                <polyline points="6 17 11 12 6 7" />
              </svg>
            </span>
          </router-link>
        </li>
        <li>List</li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-tabs
            class="custom-tabs-wdout-modif c-tabs-50"
            v-model="tab"
            background-color="transparent"
            color="#56bf7a"
            grow
          >
            <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
          </v-tabs>
          <v-tabs-items v-model="tab" class="custom-tab-content">
            <v-tab-item v-for="item in items" :key="item">
              <v-card class color="basil" flat v-if="item == 'Invoices'">
                <customer-invoice />
              </v-card>
              <v-card class color="basil" flat v-if="item == 'Payments'">
                <customer-payment />
              </v-card>
              <v-card class color="basil" flat v-if="item == 'Salary'">
                <customer-salary />
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-row>
      </v-container>
    </div>
  </v-app>
</template>

<script>
export default {
  components: {
    CustomerInvoice: () => import("./tab/invoice"),
    CustomerPayment: () => import("./tab/payment"),
    CustomerSalary: () => import("./tab/salary"),
  },
  data() {
    return {
      tab: null,
      items: ["Invoices", "Payments", "Salary"],
    };
  },
};
</script>