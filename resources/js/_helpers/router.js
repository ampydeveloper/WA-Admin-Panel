import Vue from "vue";
import Router from "vue-router";

import { authenticationService } from "../_services/authentication.service";
import { Role } from "./role";

import HomePage from "../frontend/HomePage";
import PaymentPage from "../frontend/PaymentPage";
//layouts
import AdminLayout from "../components/layout/AdminLayout";
//admin components
import Dashboard from "../components/admin/Dashboard";
import Settings from "../components/admin/Settings";
import ProfilePage from "../components/admin/profile/ProfilePage";
import ChangePasswordPage from "../components/admin/profile/ChangePasswordPage"

//add new admin
import AdminListPage from "../components/admin/admin/ListPage";
import AdminAddPage from "../components/admin/admin/AddPage";
import AdminEditPage from "../components/admin/admin/EditPage";
import AdminViewPage from "../components/admin/admin/ViewPage";

//manager
import ListPage from "../components/admin/manager/ListPage";
import AddPage from "../components/admin/manager/AddPage";
import EditPage from "../components/admin/manager/EditPage";
import ViewPage from "../components/admin/manager/ViewPage";

//customer listing
import CustomerListPage from "../components/admin/customer/ListPage";
import CustomerAddPage from "../components/admin/customer/AddPage";
import CustomerDetails from "../components/admin/customer/Details";


//Company listing
import CompanyListPage from "../components/admin/company/ListPage";
import CompanyAddPage from "../components/admin/company/AddPage";
import CompanyEditPage from "../components/admin/company/EditPage";
import CompanySection from "../components/admin/company/Section";
import CompanyDetails from "../components/admin/company/Details";


//serivce
import SerivcesListPage from "../components/admin/services/ListPage";
import SerivcesAddPage from "../components/admin/services/AddPage";
import SerivcesEditPage from "../components/admin/services/EditPage";
import SerivcesViewPage from "../components/admin/services/ViewPage";


//truck driver
import TruckDriverListPage from "../components/admin/truckdriver/ListPage";
import TruckDriverAddPage from "../components/admin/truckdriver/AddPage";
import TruckDriverEditPage from "../components/admin/truckdriver/EditPage";
import TruckDriverViewPage from "../components/admin/truckdriver/ViewPage";

//truck
import TruckListPage from "../components/admin/truck/ListPage";
import TruckAddPage from "../components/admin/truck/AddPage";
import TruckEditPage from "../components/admin/truck/EditPage";
import TruckViewPage from "../components/admin/truck/ViewPage";
import TruckDocViewPage from "../components/admin/truck/ViewDocPage";
import TruckViewServicePage from "../components/admin/truck/ViewServicePage";
import TruckAddServicePage from "../components/admin/truck/AddServicePage";
import TruckEditServicePage from "../components/admin/truck/EditServicePage";
import TruckInsuranceServicePage from "../components/admin/truck/InsuranceServicePage";
import TruckInsuranceEditServicePage from "../components/admin/truck/EditInsuranceServicePage";
//skidsteer
import SkidsteerListPage from "../components/admin/skidsteer/ListPage";
import SkidsteerAddPage from "../components/admin/skidsteer/AddPage";
import SkidsteerEditPage from "../components/admin/skidsteer/EditPage";
import SkidsteerViewPage from "../components/admin/skidsteer/ViewPage";
import SkidsteerDocViewPage from "../components/admin/skidsteer/ViewDocPage";
import SkidsteerViewServicePage from "../components/admin/skidsteer/ViewServicePage";
import SkidsteerAddServicePage from "../components/admin/skidsteer/AddServicePage";

//jos
import JobsViewPage from "../components/admin/jobs/View";
//dispatches
import DispatchesViewPage from "../components/admin/dispatches/View";

//reports
import ReportsViewPage from "../components/admin/reports/View";
//AccountingViewPage
import AccountingViewPage from "../components/admin/accounting/View";

import LoginPage from "../components/login/LoginPage";
import RegisterPage from "../components/register/RegisterPage";
import ChangePassword from "../components/ChangePasswordPage";


Vue.use(Router);

