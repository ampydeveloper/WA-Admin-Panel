import { authenticationService } from "../_services/authentication.service";

export const requestOptions = {
  get() {
    return {
      method: "GET",
      ...headers()
    };
  },
  post(body) {
    return {
      method: "POST",
      ...headers(),
      body: JSON.stringify(body)
    };
  },
  patch(body) {
    return {
      method: "PATCH",
      ...headers(),
      body: JSON.stringify(body)
    };
  },
  put(body) {
    return {
      method: "PUT",
      ...headers(),
      body: JSON.stringify(body)
    };
  },
  delete() {
    return {
      method: "DELETE",
      ...headers()
    };
  }
};

function headers() {
  const currentUser = authenticationService.currentUserValue || {};
  const authHeader = currentUser.data !== undefined
    ? { Authorization: "Bearer " + currentUser.data.access_token }
    : {};
  return {
    headers: {
      ...authHeader,
      "Content-Type": "application/json",
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': Laravel.csrfToken
    }
  };
}
