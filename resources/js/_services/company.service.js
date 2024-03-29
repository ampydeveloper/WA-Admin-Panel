import { BehaviorSubject } from "rxjs";

import { requestOptions } from "../_helpers/request-options";
import { handleResponse } from "../_helpers/handle-response";

import { environment } from "../config/test.env";

const currentUserSubject = new BehaviorSubject(
  JSON.parse(localStorage.getItem("currentUser"))
);

export const companyService = {
  listHauler,
  add,
  edit,
  getHauler,
  deleteHauler,
  getCustomerCard,
  getCustomerRecord,
  listHaulerDriver,
  addHaulerDriver,
  editHaulerDriver,
  getHaulerDriver,
  deleteHaulerDriver,
  apiUrl: environment.apiUrl,
  currentUrl: '',
  currentUser: currentUserSubject.asObservable(),
  get currentUserValue() {
    return currentUserSubject.value;
  }
};

function listHauler(){
      return fetch(
    this.apiUrl+`admin/list-hauler`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function add(data) {

  return fetch(
    this.apiUrl+`admin/create-hauler`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function edit(data, id) {
  return fetch(
    this.apiUrl+`admin/update-hauler`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getHauler(data) {
  return fetch(
    this.apiUrl+`admin/get-hauler/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function deleteHauler(id) {
  return fetch(
    this.apiUrl+`admin/delete-hauler/`+id,
    requestOptions.delete()
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

function listHaulerDriver(data) {
    return fetch(
    this.apiUrl+`admin/list-hauler-driver/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function addHaulerDriver(data) {
    return fetch(
    this.apiUrl+`admin/create-hauler-driver`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function editHaulerDriver(data, id) {
    return fetch(
    this.apiUrl+`admin/edit-hauler-driver`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getHaulerDriver(data) {
    return fetch(
    this.apiUrl+`admin/get-hauler-driver/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}


function deleteHaulerDriver(id) {
    return fetch(
    this.apiUrl+`admin/delete-hauler-driver/`+id,
    requestOptions.delete()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}