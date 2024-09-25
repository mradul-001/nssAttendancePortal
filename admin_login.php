<?php

session_start();

if (isset($_SESSION["isAALoggedIn"]) && $_SESSION["isAALoggedIn"] === true) {
    header("Location: admin_workbench.html");
    exit(); // no further execution of the code will happen
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $rollNoOfAA_ = $_POST['rollNumber'];
    $passwordOfAA_ = $_POST['password'];

    $conn = new mysqli("localhost", "root", "", "nss_dev");

    // check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM admins WHERE _roll_ = ?");
    $stmt->bind_param("s", $rollNoOfAA_);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch the entire row of data
        $row = $result->fetch_assoc();

        $realPass = $row['_password_'];
        $nameOfAA = $row['_name_'];  // Fetch the AA's name from the row

        if ($passwordOfAA_ == $realPass) {
            session_start();
            $_SESSION["adminUsername"] = $rollNoOfAA_;
            $_SESSION["adminName"] = $nameOfAA;
            $_SESSION["isAALoggedIn"] = true;
            echo
                "<script>
                    window.location.href = 'admin_workbench.html';
            </script>";
        } else {
            echo
                "<script>
                alert('Incorrect Password!');
            </script>";
        }
    } else {
        echo "<script>alert('User doesnt exists')</script>";
    }

}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/nss_logo_.png" type="image/x-icon">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_navbar.css">
</head>

<body>

    <div class="navbar">
        <div class="logo">
            <img src="assets/nss_logo_.png" alt="Logo">
            <span>NSS IITB</span>
        </div>
    </div>


    <div class="content">

        <h2>Activity Associate Login</h2>
        <form id="loginForm" action="admin_login.php" method="POST">

            <label for="rollNumber">Roll Number:</label><br>
            <input type="text" id="rollNumber" name="rollNumber" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>


            <button type="submit">Login</button>
        </form>
    </div>


    <script src="script.js"></script>
</body>

</html>