import axios from "axios";
import Swal from "sweetalert2";
import { useUserStore } from "@/stores/user";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.headers.common["Content-Type"] = "application/json";
axios.defaults.headers.common["Accept"] = "application/json";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
const baseURL = window.location.origin;

axios.defaults.baseURL = baseURL + "/api";

const intercept_status_codes = [418, 450];

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}
async function getCsrfToken() {
  try {
    await axios.get(baseURL + "/sanctum/csrf-cookie");
  } catch (error) {
    console.error("Error fetching CSRF token", error);
  }
}
axios.interceptors.request.use(async (config) => {
  if (["POST", "PUT", "DELETE"].includes(config.method.toUpperCase())) {
    await getCsrfToken();
  }
  const csrfToken = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  if (csrfToken) {
    config.headers["X-XSRF-TOKEN"] = csrfToken[1];
  }
  return config;
});
axios.interceptors.response.use(
  (response) => response,
  function (error) {
    if (error.response.status === 401) {
      Swal.fire("Oops...", "Session Expired, Please re-login.", "error").then(
        function () {
          window.location = "/login";
        }
      );
    }
    if (intercept_status_codes.includes(error.response.status)) {
      Swal.fire("Error!!", error.response.data.message, "error");
    }
    return Promise.reject(error);
  }
);
window.axios = axios;
const AxiosPlugin = {
  install(app) {
    app.config.globalProperties.axios = axios;
  },
};

export { AxiosPlugin, axios };
