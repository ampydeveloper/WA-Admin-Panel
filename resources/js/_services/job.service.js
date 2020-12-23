import { BehaviorSubject } from "rxjs";

import { requestOptions } from "../_helpers/request-options";
import { handleResponse } from "../_helpers/handle-response";

import { environment } from "../config/test.env";

const currentUserSubject = new BehaviorSubject(
    JSON.parse(localStorage.getItem("currentUser"))
);

export const jobService = {
  getCustomer,
  getJobChatMessages,
  chatUsers,
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
  dispatchlist,
  dispatchAllJoblist,
  // dispatchJoblist,
  jobassigned,
  jobcomplete,
  jobopned,
  jobrepeating,
  jobunpaid,
  listDrivers,
  listTrucks,
  listSkidsteers,
  update,
  deleteJob,
  getHaulerDrivers,
  getEmails,
  chatImageUpload,
  updateEmail,
  getReport,
  apiUrl: environment.apiUrl,
  // chatUrl: 'http://13.235.151.113:3100/',
  chatUrl: 'http://wa.customer.leagueofclicks.com/',
  currentUrl: '',
  currentUser: currentUserSubject.asObservable(),
  get currentUserValue() {
    return currentUserSubject.value
  }
};

function getCustomer() {
    return fetch(
            this.apiUrl + `admin/job-customer`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function getJobChatMessages(data) {
    return fetch(
            this.apiUrl + `job-chat`,
            requestOptions.post(data)
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function listService(rid) {
    return fetch(
            this.apiUrl + `admin/list-services/${rid}`,
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
            this.apiUrl + `admin/get-customer/` + id,
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
            this.apiUrl + `admin/get-service-slots/` + id,
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
            this.apiUrl + `admin/create-job`,
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
            this.apiUrl + `admin/job-farms/` + data.customer_id + '/' + data.manager_id,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function getFarmManager(data) {
    return fetch(
            this.apiUrl + `admin/job-farms-manager/` + data,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function joblist() {
    return fetch(
            this.apiUrl + `admin/job-list`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}
function deleteJob(data) {
    return fetch(
      this.apiUrl + `admin/cancel-booked-job`,
      requestOptions.post({job_id:data})
    )
      .then(handleResponse)
      .then(user => {
        return user;
      });

}
function dispatchAllJoblist() {
    return fetch(
            this.apiUrl + `admin/dispatches`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function dispatchlist() {
    return fetch(
            this.apiUrl + `test-task`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function getFarm(data) {
    return fetch(
            this.apiUrl + `admin/get-farm/` + data,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}
// function dispatchJobList() {
//   return fetch(
//     this.apiUrl+`admin/dispatch-job-list`,
//     requestOptions.get()
//   )
//     .then(handleResponse)
//     .then(user => {
//       // store user details and passport token in local storage to keep user logged in between page refreshes

//       return user;
//     });
// }

function jobassigned() {
    return fetch(
            this.apiUrl + `admin/assigned-job-list`,
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
            this.apiUrl + `admin/complete-job-list`,
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
            this.apiUrl + `admin/open-job-list`,
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
            this.apiUrl + `admin/repeating-job-list`,
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
            this.apiUrl + `admin/unpaid-job-list`,
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
            this.apiUrl + `admin/single-job/` + job_id,
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

function chatUsers(data) {
    console.log(this.apiUrl + `chat-members/`+data);
  return fetch( 
    this.apiUrl + `chat-members/`+data,
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

function listDrivers() {
    return fetch(
            this.apiUrl + `admin/list-drivers`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(drivers => {
            return drivers;
        });
}

function listTrucks() {
    return fetch(
            this.apiUrl + `admin/list-vehicle`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(trucks => {
            return trucks;
        });
}

function listSkidsteers() {
    return fetch(
            this.apiUrl + `admin/list-vehicle-skidsteer`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(skidsteers => {
            return skidsteers;
        });
}

function update(type, id, jid) {
    return fetch(
            this.apiUrl + `admin/update-driver-vehicle/${jid}/${type}/${id}`,
            requestOptions.patch()
        )
        .then(handleResponse)
        .then(status => {
            return status;
        });
}

function getHaulerDrivers(data){
    return fetch(
            this.apiUrl + `admin/job-hauler-drivers/` + data,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function chatImageUpload(data) {
    return fetch(
      this.apiUrl+`uploadImage`,
      requestOptions.post(data)
    )
      .then(handleResponse)
      .then(user => {
        // store user details and passport token in local storage to keep user logged in between page refreshes
          return user;
      });
  }

  function getEmails() {
    return fetch(
            this.apiUrl + `admin/get-emails`,
            requestOptions.get()
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function updateEmail(data) {
    return fetch(
            this.apiUrl + `admin/save-emails`,
            requestOptions.post(data)
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}

function getReport(data) {
    return fetch(
            this.apiUrl + `admin/`+data.type,
            requestOptions.post(data)
        )
        .then(handleResponse)
        .then(user => {
            // store user details and passport token in local storage to keep user logged in between page refreshes

            return user;
        });
}
