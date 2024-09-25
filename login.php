<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $rollNo_ = $_POST["rollNumber"];
    $password_ = $_POST["password"];

    $conn = new mysqli("localhost", "root", "", "nss_dev");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE _roll_ = ?");
    $stmt->bind_param("s", $rollNo_);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch the entire row of data
        $row = $result->fetch_assoc();

        $realPass = $row['_password_'];
        $nameOfStudent = $row['_name_'];
        $deptOfStudent = $row['_department_'];

        if ($password_ == $realPass) {
            session_start();
            $_SESSION["username"] = $rollNo_;
            $_SESSION["nameOfStudent"] = $nameOfStudent;
            $_SESSION["deptOfStudent"] = $deptOfStudent;
            $_SESSION["isLoggedIn"] = true;

            $nameParts = explode(' ', $nameOfStudent, 2); // Limit to 2 parts
            $firstName = $nameParts[0]; // Get the first part of the name
            $_SESSION["firstName"] = $firstName;

            echo "<script>
                    window.location.href = 'index.php';
                </script>";


        } else {
            echo "  <script>
                        alert('Incorrect Password!');
                    </script>";
        }
    } else {
        echo "<script>alert('User doesnt exists')</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="style_navbar.css">
    <link rel="stylesheet" href="style.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.css"> -->
    <link rel="shortcut icon" href="assets/nss_logo_.png" type="image/x-icon">
    <title>Login</title>
</head>

<body>

    <div class="navbar">
        <div class="logo">
            <img src="assets/nss_logo_.png" alt="Logo">
            <span>NSS IITB</span>
        </div>
        <div class="links">
            <a href="index.php#about"><button>About</button></a>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSdlQEjMT2q95-LvYhMgCZCOOfGorvXEobkBWWUt4e8HDrMPDw/viewform?pli=1"
                , target="_blank"><button>Review</button></a>
        </div>
    </div>

    <div class="content">

        <h2>Volunteer Login</h2>

        <form id="loginForm" action="login.php" method="POST">

            <label for="rollNumber">Roll Number:</label><br>
            <input type="text" id="rollNumber" name="rollNumber" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>


            <button type="submit">Login</button>
            <div class="login_link"><a href="register.php">Don't have an account?</a></div>

        </form>
    </div>
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="script.js"></script>


</body>

</html>