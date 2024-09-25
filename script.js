document.getElementsByClassName("logo")[0].addEventListener("click", () => {
  document.location.href = "./index.php";
});

document.addEventListener("click", (event) => {
  if (event.target.id === "to_loginPage") {
    document.location.href = "./login.php";
  } else if (event.target.id === "to_registerPage") {
    document.location.href = "./register.php";
  } else if (event.target.id === "to_logout") {
    document.location.href = "./logout.php";
  } else if (event.target.id === "to_AdminPage") {
    document.location.href = "./admin_login.php";
  } else if (event.target.id === "to_attendancePage") {
    document.location.href = "./attendance.html";
  }
});