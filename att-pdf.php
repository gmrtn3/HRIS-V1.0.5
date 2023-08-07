<?php
//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 011 for TCPDF class
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('TCPDF-main/tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

    // Load table data from database
    public function LoadData() {
        include 'config.php';
        $select = "SELECT status, empid, date, time_in, time_out, late, early_out, overtime, total_work, total_rest FROM attendances";
        $query = mysqli_query($conn, $select);
        
        $data = array();
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        
        return $data;
        }

    // Colored table
    public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(0, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(25, 28, 28, 28,28, 28, 28, 28, 28, 28);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 6, $row['status'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['empid'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['date'], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['time_in'], 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, $row['time_out'], 'LR', 0, 'L', $fill);
            $this->Cell($w[5], 6, $row['late'], 'LR', 0, 'L', $fill);
            $this->Cell($w[6], 6, $row['early_out'], 'LR', 0, 'L', $fill);
            $this->Cell($w[7], 6, $row['overtime'], 'LR', 0, 'L', $fill);
            $this->Cell($w[8], 6, $row['total_work'], 'LR', 0, 'L', $fill);
            $this->Cell($w[9], 6, $row['total_rest'], 'LR', 0, 'L', $fill);
            
            
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);

$pdf->SetTitle('HRIS Attendance PDF');
$pdf->SetSubject('TCPDF Tutorial');


// set default header data
// set default header data
$pdf->SetHeaderData('', 30, 'HRIS Attendance', 'by SLASH - slash-tech.com \ www.slash-tech.com');




// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

// column titles
$header = array('Status', 'Employee ID', 'Date', 'Time In', 'Time Out', 'Late', 'Early Out', 'Overtime', 'Total Work', 'Total Rest');

// data loading
$data = $pdf->LoadData();

// print colored table
$pdf->ColoredTable($header, $data);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('Employee Attendance.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+