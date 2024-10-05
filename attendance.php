<?php

$radius = 50;
$timeLimit = 600;
function haversineDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // radius of the Earth in meters

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
    sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return $distance;
}


// getting the session variables
session_start();

$rollNo_ = $_SESSION["username"];
$nameOfStudent_ = $_SESSION["nameOfStudent"];
$deptOfStudent_ = $_SESSION["deptOfStudent"];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true); // Get the data in php array format

    $AAroll_ = $data['AAroll'];
    $latitude_ = $data['latitude'];
    $longitude_ = $data['longitude'];
    $timestamp_ = $data['timestamp'];
    $fingerprint_ = $data['fingerprint'];

    $conn = new mysqli("localhost", "root", "", "nss_dev");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo $fingerprint_;

    // ---------------------------------- Attendance Verification starts here ------------------------------------//

    $stmt = $conn->prepare("SELECT *
    FROM slot_data
    WHERE _timestamp_ = (SELECT MAX(_timestamp_) FROM slot_data) AND _roll_ = ?");
    $stmt->bind_param("s", $AAroll_);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the row
    if ($row = $result->fetch_assoc()) {

        $AAlatitude = $row['_latitude_'];
        $AAlongitude = $row['_longitude_'];
        $AAtimestamp = $row['_timestamp_'];

        $distance = haversineDistance($AAlatitude, $AAlongitude, $latitude_, $longitude_);
        $timestampDifference = abs(strtotime($timestamp_) - strtotime($AAtimestamp));

        if ($distance <= $radius && $timestampDifference <= $timeLimit) {
            $message_ = "Attendance marked successfully";
            $status_ = 1;
        } else if ($distance <= $radius && $timestampDifference >= $timeLimit) {
            $message_ = "Attendance not marked, time limit exceeded!";
            $status_ = 0;
        } else if ($distance >= $radius && $timestampDifference <= $timeLimit) {
            $message_ = "Attendance not marked, out of range!";
            $status_ = -1;
        } else {
            $message_ = "Attendance window not open!";
            $status_ = -2;
        }

    } else {
        echo "Attendance window not running!";
    }

    // -------------------------------------- Attendance Verification ends here ----------------------------------------//

    $stmt = $conn->prepare("INSERT INTO attendance (_roll_, _name_, _department_, _timestamp_, _latitude_, _longitude_, _fingerprint_, _status_, _message_) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssis", $rollNo_, $nameOfStudent_, $deptOfStudent_, $timestamp_, $latitude_, $longitude_, $fingerprint_, $status_, $message_);

    if ($stmt->execute()) {
        echo $message_;
    } else {
        echo "Some error occurred!";
    }

    $stmt->close();
    $conn->close();
}
