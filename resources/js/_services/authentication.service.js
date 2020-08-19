import { BehaviorSubject } from "rxjs";

import { requestOptions } from "../_helpers/request-options";
import { handleResponse } from "../_helpers/handle-response";

import { environment } from "../config/test.env";

const currentUserSubject = new BehaviorSubject(
  JSON.parse(localStorage.getItem("currentUser"))
);

export const authenticationService = {
  login,
  logout,
  register,
  updateProfile,
  changePassword,
  recoverPassword,
  forgetpassword,
  Delete,
  apiUrl: environment.apiUrl,
  currentUrl: '',
  currentUser: currentUserSubject.asObservable(),
  get currentUserValue() {
    return currentUserSubject.value;
  }
};

function register(data) {

  return fetch(
    this.apiUrl+`signup`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function updateProfile(data) {

  return fetch(
    this.apiUrl+`admin/edit-profile`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function login(email, password, requestType) {

  return fetch(
    this.apiUrl+`login`,
    requestOptions.post({ email, password, requestType})
  )
    .then(handleResponse)
    .then(user => {
	// store user details and passport token in local storage to keep user logged in between page refreshes
        localStorage.setItem("currentUser", JSON.stringify(user));
        currentUserSubject.next(user);
      if(user.data.user.role_id === 1 ){
        this.currentUrl = "/admin/dashboard";
      }
      if(user.data.user.role_id === 2 ){
        this.currentUrl = "/manager/dashboard";
      }
      if(user.data.user.role_id === 3 ){
        this.currentUrl = "/driver/dashboard";
      }
      if(user.data.user.role_id === 4){
        this.currentUrl = "Home"; 
      }
      if(user.data.user.role_id === 5){
        this.currentUrl = "Home"; 
      }
      return this.currentUrl;
    });
}
function recoverPassword(data){
  return fetch(
      this.apiUrl+`recover-password`,
      requestOptions.post(data)
    )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function changePassword(data){
    return fetch(
      this.apiUrl+`admin/change-password`,
      requestOptions.post(data)
    )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function logout() {
  // remove user from local storage to log user out
  localStorage.removeItem("currentUser");
  currentUserSubject.next(null);
}
function Delete(data) {
  return fetch(
    this.apiUrl+`admin/delete-manager/`+data,
    requestOptions.get()
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}

function forgetpassword(data) {

  return fetch(
    this.apiUrl+`forgot-password`,
    requestOptions.post(data)
  )
    .then(handleResponse)
    .then(user => {
      // store user details and passport token in local storage to keep user logged in between page refreshes

      return user;
    });
}
