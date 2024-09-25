<?php

require('./fpdf.php');

$conn = new mysqli('localhost', 'root', '', 'nss_dev');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$department = $_GET['department'];
$sql = "SELECT * FROM users WHERE _department_ = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $department);
$stmt->execute();
$result = $stmt->get_result();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$maxRowsPerPage = 40;
$rowCount = 0;
$pdf->Cell(0, 10, 'Volunteers of ' . $department . ' Department', 0, 1, 'C');
$pdf->Cell(40, 10, 'Roll Number', 1);
$pdf->Cell(60, 10, 'Name', 1);
$pdf->Cell(60, 10, 'Mobile Number', 1);
$pdf->Ln();

while ($row = $result->fetch_assoc()) {

    $pdf->Cell(40, 10, $row['_roll_'], 1);
    $pdf->Cell(60, 10, $row['_name_'], 1);
    $pdf->Cell(60, 10, $row['_mobile_'], 1);
    $pdf->Ln();
    $rowCount++;

    if ($rowCount % $maxRowsPerPage == 0) {
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
    }

}

$conn->close();
$pdf->Output('D', 'volunteers_' . $department . '.pdf');

// The code first initializes an FPDF object and adds a new page to the PDF. It then sets the font to Arial, bold, and size 12. The title "Volunteers from [Department]" is added at the top center of the page. Next, it creates table headers for "Roll No", "Mobile number" and "Name" and moves to the next line. The code iterates over the fetched data, adding each volunteer's roll number and name as a row in the table, with each cell having a border. Finally, the generated PDF is output and prompted for download with a filename that includes the department name.