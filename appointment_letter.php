<?php
require('fpdf/fpdf.php');
include "config.php";

/* 📌 GET APPOINTMENT ID */
if(!isset($_GET['id'])){
    die("Invalid Access");
}

$id = $_GET['id'];

/* 🔗 FETCH DATA (JOIN ALL TABLES) */
$query = mysqli_query($conn,"
SELECT a.*, p.Patient_Name, p.Contact_Number,
       d.Doctor_Name, d.Specialization,
       h.Name AS Hospital_Name, h.Location
FROM appointments a
JOIN patients p ON a.Patient_ID = p.Patient_ID
JOIN doctors d ON a.Doctor_ID = d.Doctor_ID
JOIN hospitals h ON d.Hospital_ID = h.Hospital_ID
WHERE a.Appointment_ID='$id'
");

$data = mysqli_fetch_assoc($query);

/* 📄 CREATE PDF */
$pdf = new FPDF();
$pdf->AddPage();

/* 🏥 PROJECT TITLE */
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Centralized Hospital & Healthcare Management System',0,1,'C');

$pdf->Ln(5);

/* 📄 LETTER TITLE */
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Appointment Confirmation Letter',0,1,'C');

$pdf->Ln(10);

/* 📋 DETAILS */
$pdf->SetFont('Arial','',12);

$pdf->Cell(0,8,'Appointment ID: '.$data['Appointment_ID'],0,1);
$pdf->Cell(0,8,'Patient Name: '.$data['Patient_Name'],0,1);
$pdf->Cell(0,8,'Contact: '.$data['Contact_Number'],0,1);

$pdf->Ln(5);

$pdf->Cell(0,8,'Doctor Name: '.$data['Doctor_Name'],0,1);
$pdf->Cell(0,8,'Specialization: '.$data['Specialization'],0,1);

$pdf->Ln(5);

$pdf->Cell(0,8,'Hospital: '.$data['Hospital_Name'],0,1);
$pdf->Cell(0,8,'Location: '.$data['Location'],0,1);

$pdf->Ln(5);

$pdf->Cell(0,8,'Date: '.$data['Appointment_Date'],0,1);
$pdf->Cell(0,8,'Time: '.$data['Slot_Time'],0,1);

$pdf->Ln(10);

/* 📌 FOOTER MESSAGE */
$pdf->MultiCell(0,8,
"This is a system generated appointment confirmation. Please arrive 10 minutes early."
);

$pdf->Ln(15);

$pdf->Cell(0,8,'Signature',0,1,'R');

/* 📥 DOWNLOAD */
$pdf->Output('D', 'Appointment_'.$id.'.pdf');
?>
