import { BehaviorSubject } from "rxjs";

import { requestOptions } from "../_helpers/request-options";
import { handleResponse } from "../_helpers/handle-response";

import { environment } from "../config/test.env";

const currentUserSubject = new BehaviorSubject(
  JSON.parse(localStorage.getItem("currentUser"))
);

export const customerService = {
  add,
  addFarm,
  edit,
  getCustomer,
  getFarmAndManager,
  listCustomer,
  getCustomerCard,
  getCustomerRecord,
  updateFarmManager,
  apiUrl: environment.apiUrl,
  currentUrl: '',
  currentUser: currentUserSubject.asObservable(),
  get currentUserValue() {
    return currentUserSubject.value;
  }
};

function add(data) {

  return fetch(
    this.apiUrl+`admin/create-customer`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function addFarm(data) {

  return fetch(
    this.apiUrl+`admin/create-farm`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function edit(data) {
  return fetch(
    this.apiUrl+`admin/update-customer/`+data.id,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function listCustomer(){
      return fetch(
    this.apiUrl+`admin/list-customer`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function getCustomer(data) {
  return fetch(
    this.apiUrl+`admin/get-customer/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getFarmAndManager(data) {
  return fetch(
    this.apiUrl+`admin/get-farm-and-manager/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getCustomerCard(data) {
  return fetch(
    this.apiUrl+`admin/card-list/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getCustomerRecord(data) {
  return fetch(
    this.apiUrl+`admin/record-list/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function updateFarmManager(data) {

  return fetch(
    this.apiUrl+`admin/update-farm-manager`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes
      return user;
    });
}
