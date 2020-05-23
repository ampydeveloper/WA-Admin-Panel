import { authenticationService } from "../_services/authentication.service";

export function handleResponse(response) {
  return response.text().then(text => {
    const data = text && JSON.parse(text);
    if (!response.ok) {
      if ([401, 403].indexOf(response.status) !== -1) {
        // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
        authenticationService.logout();
        // location.reload(true);
      } else if ([422, 500].indexOf(response.status) !== -1) {
        for (let [key, value] of Object.entries(data.data)) {
          var x = document.getElementById("snackbar");
          x.innerHTML = `${value}`;
          x.className = "show";
          setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
          return false;
        }
      }
      const error = (data && data.message) || response.statusText;
      return Promise.reject(error);
    }
    return data;
  });
}