export const router = new Router({
  mode: "history",
  routes: [
    { path: "/", component: HomePage, meta: { requiresAuth: [Role.Customer] } },
    { path: "/home", component: HomePage, name:'Home',  meta: { requiresAuth: [Role.Customer] } },
    { path: "/payment", component: PaymentPage, name:'Payment',  meta: { requiresAuth: [Role.Customer] } },
    //{ path: "/confirm-email", component: HomePage },
    //admin routes
    {
      path: '/admin',
      component: AdminLayout,
      name: 'SuperAdmin',
      meta: { requiresAuth: [Role.admin]},
      children: [
        { path: 'dashboard', component: Dashboard, name: 'Dashboard', meta: { requiresAuth: [Role.Admin]} },
        { path: 'settings', component: Settings, name: 'Settings', meta: { requiresAuth: [Role.Admin]} },
        { path: 'profile', component: ProfilePage, name: 'Profile' },
        { path: 'changepassword', component: ChangePasswordPage, name: 'Changepassword', meta: { requiresAuth: [Role.Admin]} },
        { path: 'admin', component: AdminListPage, name: 'Admin', meta: { requiresAuth: [Role.Admin]} },
        { path: 'admin/add', component: AdminAddPage, name: 'AdminAdd', meta: { requiresAuth: [Role.Admin]} },
        { path: 'admin/edit/:id', component: AdminEditPage, name: 'AdminEdit', meta: { requiresAuth: [Role.Admin]} },
        { path: 'admin/view/:id', component: AdminViewPage, name: 'AdminView', meta: { requiresAuth: [Role.Admin]} },

        { path: 'customer', component: CustomerListPage, name: 'Customer', meta: { requiresAuth: [Role.Admin]} },
        { path: 'customer/add', component: CustomerAddPage, name: 'CustomerAdd', meta: { requiresAuth: [Role.Admin]} },
        { path: 'customer/details/:id', component: CustomerDetails, name: 'CustomerDetail', meta: { requiresAuth: [Role.Admin]} },

        { path: 'company', component: CompanyListPage, name: 'Company', meta: { requiresAuth: [Role.Admin]} },
        { path: 'company/add', component: CompanyAddPage, name: 'CompanyAdd', meta: { requiresAuth: [Role.Admin]} },
        { path: 'company/edit/:id', component: CompanyEditPage, name: 'CompanyEdit', meta: { requiresAuth: [Role.Admin]} },
        { path: 'company/section', component: CompanySection, name: 'CompanySection', meta: { requiresAuth: [Role.Admin]} },
        { path: 'company/details', component: CompanyDetails, name: 'CompanyDetail', meta: { requiresAuth: [Role.Admin]} },
        
        { path: 'manager', component: ListPage, name: 'Manager', meta: { requiresAuth: [Role.Admin]} },
        { path: 'manager/add', component: AddPage, name: 'Add', meta: { requiresAuth: [Role.Admin]} },
        { path: 'manager/edit/:id', component: EditPage, name: 'Edit', meta: { requiresAuth: [Role.Admin]} },
        { path: 'manager/view/:id', component: ViewPage, name: 'View', meta: { requiresAuth: [Role.Admin]} },

        { path: 'services', component: SerivcesListPage, name: 'Services', meta: { requiresAuth: [Role.Admin]} },
        { path: 'service/add', component: SerivcesAddPage, name: 'ServiceAdd' },
        { path: 'service/edit/:id', component: SerivcesEditPage, name: 'ServiceEdit', meta: { requiresAuth: [Role.Admin]} }, 
        { path: 'service/view/:id', component: SerivcesViewPage, name: 'ServiceView', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truckdrivers', component: TruckDriverListPage, name: 'Truckdrivers', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truckdriver/add', component: TruckDriverAddPage, name: 'TruckdriverAdd', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truckdriver/edit/:id', component: TruckDriverEditPage, name: 'TruckdriverEdit', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truckdriver/view/:id', component: TruckDriverViewPage, name: 'TruckdriverView', meta: { requiresAuth: [Role.Admin]} },
        { path: 'trucks', component: TruckListPage, name: 'Trucks', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truck/add', component: TruckAddPage, name: 'TruckAdd', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truck/edit/:id', component: TruckEditPage, name: 'TruckEdit', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truck/view/:id', component: TruckViewPage, name: 'TruckView', meta: { requiresAuth: [Role.Admin]} },
        { path: 'truck/docview/:id', component: TruckDocViewPage, name: 'TruckDocView', meta: { requiresAuth: [Role.Admin]} },
	{ path: 'truck/service/:id', component: TruckViewServicePage, name: 'TruckViewService' , meta: { requiresAuth: [Role.Admin]} },
        { path: 'truck/addservice/:id', component: TruckAddServicePage, name: 'TruckAddService' , meta: { requiresAuth: [Role.Admin]} },
        { path: 'truck/editservice/:id', component: TruckEditServicePage, name: 'TruckEditService' , meta: { requiresAuth: [Role.Admin]} },
	{ path: 'truck/addinsurance/:id', component: TruckInsuranceServicePage, name: 'TruckAddInsurance' , meta: { requiresAuth: [Role.Admin]} },
	{ path: 'truck/editinsurance/:id', component: TruckInsuranceEditServicePage, name: 'TruckEditInsurance' , meta: { requiresAuth: [Role.Admin]} },
        { path: 'skidsteers', component: SkidsteerListPage, name: 'Skidsteers', meta: { requiresAuth: [Role.Admin]} },
        { path: 'skidsteer/add', component: SkidsteerAddPage, name: 'SkidsteerAdd', meta: { requiresAuth: [Role.Admin]} },
        { path: 'skidsteer/edit/:id', component: SkidsteerEditPage, name: 'SkidsteerEdit', meta: { requiresAuth: [Role.Admin]} },
        { path: 'skidsteer/view/:id', component: SkidsteerViewPage, name: 'SkidsteerView', meta: { requiresAuth: [Role.Admin]} },
	{ path: 'skidsteer/docview/:id', component: SkidsteerDocViewPage, name: 'SkidsteerDocView', meta: { requiresAuth: [Role.Admin]} },
	{ path: 'Skidsteer/service/:id', component: SkidsteerViewServicePage, name: 'SkidsteerViewService', meta: { requiresAuth: [Role.Admin]} },
       { path: 'Skidsteer/addservice/:id', component: SkidsteerAddServicePage, name: 'SkidsteerAddService', meta: { requiresAuth: [Role.Admin]} },

	{ path: 'jobs', component: JobsViewPage, name: 'Jobs', meta: { requiresAuth: [Role.Admin]} },
        { path: 'dispatches', component: DispatchesViewPage, name: 'Dispatches', meta: { requiresAuth: [Role.Admin]} },
        { path: 'accounting', component: AccountingViewPage, name: 'Accounting', meta: { requiresAuth: [Role.Admin]} },
        { path: 'reports', component: ReportsViewPage, name: 'Reports', meta: { requiresAuth: [Role.Admin]} },
      ]
    },
   
    //manager routes
    {
      path: '/manager',
      component: AdminLayout,
      name: 'ManagerDashboard',
      meta: { requiresAuth: Role.Admin_Manager},
      children: [
        { path: 'dashboard', component: Dashboard, name: 'Manager_Dashboard', meta: { requiresAuth: [Role.Admin_Manager]} },
        { path: 'settings', component: Settings, name: 'Manager_Settings', meta: { requiresAuth: [Role.Admin_Manager]} },
 	{ path: 'changepassword', component: ChangePasswordPage, name: 'MChangepassword', meta: { requiresAuth: [Role.Admin_Manager]} },
      ]
    },
    //driver routes
    {
      path: '/driver',
      component: AdminLayout,
      name: 'DriverDashboard',
      meta: { requiresAuth: Role.Truck_Driver},
      children: [
        { path: 'dashboard', component: Dashboard, name: 'driver_Dashboard', meta: { requiresAuth: [Role.Truck_Driver]} },
        { path: 'settings', component: Settings, name: 'Driver_Settings', meta: { requiresAuth: [Role.Truck_Driver]} },
 	{ path: 'changepassword', component: ChangePasswordPage, name: 'DChangepassword', meta: { requiresAuth: [Role.Truck_Driver]} },
      ]
    },
    { path: "/login", component: LoginPage },
    { path: "/register", component: RegisterPage },
    { path: "/change-passowrd", component: ChangePassword },

    { path: '/auth/:provider/callback',
      component: {
        template: '<div class="auth-component"></div>'
      }
    },

    // otherwise redirect to home
    { path: "*", redirect: "/login" }
  ]
});

router.beforeEach((to, from, next) => {
  // redirect to login page if not logged in and trying to access a restricted page
  const { requiresAuth } = to.meta;
  const currentUser = authenticationService.currentUserValue;

  if (requiresAuth) {
    if (!currentUser) {
      // not logged in so redirect to login page with the return url
      return next({ path: "/login", query: { returnUrl: to.path } });
    }
    // check if route is restricted by role
    if (requiresAuth.length && requiresAuth.includes(currentUser.data.user.role_id)) {
	if(!currentUser.data.user.password_changed_at){
	  return next({ path: "/change-passowrd", query: { returnUrl: to.path } });
	}
    }
 	
	
    // check if route is restricted by role
    if (requiresAuth.length && !requiresAuth.includes(currentUser.data.user.role_id)) {
	 localStorage.removeItem("currentUser");
      // role not authorised so redirect to home page
      return next({ path: "/login" });
    }
  }

  next();
});
