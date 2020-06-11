<template>
  <div>    
     <router-view></router-view>
  </div>
</template>

<script>
import { authenticationService } from "../_services/authentication.service";
import { router } from "../_helpers/router";
import { Role } from "../_helpers/role";

export default {
  name: "app",
   components: {
    },
  data() {
    return {
      currentUser: null
    };
  },
  computed: {
    isAdmin() {
      return this.currentUser && this.currentUser.data.user.role_id === Role.Admin;
    },
    isAdminManager() {
      return this.currentUser && this.currentUser.data.user.role_id === Role.Admin_Manager;
    },
    isTruckDriver() {
      return this.currentUser && this.currentUser.data.user.role_id === Role.Truck_Driver;
    },
    isCustomer() {
      return this.currentUser && this.currentUser.data.user.role_id === Role.Customer;
    },
    isCustomerManager() {
      return this.currentUser && this.currentUser.data.user.role_id === Role.Customer_Manager;
    },
    Company() {
      return this.currentUser && this.currentUser.data.user.role_id === Role.Company;
    }
  },
  created() {
    authenticationService.currentUser.subscribe(x => (this.currentUser = x));
  },
  methods: {
    logout() {
      authenticationService.logout();
      router.push("/login");
    }
  }
};
</script>
