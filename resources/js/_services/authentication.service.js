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

function login(email, password) {

  return fetch(
    this.apiUrl+`login`,
    requestOptions.post({ email, password })
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
        this.currentUrl = "/"; 
      }
      return this.currentUrl;
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
