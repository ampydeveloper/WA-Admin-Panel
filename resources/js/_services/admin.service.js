import { BehaviorSubject } from "rxjs";

import { requestOptions } from "../_helpers/request-options";
import { handleResponse } from "../_helpers/handle-response";

import { environment } from "../config/test.env";

const currentUserSubject = new BehaviorSubject(
  JSON.parse(localStorage.getItem("currentUser"))
);

export const adminService = {
  add,
  edit,
  Delete,
  getAdmin,
  listAdmin,
  dashboardData,
  dashboardDataFilters,
  dashboardJobs,
  apiUrl: environment.apiUrl,
  currentUrl: '',
  currentUser: currentUserSubject.asObservable(),
  get currentUserValue() {
    return currentUserSubject.value;
  }
};

function add(data) {

  return fetch(
    this.apiUrl + `admin/create-admin`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function edit(data, managerId) {
  return fetch(
    this.apiUrl + `admin/edit-admin-profile`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function Delete(data) {
  return fetch(
    this.apiUrl + `admin/delete-manager/` + data,
    requestOptions.delete()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function listAdmin() {
  return fetch(
    this.apiUrl + `admin/list-admin`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function dashboardData() {
  return fetch(
    this.apiUrl + `admin/dashboard`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function dashboardDataFilters(data) {
  return fetch(
    this.apiUrl + `admin/dashboard-filters`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function dashboardJobs(data) {
  return fetch(
    this.apiUrl + `admin/job-by-map`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes
      return user;
    });
}
function getAdmin(data) {
  return fetch(
    this.apiUrl + `admin/get-admin/` + data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

