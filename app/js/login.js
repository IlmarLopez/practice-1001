// import functions
import { authenticate } from "./services/users.js";

// event handlers
window.onload = init();
document.getElementById("buttonlogin").addEventListener("click", () => {
  login();
});

// init
function init() {
  console.log("Initializin page...");
  hideErrorLabel();
}

// hide error label
function hideErrorLabel() {
  console.log("Hiding error label");
  document.getElementById("errorlabel").style.display = "none";
}

// show error label
function showErrorLabel(message) {
  console.log("Showing error label");
  document.getElementById("errorlabel").style.display = "block";
  document.getElementById("errorlabel").innerHTML = message;
}

// login
function login() {
  console.log("Login...");
  // read user and password
  var username = document.getElementById("inputusername").value;
  var password = document.getElementById("inputpassword").value;
  // autenticate
  authenticate(username, password)
    .then((data) => {
      redirect(data);
    }).catch((error) => {
      console.log(error);
    });
}

// redirect user
function redirect(userInfo) {
  console.log("Redirecting user...");
  if (userInfo.status === 0) {
    sessionStorage.userInfo = JSON.stringify(userInfo.user);
    if (sessionStorage.previusPage != null)
      window.location = sessionStorage.previusPage;
    else
      window.location = "index.html";
  }
  else {
    showErrorLabel(userInfo.errorMessage);
  }
}
