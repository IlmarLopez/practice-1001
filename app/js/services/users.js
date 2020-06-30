import { config } from './config.js';

export function authenticate(username, password) {
  // login in API
  return fetch(config.apis.url + "users/login/", {
    headers: {
      username: username,
      password: password,
    },
  })
    .then(res => res.json())
    .catch(() => "Invalid API");
}

export function logout() {
  sessionStorage.userInfo = null;
  window.location = 'login.html';
}