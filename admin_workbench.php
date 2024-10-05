<?php

session_start();

if ($_SESSION["isAALoggedIn"] == true) {
    $adminUsername = $_SESSION["adminUsername"];
    $adminName = $_SESSION["adminName"];
} else {
    header("Location: admin_login.php");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true); // Get the data in php array format

    $timestamp_ = $data['timestamp'];
    $latitude_ = $data['latitude'];
    $longitude_ = $data['longitude'];
    $conn = new mysqli("localhost", "root", "", "nss_dev");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $stmt = $conn->prepare("INSERT INTO slot_data (_roll_, _name_, _timestamp_, _latitude_, _longitude_) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $adminUsername, $adminName, $timestamp_, $latitude_, $longitude_);

    if ($stmt->execute()) {
        echo "Attendance Window is now open for 10 minutes, ask students to mark their attendance!";
    } else {
        echo "Something went wrong!";
    }

    $stmt->close();
    $conn->close();
}
