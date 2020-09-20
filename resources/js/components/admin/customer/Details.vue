<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Customer Details
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
        <li>
          <router-link to="/admin/customer">
            List
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
        <li>Edit</li>
      </ul>

      <div class="top-right-link add-icon" style="display:none;">
        <router-link
          v-if="isAdmin"
          :to="'/admin/customer/addfarmmore/' + addForm.id"
          class="nav-item nav-link"
        >Add Farm</router-link>
        <router-link
          v-if="!isAdmin"
          :to="'/manager/customer/addfarmmore/' + addForm.id"
          class="nav-item nav-link"
        >Add Farm</router-link>
      </div>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-tabs
            class="custom-tabs-wdout-modif"
            v-model="tab"
            background-color="transparent"
            color="#56bf7a"
            grow
          >
            <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
          </v-tabs>
          <v-tabs-items v-model="tab" class="custom-tab-content">
            <v-tab-item v-for="item in items" :key="item">
              <!-- customer info tabs -->
              <v-card class="customer-details-tabs" color="basil" flat v-if="item == 'Customer'">
                <customer-info />
              </v-card>
              <!-- Payments tab -->
              <v-card class="payment-tabs" color="basil" flat v-if="item == 'Payment'">
                <customer-payment />
              </v-card>
              <!-- Farms tab -->
              <v-card class color="basil" flat v-if="item == 'Farms'">
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
    </div>
  </v-app>
</template>

<script>
import { customerService } from "../../../_services/customer.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";
export default {
  components: {
    CustomerInfo: () => import("./tab/info"),
    CustomerFarm: () => import("./tab/farm"),
    CustomerPayment: () => import("./tab/payment"),
    CustomerRecord: () => import("./tab/records"),
  },

  data() {
    return {
      tab: null,
      items: ["Customer", "Payment", "Farms", "Records"],
      isAdmin: true,
      addForm: {
        id: "",
      },
    };
  },
  mounted: function () {
    const currentUser = authenticationService.currentUserValue;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    customerService.getCustomer(this.$route.params.id).then((response) => {
      //handle response
      if (response.status) {
        this.addForm = {
          id: response.data.id,
        };
      } else {
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
};
</script>
