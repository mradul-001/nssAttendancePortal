<?php

$radius = 200;
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

    // ---------------------- Attendance Verification starts here -------------------------//

    $stmt = $conn->prepare("SELECT *
    FROM slot_data
    WHERE _timestamp_ = (SELECT MAX(_timestamp_) FROM slot_data) AND _roll_ = ?");
    $stmt->bind_param("s", $AAroll_);
    $stmt->execute();
    $result_from_admin_table = $stmt->get_result();

    // Fetch the row
    if ($row = $result_from_admin_table->fetch_assoc()) {

        $AAlatitude = $row['_latitude_'];
        $AAlongitude = $row['_longitude_'];
        $AAtimestamp = $row['_timestamp_'];

        // fetch the fingerprint of the volunteer
        // if fingerprint is in the list with the same roll number, then allow, but if roll number is different, then reject. consider timestamps
        $stmt = $conn->prepare("
        SELECT *
        FROM attendance
        WHERE _fingerprint_ = ? 
        AND _roll_ != ? 
        AND ABS(TIMESTAMPDIFF(SECOND, _timestamp_, ?)) < 600
    ");
        $stmt->bind_param("sss", $fingerprint_, $rollNo_, $AAtimestamp);
        $stmt->execute();
        $result_from_attendance_table = $stmt->get_result();

        // calculate distance and time difference
        $distance = haversineDistance($AAlatitude, $AAlongitude, $latitude_, $longitude_);
        $timestampDifference = abs(strtotime($timestamp_) - strtotime($AAtimestamp));

        if ($row = $result_from_attendance_table->fetch_assoc()) {
            $status_ = -3;
            $message_ = "Action not allowed!";
        } else {
            if ($distance <= $radius && $timestampDifference <= $timeLimit) {
                $message_ = "Attendance marked successfully";
                $status_ = 1;
            } else if ($distance <= $radius && $timestampDifference >= $timeLimit) {
                $message_ = "Attendance window not open!";
                $status_ = 0;
            } else if ($distance >= $radius && $timestampDifference <= $timeLimit) {
                $message_ = "Attendance not marked, out of range!";
                $status_ = -1;
            } else {
                $message_ = "Attendance window not open!";
                $status_ = -2;
            }
        }

        // put all the data in the database
        $stmt = $conn->prepare("INSERT INTO attendance (_roll_, _name_, _department_, _timestamp_, _latitude_, _longitude_, _fingerprint_, _status_, _message_) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssis", $rollNo_, $nameOfStudent_, $deptOfStudent_, $timestamp_, $latitude_, $longitude_, $fingerprint_, $status_, $message_);
        $stmt->execute();

        if ($status_ >= -2) {
            echo $message_;
        } else if ($status_ == -3) {
            echo "Action not allowed!";
        } else {
            echo "Some error occurred!";
        }

    } else {
        echo "Attendance window not running!";
    }

    // ------------------------- Attendance Verification ends here ----------------------------//

    $stmt->close();
    $conn->close();
}
