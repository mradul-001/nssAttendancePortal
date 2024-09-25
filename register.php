<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name_ = $_POST['name'];
    $rollNo_ = $_POST['rollNumber'];
    $password_ = $_POST['password'];
    $phoneNo_ = $_POST['phoneNumber'];
    $department_ = $_POST['department'];

    // Connecting to the database
    $conn = new mysqli("localhost", "root", "", "nss_dev");

    // Checking connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Checking if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE _roll_ = ?");
    $stmt->bind_param("s", $rollNo_);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "alert('Account with this username already exists!')";
    } else {
        // Insert user into the database
        $sql = "INSERT INTO users (_name_, _roll_, _password_, _department_, _mobile_) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name_, $rollNo_, $password_, $department_, $phoneNo_);
        $stmt->execute();
        echo "<script>
                alert('Account created!');
                window.location.href = 'index.php';
            </script>";
    }

    // Close the connection
    $stmt->close();
    $conn->close();

    exit();
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
    <link rel="shortcut icon" href="assets/nss_logo_.png" type="image/x-icon">
    <title>Register</title>
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
        <h2>Student Registration Form</h2>
        <form id="registrationForm" action="register.php" method="POST" onsubmit="return validateForm()">

            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="rollNumber">Roll Number:</label><br>
            <input type="text" id="rollNumber" name="rollNumber" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <label for="department">Department:</label><br>
            <select id="department" name="department" required>
                <option value>Select Department</option>
                <option value="EO">Educational Outreach</option>
                <option value="EnS">Environment And Sustainability</option>
                <option value="CE">Campus Engagement</option>
                <option value="SD">Social Development</option>
            </select><br><br>

            <label for="phoneNumber">Phone Number:</label><br>
            <input type="text" id="phoneNumber" name="phoneNumber" required><br><br>

            <button type="submit">Register</button>
            <div class="login_link"><a href="login.php">Already have an account?</a></div>
        </form>
    </div>

    <script src="script.js"></script>
    <script src="register.js"></script>

</body>

</html>