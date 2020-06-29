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

// login
function login() {
  console.log("Login...");
  // read user and password
  var username = document.getElementById("inputusername").value;
  var password = document.getElementById("inputpassword").value;
  // login in API
  fetch("http://localhost/dashboard2020/api/users/login/", {
    headers: {
      username: username,
      password: password,
    },
  })
    .then((res) => {
      console.log(res);
      redirect(res.json());
    })
    .catch(() => {
      console.log("Invalid API");
    });
}

// redirect user
function redirect(userInfo) {
  console.log("Redirecting user...");
  console.log(username);
}
