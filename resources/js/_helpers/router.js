import Vue from "vue";
import Router from "vue-router";

import { authenticationService } from "../_services/authentication.service";
import { Role } from "./role";

import HomePage from "../frontend/HomePage";
import PaymentPage from "../frontend/PaymentPage";
import AddJobPage from "../frontend/AddJobPage";
//layouts
import AdminLayout from "../components/layout/AdminLayout";
import UserLayout from "../frontend/layout/UserLayout";
//admin components
import Dashboard from "../components/admin/Dashboard";
import TruckDashboard from "../components/admin/DriverDashboard";
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
import CustomerAddFarms from "../components/admin/customer/AddFarms";
import CustomerAddMoreFarms from "../components/admin/customer/AddMoreFarms";

//Company listing
import CompanyListPage from "../components/admin/company/ListPage";
import CompanyAddPage from "../components/admin/company/AddPage";
import CompanyEdit from "../components/admin/company/EditPage";

import CompanyDriverListPage from "../components/admin/company/DriverListPage";
import CompanyDriverAddPage from "../components/admin/company/DriverAddPage";
import CompanyDriverEdit from "../components/admin/company/DriverEditPage";

//News
import NewsListPage from "../components/admin/news/ListPage";
import NewsAddPage from "../components/admin/news/AddPage";
import NewsEditPage from "../components/admin/news/EditPage";

//Mechanic
import MechanicListPage from "../components/admin/mechanic/ListPage";
import MechanicAddPage from "../components/admin/mechanic/AddPage";
import MechanicEditPage from "../components/admin/mechanic/EditPage";

//FAQ
import FaqListPage from "../components/admin/faq/ListPage";
import FaqAddPage from "../components/admin/faq/AddPage";
import FaqEditPage from "../components/admin/faq/EditPage";

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
import skidsteerEditServicePage from "../components/admin/skidsteer/EditServicePage";
import skidsteerInsuranceServicePage from "../components/admin/skidsteer/InsuranceServicePage";
import skidsteerInsuranceEditServicePage from "../components/admin/skidsteer/EditInsuranceServicePage";
//jos
import JobsViewPage from "../components/admin/jobs/View";
import JobsAddPage from "../components/admin/jobs/AddPage";
import JobsChatPage from "../components/admin/jobs/JobChat";
import ChatPage from "../components/chat/Chat";
//dispatches
import DispatchesViewPage from "../components/admin/dispatches/View";

//reports
import ReportsViewPage from "../components/admin/reports/View";
import ReportsListPage from "../components/admin/reports/reportList";
import ReportsActivityPage from "../components/admin/reports/reportActivity";
import ReportsDaySheetPage from "../components/admin/reports/reportDaySheet";
import ReportsMechanicReportPage from "../components/admin/reports/reportMechanicReport";
import ReportsPaymentRefundPage from "../components/admin/reports/reportPaymentRefund";
import ReportsPayrollPage from "../components/admin/reports/reportPayroll";
import ReportsSalesRevenuePage from "../components/admin/reports/reportSalesRevenue";
import ReportsTaxPage from "../components/admin/reports/reportTax";

import EmailPage from "../components/admin/EmailTemplate";
//AccountingViewPage
import AccountingViewPage from "../components/admin/accounting/View";
import AccountingDetails from "../components/admin/accounting/Details";

import AdminLoginPage from "../components/login/AdminLoginPage";
import LoginPage from "../components/login/LoginPage";
import AboutPage from "../components/open/AboutPage";
import ServicePage from "../components/open/ServicePage";
import ContactPage from "../components/open/ContactPage";
import FAQPage from "../components/open/FAQPage";


import RegisterPage from "../components/register/RegisterPage";
import ChangePassword from "../components/ChangePasswordPage";
import RecoverPassword from "../components/RecoverPasswordPage";
import ForgetPassword from "../components/ForgotPasswordPage";


Vue.use(Router);

