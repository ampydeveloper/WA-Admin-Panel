import { BehaviorSubject } from "rxjs";

import { requestOptions } from "../_helpers/request-options";
import { handleResponse } from "../_helpers/handle-response";

import { environment } from "../config/test.env";

const currentUserSubject = new BehaviorSubject(
  JSON.parse(localStorage.getItem("currentUser"))
);

export const truckService = {
  add,
  edit,
  Delete,
  getTruck,
  listTrucks,
  addService,
  getTruckService,
  updateTruckService,
  getTruckSingleService,
  getTruckSingleInsurance,
  updateInsurance,
  DeleteService,
  DeleteInsurance,
  addInsurance, 
  getTruckInsurance,
  getLastInsu,
  apiUrl: environment.apiUrl,
  currentUrl: '',
  currentUser: currentUserSubject.asObservable(),
  get currentUserValue() {
    return currentUserSubject.value;
  }
};

function add(data) {

  return fetch(
    this.apiUrl+`admin/create-vehicle`,
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
    this.apiUrl+`admin/edit-vehicle/`+data.id,
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
    this.apiUrl+`admin/delete-vehicle/`+data,
    requestOptions.delete()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getTruck(data) {
  return fetch(
    this.apiUrl+`admin/get-vehicle/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function listTrucks() {
  return fetch(
    this.apiUrl+`admin/list-vehicle`,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function addService(data) {

  return fetch(
    this.apiUrl+`admin/create-vehicleservice`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
function addInsurance(data) {

  return fetch(
    this.apiUrl+`admin/create-vehicleinsurance`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getTruckService(data) {
  return fetch(
    this.apiUrl+`admin/get-vehicleservice/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getTruckInsurance(data) {
  return fetch(
    this.apiUrl+`admin/get-vehicleinsurance/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getTruckSingleService(data) {
  return fetch(
    this.apiUrl+`admin/get-service-details/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getTruckSingleInsurance(data){
  return fetch(
    this.apiUrl+`admin/get-insurance-details/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function updateTruckService(data) {
  return fetch(
    this.apiUrl+`admin/save-service-details/`+data.id,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}


function updateInsurance(data) {
  return fetch(
    this.apiUrl+`admin/save-insurance-details/`+data.id,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function getLastInsu(vehicle_id) {
  return fetch(
    this.apiUrl+`admin/get-last-insurance/`+vehicle_id,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}


function DeleteService(data) {
  return fetch(
    this.apiUrl+`admin/delete-service-details/`+data,
    requestOptions.delete()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function DeleteInsurance(data) {
  return fetch(
    this.apiUrl+`admin/delete-insurance-details/`+data,
    requestOptions.delete()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
