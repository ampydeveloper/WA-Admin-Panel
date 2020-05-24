import Vue from "vue";
import Router from "vue-router";

import { authenticationService } from "../_services/authentication.service";
import { Role } from "./role";

import HomePage from "../home/HomePage";
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

//serivce
import SerivcesListPage from "../components/admin/services/ListPage";
import SerivcesAddPage from "../components/admin/services/AddPage";
import SerivcesEditPage from "../components/admin/services/EditPage";
import SerivcesViewPage from "../components/admin/services/ViewPage";


//truck driver
import TruckDriverListPage from "../components/admin/trcukdriver/ListPage";
import TruckDriverAddPage from "../components/admin/trcukdriver/AddPage";
import TruckDriverEditPage from "../components/admin/trcukdriver/EditPage";
import TruckDriverViewPage from "../components/admin/trcukdriver/ViewPage";

//truck
import TruckListPage from "../components/admin/trcuk/ListPage";
import TruckAddPage from "../components/admin/trcuk/AddPage";
import TruckEditPage from "../components/admin/trcuk/EditPage";
import TruckViewPage from "../components/admin/trcuk/ViewPage";

//skidsteer
import SkidsteerListPage from "../components/admin/skidsteer/ListPage";
import SkidsteerAddPage from "../components/admin/skidsteer/AddPage";
import SkidsteerEditPage from "../components/admin/skidsteer/EditPage";
import SkidsteerViewPage from "../components/admin/skidsteer/ViewPage";

import LoginPage from "../components/login/LoginPage";
import RegisterPage from "../components/register/RegisterPage";
import ChangePassword from "../components/ChangePasswordPage";


Vue.use(Router);

export const router = new Router({
  mode: "history",
  routes: [
    { path: "/", component: HomePage, meta: { requiresAuth: [Role.Customer] } },
    { path: "/confirm-email", component: HomePage },
    //admin routes
    {
      path: '/admin',
      component: AdminLayout,
      name: 'SuperAdmin',
      meta: { requiresAuth: [Role.admin]},
      children: [
        { path: 'dashboard', component: Dashboard, name: 'Dashboard', meta: { requiresAuth: Role.Admin} },
        { path: 'settings', component: Settings, name: 'Settings', meta: { requiresAuth: Role.Admin} },
        { path: 'profile', component: ProfilePage, name: 'Profile' },
        { path: 'changepassword', component: ChangePasswordPage, name: 'Changepassword', meta: { requiresAuth: Role.Admin} },
        { path: 'admin', component: AdminListPage, name: 'Admin', meta: { requiresAuth: Role.Admin} },
        { path: 'admin/add', component: AdminAddPage, name: 'AdminAdd', meta: { requiresAuth: Role.Admin} },
        { path: 'admin/edit/:id', component: AdminEditPage, name: 'AdminEdit', meta: { requiresAuth: Role.Admin} },
        { path: 'admin/view/:id', component: AdminViewPage, name: 'AdminView', meta: { requiresAuth: Role.Admin} },
        
        { path: 'manager', component: ListPage, name: 'Manager', meta: { requiresAuth: Role.Admin} },
        { path: 'manager/add', component: AddPage, name: 'Add', meta: { requiresAuth: Role.Admin} },
        { path: 'manager/edit/:id', component: EditPage, name: 'Edit', meta: { requiresAuth: Role.Admin} },
        { path: 'manager/view/:id', component: ViewPage, name: 'View', meta: { requiresAuth: Role.Admin} },
        { path: 'services', component: SerivcesListPage, name: 'Services', meta: { requiresAuth: Role.Admin} },
        { path: 'service/add', component: SerivcesAddPage, name: 'ServiceAdd' },
        { path: 'service/edit/:id', component: SerivcesEditPage, name: 'ServiceEdit' },
        { path: 'service/view/:id', component: SerivcesViewPage, name: 'ServiceView' },
        { path: 'truckdrivers', component: TruckDriverListPage, name: 'Truckdrivers' },
        { path: 'truckdriver/add', component: TruckDriverAddPage, name: 'TruckdriverAdd' },
        { path: 'truckdriver/edit/:id', component: TruckDriverEditPage, name: 'TruckdriverEdit' },
        { path: 'truckdriver/view/:id', component: TruckDriverViewPage, name: 'TruckdriverView' },
        { path: 'trucks', component: TruckListPage, name: 'Trucks' },
        { path: 'truck/add', component: TruckAddPage, name: 'TruckAdd' },
        { path: 'truck/edit/:id', component: TruckEditPage, name: 'TruckEdit' },
        { path: 'truck/view/:id', component: TruckViewPage, name: 'TruckView' },
        { path: 'skidsteers', component: SkidsteerListPage, name: 'Skidsteers' },
        { path: 'skidsteer/add', component: SkidsteerAddPage, name: 'SkidsteerAdd' },
        { path: 'skidsteer/edit/:id', component: SkidsteerEditPage, name: 'SkidsteerEdit' },
        { path: 'skidsteer/view/:id', component: SkidsteerViewPage, name: 'SkidsteerView' },
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