export const router = new Router({
  mode: "history",
  routes: [
    { path: "/", component: HomePage, meta: { requiresAuth: [Role.Customer] } },
    { path: '/chat', component: ChatPage, name: 'CustomerChat', meta: { requiresAuth: [Role.Customer] } },
    { path: "/home", component: HomePage, name: 'Home', meta: { requiresAuth: [Role.Customer] } },
    { path: "/chat", component: ChatPage, name: 'F_chat', meta: { requiresAuth: [Role.Customer] } },
    { path: "/payment/:unique_id", component: PaymentPage, name: 'Payment', meta: { requiresAuth: [Role.Customer] } },
    { path: "/book-job/:id", component: AddJobPage, name: 'addjobpage', meta: { requiresAuth: [Role.Customer] } },

    { path: "/home", component: HomePage, name: 'MHome', meta: { requiresAuth: [Role.Customer_Manager] } },
    //admin routes
    {
      path: '/admin',
      component: AdminLayout,
      name: 'SuperAdmin',
      meta: { requiresAuth: [Role.admin] },
      children: [
        { path: 'dashboard', component: Dashboard, name: 'Dashboard', meta: { requiresAuth: [Role.Admin] } },
        { path: 'settings', component: Settings, name: 'Settings', meta: { requiresAuth: [Role.Admin] } },
        { path: 'profile', component: ProfilePage, name: 'Profile' },
        { path: 'changepassword', component: ChangePasswordPage, name: 'AChangepassword', meta: { requiresAuth: [Role.Admin] } },
        { path: 'admin', component: AdminListPage, name: 'Admin', meta: { requiresAuth: [Role.Admin] } },
        { path: 'admin/add', component: AdminAddPage, name: 'AdminAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'admin/edit/:id', component: AdminEditPage, name: 'AdminEdit', meta: { requiresAuth: [Role.Admin] } },
        { path: 'admin/view/:id', component: AdminViewPage, name: 'AdminView', meta: { requiresAuth: [Role.Admin] } },

        { path: 'customer', component: CustomerListPage, name: 'Customer', meta: { requiresAuth: [Role.Admin] } },
        { path: 'customer/add', component: CustomerAddPage, name: 'CustomerAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'customer/details/:id', component: CustomerDetails, name: 'CustomerDetail', meta: { requiresAuth: [Role.Admin] } },
        { path: 'customer/farms/:id', component: CustomerDetails, name: 'Customerfarms', meta: { requiresAuth: [Role.Admin] } },
        { path: 'customer/addfarm', component: CustomerAddFarms, name: 'CustomerAddFarms', meta: { requiresAuth: [Role.Admin] } },
        { path: 'customer/addfarmmore/:id', component: CustomerAddMoreFarms, name: 'CustomerAddMoreFarms', meta: { requiresAuth: [Role.Admin] } },

        { path: 'hauler', component: CompanyListPage, name: 'Company', meta: { requiresAuth: [Role.Admin] } },
        { path: 'hauler/add', component: CompanyAddPage, name: 'CompanyAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'hauler/edit/:id', component: CompanyEdit, name: 'CompanyEdit', meta: { requiresAuth: [Role.Admin] } },
        
        { path: 'hauler/drivers/list/:id', component: CompanyDriverListPage, name: 'CompanyDriverListPage', meta: { requiresAuth: [Role.Admin] } },
        { path: 'hauler/driver/add', component: CompanyDriverAddPage, name: 'CompanyDriverAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'hauler/driver/edit/:id', component: CompanyDriverEdit, name: 'CompanyDriverEdit', meta: { requiresAuth: [Role.Admin] } },
        
        { path: 'news', component: NewsListPage, name: 'News', meta: { requiresAuth: [Role.Admin] } },
        { path: 'news/add', component: NewsAddPage, name: 'NewsAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'news/edit/:id', component: NewsEditPage, name: 'NewsEdit', meta: { requiresAuth: [Role.Admin] } },
        
        { path: 'faq', component: FaqListPage, name: 'Faq', meta: { requiresAuth: [Role.Admin] } },
        { path: 'faq/add', component: FaqAddPage, name: 'FaqAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'faq/edit/:id', component: FaqEditPage, name: 'FaqEdit', meta: { requiresAuth: [Role.Admin] } },
        
        { path: 'mechanic', component: MechanicListPage, name: 'Mechanic', meta: { requiresAuth: [Role.Admin] } },
        { path: 'mechanic/add', component: MechanicAddPage, name: 'MechanicAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'mechanic/edit/:id', component: MechanicEditPage, name: 'MechanicEdit', meta: { requiresAuth: [Role.Admin] } },

        { path: 'manager', component: ListPage, name: 'Manager', meta: { requiresAuth: [Role.Admin] } },
        { path: 'manager/add', component: AddPage, name: 'Add', meta: { requiresAuth: [Role.Admin] } },
        { path: 'manager/edit/:id', component: EditPage, name: 'Edit', meta: { requiresAuth: [Role.Admin] } },
        { path: 'manager/view/:id', component: ViewPage, name: 'View', meta: { requiresAuth: [Role.Admin] } },

        { path: 'services', component: SerivcesListPage, name: 'Services', meta: { requiresAuth: [Role.Admin] } },
        { path: 'service/add', component: SerivcesAddPage, name: 'ServiceAdd' },
        { path: 'service/edit/:id', component: SerivcesEditPage, name: 'ServiceEdit', meta: { requiresAuth: [Role.Admin] } },
        { path: 'service/view/:id', component: SerivcesViewPage, name: 'ServiceView', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truckdrivers', component: TruckDriverListPage, name: 'Truckdrivers', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truckdriver/add', component: TruckDriverAddPage, name: 'TruckdriverAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truckdriver/edit/:id', component: TruckDriverEditPage, name: 'TruckdriverEdit', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truckdriver/view/:id', component: TruckDriverViewPage, name: 'TruckdriverView', meta: { requiresAuth: [Role.Admin] } },
        { path: 'trucks', component: TruckListPage, name: 'Trucks', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/add', component: TruckAddPage, name: 'TruckAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/edit/:id', component: TruckEditPage, name: 'TruckEdit', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/view/:id', component: TruckViewPage, name: 'TruckView', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/docview/:id', component: TruckDocViewPage, name: 'TruckDocView', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/service/:id', component: TruckViewServicePage, name: 'TruckViewService', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/addservice/:id', component: TruckAddServicePage, name: 'TruckAddService', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/editservice/:id', component: TruckEditServicePage, name: 'TruckEditService', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/addinsurance/:id', component: TruckInsuranceServicePage, name: 'TruckAddInsurance', meta: { requiresAuth: [Role.Admin] } },
        { path: 'truck/editinsurance/:id', component: TruckInsuranceEditServicePage, name: 'TruckEditInsurance', meta: { requiresAuth: [Role.Admin] } },
        { path: 'skidsteers', component: SkidsteerListPage, name: 'Skidsteers', meta: { requiresAuth: [Role.Admin] } },
        { path: 'skidsteer/add', component: SkidsteerAddPage, name: 'SkidsteerAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'skidsteer/edit/:id', component: SkidsteerEditPage, name: 'SkidsteerEdit', meta: { requiresAuth: [Role.Admin] } },
        { path: 'skidsteer/view/:id', component: SkidsteerViewPage, name: 'SkidsteerView', meta: { requiresAuth: [Role.Admin] } },
        { path: 'skidsteer/docview/:id', component: SkidsteerDocViewPage, name: 'SkidsteerDocView', meta: { requiresAuth: [Role.Admin] } },
        { path: 'Skidsteer/service/:id', component: SkidsteerViewServicePage, name: 'SkidsteerViewService', meta: { requiresAuth: [Role.Admin] } },
        { path: 'Skidsteer/addservice/:id', component: SkidsteerAddServicePage, name: 'SkidsteerAddService', meta: { requiresAuth: [Role.Admin] } },
            { path: 'Skidsteer/editservice/:id', component: skidsteerEditServicePage, name: 'SkidsteerEditService', meta: { requiresAuth: [Role.Admin] } },

    { path: 'Skidsteer/addinsurance/:id', component: skidsteerInsuranceServicePage, name: 'SkidsteerAddInsurance', meta: { requiresAuth: [Role.Admin] } },

   { path: 'Skidsteer/editinsurance/:id', component: skidsteerInsuranceEditServicePage, name: 'SkidsteerEditInsurance', meta: { requiresAuth: [Role.Admin] } },
   
        { path: 'jobs', component: JobsViewPage, name: 'Jobs', meta: { requiresAuth: [Role.Admin] } },
        { path: 'jobs/add', component: JobsAddPage, name: 'JobsAdd', meta: { requiresAuth: [Role.Admin] } },
        { path: 'jobs/chat/:id', component: JobsChatPage, name: 'JobsChat', meta: { requiresAuth: [Role.Admin] } },
        { path: 'jobs/chat', component: ChatPage, name: 'Chat', meta: { requiresAuth: [Role.Admin] } },
        { path: 'dispatches', component: DispatchesViewPage, name: 'Dispatches', meta: { requiresAuth: [Role.Admin] } },
        { path: 'accounting/details/:id', component: AccountingViewPage, name: 'Accounting', meta: { requiresAuth: [Role.Admin] } },
        { path: 'accounting', component: AccountingDetails, name: 'AccountingDetails', meta: { requiresAuth: [Role.Admin] } },
        { path: 'reports', component: ReportsViewPage, name: 'Reports', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-list', component: ReportsListPage, name: 'ReportsList', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-activity', component: ReportsActivityPage, name: 'ReportsActivity', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-day-sheet', component: ReportsDaySheetPage, name: 'ReportsDaySheet', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-mechanic-report', component: ReportsMechanicReportPage, name: 'ReportsMechanicReport', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-payment-refund', component: ReportsPaymentRefundPage, name: 'ReportsPaymentRefund', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-payroll', component: ReportsPayrollPage, name: 'ReportsPayroll', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-sales-revenue', component: ReportsSalesRevenuePage, name: 'ReportsSalesRevenue', meta: { requiresAuth: [Role.Admin] } },
        { path: 'report-tax', component: ReportsTaxPage, name: 'ReportsTax', meta: { requiresAuth: [Role.Admin] } },
        { path: 'emails', component: EmailPage, name: 'Emails', meta: { requiresAuth: [Role.Admin] } },
      ]
    },

    //manager routes
    {
      path: '/manager',
      component: AdminLayout,
      name: 'ManagerDashboard',
      meta: { requiresAuth: Role.Admin_Manager },
      children: [
        { path: 'dashboard', component: Dashboard, name: 'Manager_Dashboard', meta: { requiresAuth: [Role.Admin_Manager] } },
	{ path: 'profile', component: ProfilePage, name: 'MProfile',  meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'changepassword', component: ChangePasswordPage, name: 'MChangepassword', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'jobs', component: JobsViewPage, name: 'manager_Jobs', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'jobs/add', component: JobsAddPage, name: 'manager_JobsAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'jobs/chat/:id', component: JobsChatPage, name: 'manager_JobsChat', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'jobs/chat', component: ChatPage, name: 'manager_Chat', meta: { requiresAuth: [Role.Admin_Manager] } },

	{ path: 'truckdrivers', component: TruckDriverListPage, name: 'MTruckdrivers', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truckdriver/add', component: TruckDriverAddPage, name: 'MTruckdriverAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truckdriver/edit/:id', component: TruckDriverEditPage, name: 'MTruckdriverEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truckdriver/view/:id', component: TruckDriverViewPage, name: 'MTruckdriverView', meta: { requiresAuth: [Role.Admin_Manager] } },

        { path: 'manager', component: ListPage, name: 'MManager', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'manager/add', component: AddPage, name: 'MAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'manager/edit/:id', component: EditPage, name: 'MEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'manager/view/:id', component: ViewPage, name: 'MView', meta: { requiresAuth: [Role.Admin_Manager] } },

        { path: 'skidsteers', component: SkidsteerListPage, name: 'MSkidsteers', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'skidsteer/add', component: SkidsteerAddPage, name: 'MSkidsteerAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'skidsteer/edit/:id', component: SkidsteerEditPage, name: 'MSkidsteerEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'skidsteer/view/:id', component: SkidsteerViewPage, name: 'MSkidsteerView', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'skidsteer/docview/:id', component: SkidsteerDocViewPage, name: 'MSkidsteerDocView', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'Skidsteer/service/:id', component: SkidsteerViewServicePage, name: 'MSkidsteerViewService', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'Skidsteer/addservice/:id', component: SkidsteerAddServicePage, name: 'MSkidsteerAddService', meta: { requiresAuth: [Role.Admin_Manager] } },

    { path: 'Skidsteer/addinsurance/:id', component: skidsteerInsuranceServicePage, name: 'MSkidsteerAddInsurance', meta: { requiresAuth: [Role.Admin_Manager] } },

   { path: 'Skidsteer/editinsurance/:id', component: skidsteerInsuranceEditServicePage, name: 'MSkidsteerEditInsurance', meta: { requiresAuth: [Role.Admin_Manager] } },

        { path: 'trucks', component: TruckListPage, name: 'MTrucks', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/add', component: TruckAddPage, name: 'MTruckAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/edit/:id', component: TruckEditPage, name: 'MTruckEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/view/:id', component: TruckViewPage, name: 'MTruckView', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/docview/:id', component: TruckDocViewPage, name: 'MTruckDocView', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/service/:id', component: TruckViewServicePage, name: 'MTruckViewService', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/addservice/:id', component: TruckAddServicePage, name: 'MTruckAddService', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/editservice/:id', component: TruckEditServicePage, name: 'MTruckEditService', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/addinsurance/:id', component: TruckInsuranceServicePage, name: 'MTruckAddInsurance', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'truck/editinsurance/:id', component: TruckInsuranceEditServicePage, name: 'MTruckEditInsurance', meta: { requiresAuth: [Role.Admin_Manager] } },

        { path: 'services', component: SerivcesListPage, name: 'MServices', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'service/add', component: SerivcesAddPage, name: 'MServiceAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'service/edit/:id', component: SerivcesEditPage, name: 'MServiceEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'service/view/:id', component: SerivcesViewPage, name: 'MServiceView', meta: { requiresAuth: [Role.Admin_Manager] } },


        { path: 'customer', component: CustomerListPage, name: 'MCustomer', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'customer/add', component: CustomerAddPage, name: 'mCustomerAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'customer/details/:id', component: CustomerDetails, name: 'MCustomerDetail', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'customer/farms/:id', component: CustomerDetails, name: 'MCustomerfarms', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'customer/addfarm', component: CustomerAddFarms, name: 'MCustomerAddFarms', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'customer/addfarmmore/:id', component: CustomerAddMoreFarms, name: 'MCustomerAddMoreFarms', meta: { requiresAuth: [Role.Admin] } },

        { path: 'hauler', component: CompanyListPage, name: 'MCompany', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'hauler/add', component: CompanyAddPage, name: 'MCompanyAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'hauler/edit/:id', component: CompanyEdit, name: 'MCompanyEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        
        { path: 'hauler/drivers/list/:id', component: CompanyDriverListPage, name: 'MCompanyDriverListPage', meta: { requiresAuth: [Role.Admin_Manager] } },
	{ path: 'hauler/driver/add', component: CompanyDriverAddPage, name: 'MCompanyDriverAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'hauler/driver/edit/:id', component: CompanyDriverEdit, name: 'MCompanyDriverEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        
        { path: 'news', component: NewsListPage, name: 'MNews', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'news/add', component: NewsAddPage, name: 'MNewsAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'news/edit/:id', component: NewsEditPage, name: 'MNewsEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        
        { path: 'faq', component: FaqListPage, name: 'MFaq', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'faq/add', component: FaqAddPage, name: 'MFaqAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'faq/edit/:id', component: FaqEditPage, name: 'MFaqEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        
        { path: 'mechanic', component: MechanicListPage, name: 'MMechanic', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'mechanic/add', component: MechanicAddPage, name: 'MMechanicAdd', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'mechanic/edit/:id', component: MechanicEditPage, name: 'MMechanicEdit', meta: { requiresAuth: [Role.Admin_Manager] } },
        
        { path: 'dispatches', component: DispatchesViewPage, name: 'MDispatches', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'accounting/details/:id', component: AccountingViewPage, name: 'MAccounting', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'accounting', component: AccountingDetails, name: 'MAccountingDetails', meta: { requiresAuth: [Role.Admin_Manager] } },
        { path: 'reports', component: ReportsViewPage, name: 'MReports', meta: { requiresAuth: [Role.Admin_Manager] } },

      ]
    },
    //driver routes
    {
      path: '/driver',
      component: AdminLayout,
      name: 'DriverDashboard',
      meta: { requiresAuth: Role.Truck_Driver },
      children: [
        { path: 'dashboard', component: TruckDashboard, name: 'driver_Dashboard', meta: { requiresAuth: [Role.Truck_Driver] } },
        { path: 'profile', component: ProfilePage, name: 'DProfile',  meta: { requiresAuth: [Role.Truck_Driver] } },
        { path: 'changepassword', component: ChangePasswordPage, name: 'DChangepassword', meta: { requiresAuth: [Role.Truck_Driver] } },
      ]
    },
    {
      path: '/mechanic',
      component: AdminLayout,
      name: 'MechanicDashboard',
      meta: { requiresAuth: Role.Mechanic },
      children: [
        { path: 'profile', component: ProfilePage, name: 'DProfile',  meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'changepassword', component: ChangePasswordPage, name: 'MEChangepassword', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'skidsteers', component: SkidsteerListPage, name: 'MESkidsteers', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'skidsteer/add', component: SkidsteerAddPage, name: 'MESkidsteerAdd', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'skidsteer/edit/:id', component: SkidsteerEditPage, name: 'MESkidsteerEdit', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'skidsteer/view/:id', component: SkidsteerViewPage, name: 'MESkidsteerView', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'skidsteer/docview/:id', component: SkidsteerDocViewPage, name: 'MESkidsteerDocView', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'Skidsteer/service/:id', component: SkidsteerViewServicePage, name: 'MESkidsteerViewService', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'Skidsteer/addservice/:id', component: SkidsteerAddServicePage, name: 'MESkidsteerAddService', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'Skidsteer/editservice/:id', component: skidsteerEditServicePage, name: 'MESkidsteerEditService', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'Skidsteer/addinsurance/:id', component: skidsteerInsuranceServicePage, name: 'MESkidsteerAddInsurance', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'Skidsteer/editinsurance/:id', component: skidsteerInsuranceEditServicePage, name: 'MESkidsteerEditInsurance', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'trucks', component: TruckListPage, name: 'METrucks', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/add', component: TruckAddPage, name: 'METruckAdd', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/edit/:id', component: TruckEditPage, name: 'METruckEdit', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/view/:id', component: TruckViewPage, name: 'METruckView', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/docview/:id', component: TruckDocViewPage, name: 'METruckDocView', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/service/:id', component: TruckViewServicePage, name: 'METruckViewService', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/addservice/:id', component: TruckAddServicePage, name: 'METruckAddService', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/editservice/:id', component: TruckEditServicePage, name: 'METruckEditService', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/addinsurance/:id', component: TruckInsuranceServicePage, name: 'METruckAddInsurance', meta: { requiresAuth: [Role.Mechanic] } },
        { path: 'truck/editinsurance/:id', component: TruckInsuranceEditServicePage, name: 'METruckEditInsurance', meta: { requiresAuth: [Role.Mechanic] } },
      ]
    },
    { path: "/admin-login", component: AdminLoginPage },
    
    { path: "/about", component: AboutPage },
    { path: "/service", component: ServicePage },
    { path: "/contact", component: ContactPage },
    { path: "/faq", component: FAQPage },
    { path: "/login", component: LoginPage },
    { path: "/register", component: RegisterPage },
    
    { path: "/change-password", component: ChangePassword },
    { path: "/change-password/:hash_code", component: RecoverPassword },
    { path: "/forgot-password", component: ForgetPassword },

    {
      path: '/auth/:provider/callback',
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
  localStorage.setItem("payment", to.query.returnUrl);
  if (requiresAuth) {

    if (!currentUser) {
      // not logged in so redirect to login page with the return url
      return next({ path: "/login", query: { returnUrl: to.path } });
    }
    // check if route is restricted by role
    if (requiresAuth.length && requiresAuth.includes(currentUser.data.user.role_id)) {
      if (!currentUser.data.user.password_changed_at) {
        return next({ path: "/change-password", query: { returnUrl: to.path } });
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
