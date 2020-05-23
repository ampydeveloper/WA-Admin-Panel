<template>
  <div>
      <!--header for without login user-->
      <nav v-if="!isAdmin" class="navbar navbar-expand navbar-dark bg-dark">
        <header-component></header-component>
      </nav>
    
      <nav v-if="isCustomer" class="navbar navbar-expand navbar-dark bg-dark">
        <frontend-header-component></frontend-header-component>
     </nav>
      <!-- <nav v-if="isAdmin" class="navbar navbar-expand navbar-dark bg-dark">
          <backend-header-component></backend-header-component>
      </nav> -->
     <router-view></router-view>
  </div>
</template>

<script>
import { authenticationService } from "../_services/authentication.service";
import { router } from "../_helpers/router";
import { Role } from "../_helpers/role";
import Header from '.././menu/header';
import Footer from '.././menu/footer';

import FrontendHeader from '.././menu/frontend/header';
import FrontendFooter from '.././menu/frontend/footer';

import BackendHeader from '.././menu/backend/header';
import BackendFooter from '.././menu/backend/footer';
import BackendSidebar from '.././menu/backend/sidebar';

export default {
  name: "app",
   components: {
      'header-component': Header,
      'footer-component': Footer,
      'frontend-header-component': FrontendHeader,
      'frontend-footer-component': FrontendFooter,
      'backend-header-component': BackendHeader,
      'backend-sidebar-component': BackendSidebar,
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
