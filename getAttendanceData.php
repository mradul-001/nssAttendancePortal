<?php

require('./fpdf.php');



// creating a class to handle automatic addition of pages when there are too many rows

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Volunteer List', 0, 1, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 10, 'Roll No.', 1);
        $this->Cell(60, 10, 'Name', 1);
        $this->Cell(40, 10, 'Department', 1);
        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}



$conn = new mysqli('localhost', 'root', '', 'nss_dev');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$department = $_GET['department'];
$sql = "SELECT * FROM attendance WHERE _department_ = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $department);
$stmt->execute();
$result = $stmt->get_result();


// making of the pdf

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$maxRowsPerPage = 40;
$rowCount = 0;
$pdf->Cell(0, 10, 'Attendance data of ' . $department . ' Department', 0, 1, 'C');
$pdf->Cell(20, 10, 'Roll Number', 1);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(40, 10, 'Time of marking attendance', 1);
$pdf->Cell(20, 10, 'Latitude', 1);
$pdf->Cell(20, 10, 'Longitude', 1);
$pdf->Cell(50, 10, 'Remarks', 1);
$pdf->Ln();

while ($row = $result->fetch_assoc()) {
    
    $pdf->Cell(20, 10, $row['_roll_'], 1);
    $pdf->Cell(40, 10, $row['_name_'], 1);
    $pdf->Cell(40, 10, $row['_timestamp_'], 1);
    $pdf->Cell(20, 10, $row['_latitude_'], 1);
    $pdf->Cell(20, 10, $row['_longitude_'], 1);
    $pdf->Cell(50, 10, $row['_message_'], 1);
    $pdf->Ln();
    $rowCount++;

    if ($rowCount % $maxRowsPerPage == 0) {
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 8);
    }

}

$conn->close();
$pdf->Output('D', 'attendance_' . $department . '.pdf');