import { BehaviorSubject } from "rxjs";

import { requestOptions } from "../_helpers/request-options";
import { handleResponse } from "../_helpers/handle-response";

import { environment } from "../config/test.env";

const currentUserSubject = new BehaviorSubject(
  JSON.parse(localStorage.getItem("currentUser"))
);

export const jobService = {
  getCustomer,
  getManager,
  getFrams,
  listService,
  servicesTimeSlots,
  createJob,
  singleJob,
  getFarm,
  getFarmManager,
  chatList,
  storeMessage,
  joblist,
  dispatchJobList,
  jobassigned,
  jobcomplete,
  jobopned,
  jobrepeating,
  jobunpaid,
  apiUrl: environment.apiUrl,
  currentUrl: '',
  currentUser: currentUserSubject.asObservable(),
  get currentUserValue() {
    return currentUserSubject.value;
  }
};

function getCustomer() {
  return fetch(
    this.apiUrl+`admin/job-customer`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function listService() {
  return fetch(
    this.apiUrl+`admin/list-services`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}


function getManager(id) {
  return fetch(
    this.apiUrl+`admin/get-customer/`+id,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function servicesTimeSlots(id) {
  return fetch(
    this.apiUrl+`admin/get-service-slots/`+id,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function createJob(data) {
  return fetch(
    this.apiUrl+`admin/create-job`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getFrams(data) {
  return fetch(
    this.apiUrl+`admin/job-farms/`+data.customer_id+'/'+data.manager_id,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function getFarmManager(data){
  return fetch(
    this.apiUrl+`admin/get-farm-manager/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function joblist(data) {
  return fetch(
    this.apiUrl+`admin/job-list`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function getFarm(data) {
  return fetch(
    this.apiUrl+`admin/get-farm/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function dispatchJobList() {
  return fetch(
    this.apiUrl+`admin/dispatch-job-list`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function jobassigned() {
  return fetch(
    this.apiUrl+`admin/assigned-job-list`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function jobcomplete() {
  return fetch(
    this.apiUrl+`admin/complete-job-list`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function jobopned() {
  return fetch(
    this.apiUrl+`admin/open-job-list`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function jobrepeating() {
  return fetch(
    this.apiUrl+`admin/repeating-job-list`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function jobunpaid() {
  return fetch(
    this.apiUrl+`admin/unpaid-job-list`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}


function singleJob(job_id) {
  return fetch(
    this.apiUrl+`admin/single-job/`+job_id,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function chatList() {
  return fetch(
    this.apiUrl + `admin/message`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(chat => {
      return chat;
    });
}

function storeMessage(data) {
  return fetch(
    this.apiUrl + `admin/message`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(chat => {
      return chat;
    });
}


